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

use Zco\Bundle\FileBundle\Mediawiki\API;
use Zco\Bundle\FileBundle\Mediawiki\Request as MWRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Contrôleur par défaut gérant les actions accessibles depuis l'interface.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class DefaultController extends Controller
{
	private $smartFolders;
	
	/**
	 * Constructeur.
	 */
	public function __construct()
	{
		$this->smartFolders = \Doctrine_Core::getTable('File')->getSmartFolders();
		$this->contentFolders = \Doctrine_Core::getTable('File')->getContentFolders($_SESSION['id']);
	}
	
	/**
	 * Affichage d'un formulaire destiné à recueillir les fichiers que 
	 * l'utilisateur souhaite envoyer vers le module.
	 *
	 * @param Request $request
	 */
	public function indexAction(Request $request)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		\Page::$titre = 'Gestionnaire de fichiers';
		$vars = $this->getVariables($request);

		//Données en Mo.
		$usage  = \Doctrine_Core::getTable('File')->getSpaceUsage($_SESSION['id']) / (1000 * 1000);
		$quota  = (int) verifier('fichiers_quota');
		$ratio  = $quota > -1 ? ($quota > 0 ? ceil(100 * $usage / $quota) : 100) : 0;
		
		//Colore la barre en fonction du quota utilisé.
		//< 50 % : OK, >= 50 % et < 80 % : attention, > 80 % : danger
		$usageClass = $ratio > 80 ? 'danger' : ($ratio < 50 ? 'success' : 'warning');
		
		return render_to_response(
			'ZcoFileBundle::index.html.php', array_merge(array(
				'currentPage' => 'index',
				'usage'	   => $usage,
				'quota'		  => $quota,
				'ratio'	   => $ratio,
				'usageClass'  => $usageClass,
				'redirectUrl' => $this->generateUrl(
					'zco_file_folder', array(
						'id'	   => \FileTable::FOLDER_LAST_IMPORT, 
						'textarea' => $vars['textarea'], 
						'input'	   => $vars['input']
					)
				),
			), $vars)
		);
	}
	
	/**
	 * Téléverse un ou plusieurs fichiers vers le site. Les fichiers sont 
	 * attendus sous la clé "file".
	 *
	 * @param Request $request
	 */
	public function uploadAction(Request $request)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		$retval = $this->get('zco_file.uploader')->batchUpload($request, array(
			'user_id'   => $_SESSION['id'],
			'pseudo'	=> $_SESSION['pseudo'],
		));
		
		$_SESSION['fichiers']['last_import'] = array();
		foreach ($retval['success'] as $item)
		{
			$_SESSION['fichiers']['last_import'][] = $item['id'];
		}
		
		$vars = $this->getVariables($request);
		
		$failed = $retval['failed'];
		if (count($failed) > 0)
		{
			$message = array();
			foreach ($failed as $item)
			{
				$message[] = 'Erreur lors de l\'envoi de '.$item['name'].  '('.
					(isset($item['message']) ? $item['message'] : 'erreur inconnue').').';
			}
			
			return redirect(implode("\n", $message), 
				count($failed) >= $retval['total'] ? 
					$this->generateUrl('zco_file_folder', array(
						'id' => \FileTable::FOLDER_LAST_IMPORT,
						'input'    => $vars['input'], 
						'textarea' => $vars['textarea'],
					))
					: $this->generateUrl('zco_file_index', array(
						'input'    => $vars['input'], 
						'textarea' => $vars['textarea'],
					)),
				MSG_ERROR);
		}
		
		return redirect('Tous les fichiers ont été envoyés avec succès.', 
			$this->generateUrl('zco_file_folder', array(
				'id'       => \FileTable::FOLDER_LAST_IMPORT, 
				'input'    => $vars['input'], 
				'textarea' => $vars['textarea'],
			))
		);
		
		return new Response(json_encode($response));
	}
	
	/**
	 * Affichage d'une page permettant de rechercher rapidement des images 
	 * sur Wikimédia Commons.
	 *
	 * @param Request $request
	 */
	public function commonsAction(Request $request)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		\Page::$titre = 'Rechercher des fichiers sur Wikimédia Commons';
		
		return render_to_response(
			'ZcoFileBundle::commons.html.php', $this->getVariables($request, array(
				'currentPage' => 'commons',
			))
		);
	}
	
	/**
	 * Affichage des fichiers contenus dans un dossier.
	 *
	 * @param Request $request
	 * @param integer $id
	 * @param string $entities
	 */
	public function folderAction(Request $request, $id, $entities)
	{
		if (!verifier('connecte'))
		{
			throw new AccessDeniedHttpException();
		}
		
		//Données en Mo.
		$usage  = \Doctrine_Core::getTable('File')->getSpaceUsage($_SESSION['id']) / (1000 * 1000);
		$quota  = (int) verifier('fichiers_quota');
		$ratio  = $quota > -1 ? ($quota > 0 ? ceil(100 * $usage / $quota) : 100) : 0;
		
		//Colore la barre en fonction du quota utilisé.
		//< 50 % : OK, >= 50 % et < 80 % : attention, > 80 % : danger
		$usageClass = $ratio > 80 ? 'danger' : ($ratio < 50 ? 'success' : 'warning');
		
		$folder = $this->getSmartFolder((int) $id);
		if (!empty($entities))
		{
			$contentFolder = $this->getContentFolder($entities);
		}
		else
		{
			$contentFolder = null;
		}
		\Page::$titre = $folder['name'];
		
		return render_to_response(
			'ZcoFileBundle::folder.html.php', $this->getVariables($request, array(
				'currentPage'   => 'folder',
				'currentFolder' => $folder,
				'currentContentFolder' => $contentFolder,
				'usage'		    => $usage,
				'quota'			=> $quota,
				'ratio'		    => $ratio,
				'usageClass'	=> $usageClass,
			))
		);
	}
	
	/**
	 * Affichage du détail des informations sur un fichier.
	 * 
	 * @param Request $request
     * @param integer $id
	 */
	public function fileAction(Request $request, $id)
	{
		$file = \Doctrine_Core::getTable('File')->getById($id);
		if (!$file)
		{
			throw new NotFoundHttpException(sprintf('Cannot find file #%s.', $id));
		}
		if (!verifier('connecte') || $file['user_id'] != $_SESSION['id'])
		{
			throw new AccessDeniedHttpException(sprintf('Not allowed to access file #%s.', $id));
		}
		
		$vars = $this->getVariables($request);
		$vars['insertRawFile'] =
			$vars['input'] ? $file->getWebPath() : 
			'<lien url="'.$file->getWebPath().'">'.
				htmlspecialchars($file['name']).'.'.$file['extension'].
			'</lien>';
		if ($file->isImage() && !$vars['input'])
		{
			$vars['insertFullFile']  =
				'<lien url="'.$file->getWebPath().'">'.
					'<image>'.$file['id'].':'.$file->getFullname().'</image>'.
				'</lien>';
			$vars['insertThumbnail'] =
				'<lien url="'.$file->getWebPath().'">'.
					'<image largeur="'.$file->Thumbnail['width'].'">'.
						$file['id'].':'.$file->getFullname().
					'</image>'.
				'</lien>';
		}
		
		\Page::$titre = sprintf('Propriétés du fichier "%s"', $file['name']);
		$timestamp = time();
		$apiKey = $this->container->getParameter('aviary_api_key');
		
		return render_to_response(
			'ZcoFileBundle::file.html.php', array_merge(array(
				'currentPage'	=> 'file',
				'file'			=> $file,
				'licenses'		=> \Doctrine_Core::getTable('License')->findAll(),
				'apiKey'        => $apiKey,
				'signature'	   	=> md5($apiKey.$this->container->getParameter('aviary_api_secret').$timestamp),
				'timestamp'	   	=> $timestamp,
			), $vars)
		);
	}
	
	/**
	 * Renvoie les informations sur un dossier intelligent.
	 *
	 * @param  integer $id
	 * @return array
	 */
	private function getSmartFolder($id)
	{
		if (isset($this->smartFolders[$id]))
		{
			return $this->smartFolders[$id];
		}
		
		throw new NotFoundHttpException(sprintf('Cannot find smart folder #%s.', $id));
	}
	
	/**
	 * Renvoie les informations sur un dossier de contenu.
	 *
	 * @param  string $id
	 * @return array
	 */
	private function getContentFolder($id)
	{
		if (isset($this->contentFolders[$id]))
		{
			return $this->contentFolders[$id];
		}
		
		throw new NotFoundHttpException(sprintf('Cannot find content folder "%s".', $id));
	}
	
	/**
	 * Renvoie les variables par défaut nécessaires au layout.
	 *
	 * @param  Request $request
	 * @param  array $variables Variables facultatives à fusionner
	 * @return array
	 */
	private function getVariables(Request $request, array $variables = array())
	{
		return array_merge(array(
			'smartFolders'   => $this->smartFolders,
			'contentFolders' => $this->contentFolders,
			'currentFolder'  => array(),
			'currentContentFolder' => array(),
			'input'		     => $request->query->has('input') ? htmlspecialchars($request->query->get('input')) : null,
			'textarea'	     => $request->query->has('textarea') ? htmlspecialchars($request->query->get('textarea')) : null,
			'xhr'		     => $request->query->has('xhr') && $request->query->get('xhr'),
		), $variables);
	}
}
