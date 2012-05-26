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
 * Contrôleur chargé du changement du statut résolu d'un sujet.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class ChangerResoluAction extends ForumActions
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

		if(
			(
				$_SESSION['id'] == $InfosSujet['sujet_auteur']
				AND
				verifier('resolu_ses_sujets', $InfosSujet['sujet_forum_id'])
			)
			OR
			verifier('resolu_sujets', $InfosSujet['sujet_forum_id'])
		)
		{
			ChangerResoluSujet($_GET['id'], $InfosSujet['sujet_resolu']);
			return redirect(($InfosSujet['sujet_resolu'] ? 54 : 53), 'sujet-'.$_GET['id'].'-'.rewrite($InfosSujet['sujet_titre']).'.html');
		}
		else
			return redirect(70, 'sujet-'.$_GET['id'].'-'.rewrite($InfosSujet['sujet_titre']).'.html');
	}
}
