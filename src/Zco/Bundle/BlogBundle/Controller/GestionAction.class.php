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

/**
 * Contrôleur gérant l'affichage des billets en ligne (côté admin).
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class GestionAction extends BlogActions
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl(null, false, false, 1);
		Page::$titre .= ' - Liste des billets en ligne';

		$nbBilletsParPage = 30;
		$page = !empty($_GET['p']) && is_numeric($_GET['p']) ? $_GET['p'] : 1;
		$CompterBillets = CompterListerBilletsEnLigne();
		list($ListerBillets, $Auteurs) = ListerBillets(array(
			'etat' => BLOG_VALIDE,
			'lecteurs' => false
		), $page);
		$ListePages = liste_pages($page, ceil($CompterBillets / $nbBilletsParPage), $CompterBillets, $nbBilletsParPage, 'gestion-p%s.html');
		$colspan = 4;
		if(verifier('blog_supprimer')) $colspan++;
		if(verifier('blog_devalider')) $colspan++;
		if(verifier('blog_editer_valide')) $colspan++;

		//Inclusion de la vue
		fil_ariane('Liste des billets en ligne');
		
		return render_to_response(array(
			'ListerBillets' => $ListerBillets,
			'Auteurs' => $Auteurs,
			'CompterBillets' => $CompterBillets,
			'ListePages' => $ListePages,
			'colspan' => $colspan,
		));
	}
}
