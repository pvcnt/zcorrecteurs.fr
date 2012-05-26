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

/**
 * Contrôleur gérant l'affichage des billets d'une catégorie.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class CategorieAction extends BlogActions
{
	public function execute()
	{
		if(!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$InfosCategorie = InfosCategorie($_GET['id']);
			if(empty($InfosCategorie))
				return redirect(217, '/blog/', MSG_ERROR);

			if(!verifier('blog_voir', $_GET['id']))
				throw new Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

			zCorrecteurs::VerifierFormatageUrl($InfosCategorie['cat_nom'], true, false, 1);
			$NombreDeBillet = CompterListerBilletsEnLigne($_GET['id']);
			$nbBilletsParPage = 15;
			$NombreDePage = ceil($NombreDeBillet / $nbBilletsParPage);
			$page = (!empty($_GET['p']) && is_numeric($_GET['p'])) ? $_GET['p'] : 1;
			list($ListerBillets, $BilletsAuteurs) = ListerBillets(array(
				'id_categorie' => $_GET['id'],
				'lecteurs' => false,
				'etat' => BLOG_VALIDE,
				'futur' => false,
			), $page);
			$ListePage = liste_pages($page, $NombreDePage, $NombreDeBillet, $nbBilletsParPage, '/blog/categorie-'.$_GET['id'].'-p%s-'.rewrite($InfosCategorie['cat_nom']).'.html');
			$ListerParents = ListerParents($InfosCategorie);
			$Categories = ListerEnfants($ListerParents[1]);

			//Inclusion de la vue
			fil_ariane($_GET['id'], 'Liste des billets de la catégorie');
			Page::$robots = 'noindex,follow';
			$this->get('zco_vitesse.resource_manager')->addFeed(
			    '/blog/flux-'.$_GET['id'].'-'.rewrite($InfosCategorie['cat_nom']).'.html', 
			    array('title' => 'Derniers billets de cette catégorie')
			);
			
			return render_to_response(array(
				'ListerBillets' => $ListerBillets,
				'BilletsAuteurs' => $BilletsAuteurs,
				'ListePage' => $ListePage,
				'ListerParents' => $ListerParents,
				'Categories' => $Categories,
				'InfosCategorie' => $InfosCategorie,
				'NombreDeBillet' => $NombreDeBillet,
			));
		}
		else
		{
			return redirect(216, '/blog/', MSG_ERROR);
		}
	}
}
