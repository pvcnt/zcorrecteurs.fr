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
 * Contrôleur chargé du changement du statut fermé d'un sujet.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class ChangerStatutAction extends ForumActions
{
	public function execute()
	{
		//On récupère les infos sur le sujet.
		list($InfosSujet, $InfosForum) = $this->initSujet();
		if ($InfosSujet instanceof Response)
			return $InfosSujet;
		include(dirname(__FILE__).'/../modeles/moderation.php');

		zCorrecteurs::VerifierFormatageUrl($InfosSujet['sujet_titre'], true);

		//Vérification du token.
		if(empty($_GET['token']) || $_GET['token'] != $_SESSION['token'])
			throw new Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

		if(verifier('fermer_sujets', $InfosSujet['sujet_forum_id']))
		{
			ChangerStatutSujet($_GET['id'], $InfosSujet['sujet_ferme']);
			return redirect(($InfosSujet['sujet_ferme'] ? 57 : 56), 'sujet-'.$_GET['id'].'-'.rewrite($InfosSujet['sujet_titre']).'.html');
		}
		else
			return redirect(70, 'sujet-'.$_GET['id'].'-'.rewrite($InfosSujet['sujet_titre']).'.html', MSG_ERROR);
	}
}
