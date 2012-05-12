<?php

/**
 * Copyright 2012 Corrigraphie
 * 
 * This file is part of zCorrecteurs.fr.
 *
 * zCorrecteurs.fr is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * zCorrecteurs.fr is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with zCorrecteurs.fr. If not, see <http://www.gnu.org/licenses/>.
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Contrôleur gérant l'affichage de toutes les alertes.
 *
 * @author DJ Fox <djfox@zcorrecteurs.fr>
 */
class AlertesAction extends Controller
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl(null, true);
		//Inclusion des modèles
		include(dirname(__FILE__).'/../modeles/alertes.php');
		include(dirname(__FILE__).'/../modeles/lire.php');

		//On compte le nombre d'alertes à afficher.
		if(isset($_GET['solved']) AND $_GET['solved'])
		{
			$statut = 1;
			$ajout_url = '?solved=1';
		}
		elseif(isset($_GET['solved']) AND !$_GET['solved'])
		{
			$statut = 0;
			$ajout_url = '?solved=0';
		}
		elseif(!isset($_GET['solved']))
		{
			$statut = -1;
			$ajout_url = '';
		}
		$CompterAlertes = CompterAlertes($statut);

		if(!empty($_GET['id']) AND is_numeric($_GET['id']))
		{
			$page = TrouverLaPageDeCetteAlerte($_GET['id'], $CompterAlertes);
			return new Symfony\Component\HttpFoundation\RedirectResponse('alertes-p'.$page.'.html#a'.$_GET['id']);
		}

		//Si on veut marquer en résolu une alerte
		if(!empty($_GET['resolu']) && is_numeric($_GET['resolu']))
		{
			AlerteResolue($_GET['resolu']);
			return redirect(42, 'alertes.html');
		}

		Page::$titre .= ' - Voir les alertes';

		//Système de pagination
		$nbAlertesParPage = 20;
		$NombreDePages = ceil($CompterAlertes / $nbAlertesParPage); //On en déduit le nombre de pages à créer
		$page = !empty($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : $NombreDePages;
		$debut = ($NombreDePages - $page) * $nbAlertesParPage; //On détermine la première alerte à lister
		$ListerAlertes = ListerAlertes($debut, $nbAlertesParPage); //On liste les alertes

		$ListePages = liste_pages($page, $NombreDePages, $CompterAlertes, $nbAlertesParPage, 'alertes-p%s.html'.$ajout_url);

		//Inclusion de la vue
		fil_ariane('Voir les alertes sur les messages privés');
		
		return render_to_response(array(
			'CompterAlertes' => $CompterAlertes,
			'ListerAlertes' => $ListerAlertes,
			'ListePages' => $ListePages,
		));
	}
}
