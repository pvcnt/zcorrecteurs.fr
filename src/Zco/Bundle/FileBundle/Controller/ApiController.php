<?php

/**
 * zCorrecteurs.fr est le logiciel qui fait fonctionner www.zcorrecteurs.fr
 *
 * Copyright (C) 2012 Corrigraphie
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Zco\Bundle\FileBundle\Controller;

use Zco\Bundle\FileBundle\Mediawiki\Request as MWRequest;
use Zco\Bundle\FileBundle\Exception\UploadRejectedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Contrôleur gérant toutes les actions liées à l'API du bundle. Ces actions 
 * sont normalement appelées lors d'opérations asynchrones.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class ApiController extends Controller
{
	/**
	 * Récupère la liste des fichiers correspondant à une recherche donnée. Les 
	 * fichiers sont filtrés par dossier ainsi que selon une chaîne donnée 
	 * (optionnelle).
	 *
	 * @return Response Réponse JSON contenant la liste des fichiers retrouvés
	 *				  (les données sont formatées et prêtes à l'affichage)
	 */
	public function searchAction($folder, $entities)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$search = !empty($_POST['search']) ? trim($_POST['search']) : '';
		$files = \Doctrine_Core::getTable('File')
			->getByFolderAndSearch((int) $folder, $_SESSION['id'], $search, $entities);
		
		$response = array();
		foreach ($files as $i => $file)
		{
			$response[] = array(
				'id'			 => (int) $file['id'],
				'name'		   	 => htmlspecialchars($file['name']),
				'size'		  	 => sizeformat($file['size']),
				'date'		   	 => dateformat($file['date']),
				'thumbnail_path' => htmlspecialchars($file->getImageWebPath()),
				'path'		     => htmlspecialchars($file->getWebPath()),
			);
		}
		
		return new Response(json_encode($response));
	}
	
	/**
	 * Effectue une recherche sur Wikimédia Commons pour trouver une liste de 
	 * fichiers correspondant à une chaîne de recherche donnée.
	 *
	 * @return Response Réponse JSON contenant la liste des fichiers retrouvés
	 *				  (les données sont formatées et prêtes à l'affichage)
	 */
	public function searchCommonsAction()
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$search   = isset($_POST['search']) ? trim($_POST['search']) : '';
		$response = array();
		
		if (!empty($search))
		{
			$request = new MWRequest\Query();
			$request->useGenerator('allimages', array(
				'limit' => 10,
				'from' => $search,
			));
			$request->useProperty('categories', array(
				'clshow' => 'hidden',
			));
			$request->useProperty('imageinfo', array(
				'prop' => array('size', 'mime', 'timestamp', 'url'),
				'urlwidth' => 150,
				'urlheight' => 80,
			));
			
			$ret = $this->get('zco_file.mediawiki_api.wikimedia_commons')
				->request($request);
			$files = $ret['query']['pages'];
			
			foreach ($files as $i => $file)
			{
				$response[] = array(
					'id'			 => (int) $file['pageid'],
					'name'		   => substr($file['title'], strpos($file['title'], ':') + 1),
					'size'		   => sizeformat($file['imageinfo'][0]['size']),
					'date'		   => dateformat($file['imageinfo'][0]['timestamp']),
					'thumbnail_path' => !empty($file['imageinfo'][0]['thumberror']) ? '/bundles/fichiers/img/placeholder.png' : htmlspecialchars($file['imageinfo'][0]['thumburl']),
					'path'		   => htmlspecialchars($file['imageinfo'][0]['descriptionurl']),
				);
			}
		}
		
		return new Response(json_encode($response));
	}
	
	/**
	 * Modifie le nom et la license d'un fichier donné.
	 *
	 * @param  integer $id L'identifiant du fichier
	 * @return Response Réponse JSON
	 */
	public function editAction($id)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$file = \Doctrine_Core::getTable('File')->find($id);
		if (!$file)
		{
			throw new NotFoundHttpException(sprintf('Cannot find file #%s.', $id));
		}
		if ($file['user_id'] != $_SESSION['id'])
		{
			throw new AccessDeniedHttpException(sprintf('Not allowed to access file #%s.', $id));
		}
		
		if (!empty($_POST['name']))
		{
			$file['name'] = trim($_POST['name']);
		}
		if (!empty($_POST['license']) && !empty($_POST['pseudo']))
		{
			$license = \Doctrine_Core::getTable('Licence')->find($_POST['license']);
			
			if ($license['id'] != $file['licence_id'])
			{
				$file['license_id'] = $license['id'];
				
				$license = new \FichierLicense();
				$license['file_id']	= $file['id'];
				$license['license_id'] = $file['licence_id'];
				$license['pseudo']	   = trim($_POST['pseudo']);
				$license->save();
			}
		}
		$file->save();
		
		return new Response(json_encode(array('status' => 'OK')));
	}
	
	public function usageAction()
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$usage  = \Doctrine_Core::getTable('File')->getSpaceUsage($_SESSION['id']) / (1000 * 1000);
		$quota  = (int) verifier('fichiers_quota');
		$ratio  = $quota > -1 ? ($quota > 0 ? ceil(100 * $usage / $quota) : 100) : 0;
		
		//Colore la barre en fonction du quota utilisé.
		//< 50 % : OK, >= 50 % et < 80 % : attention, > 80 % : danger
		$usageClass = $ratio > 80 ? 'danger' : ($ratio < 50 ? 'success' : 'warning');
		
		return new Response(json_encode(array(
			'status'	 => 'OK',
			'usage'	  => $usage,
			'quota'	  => $quota,
			'ratio'	  => $ratio,
			'usageClass' => $usageClass,
		)));
	}
	
	public function saveAction($id)
	{
		$file = \Doctrine_Core::getTable('File')->find($id);
		if (!$file)
		{
			throw new NotFoundHttpException(sprintf('Cannot find file #%s.', $id));
		}
		/*if ($file['user_id'] != $_SESSION['id'])
		{
			throw new AccessDeniedHttpException(sprintf('Not allowed to access file #%s.', $id));
		}*/
		
		$this->get('gaufrette.uploads_filesystem')->write($file->getRelativePath(), file_get_contents($_REQUEST['url']));
		
		return new Response(json_encode(array('status' => 'OK')));
	}
	
	/**
	 * Supprime un fichier donné.
	 *
	 * @param  integer $id L'identifiant du fichier
	 * @return Response Réponse JSON
	 */
	public function deleteAction($id)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$file = \Doctrine_Core::getTable('File')->find($id);
		if (!$file)
		{
			throw new NotFoundHttpException(sprintf('Cannot find file #%s.', $id));
		}
		if ($file['user_id'] != $_SESSION['id'])
		{
			throw new AccessDeniedHttpException(sprintf('Not allowed to access file #%s.', $id));
		}
		
		$file->delete();
		
		return new Response(json_encode(array('status' => 'OK')));
	}
}
