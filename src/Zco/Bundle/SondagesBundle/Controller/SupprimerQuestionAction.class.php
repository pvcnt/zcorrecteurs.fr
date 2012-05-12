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
 * Contrôleur gérant la suppression d'une question d'un sondage.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class SupprimerQuestionAction
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl(null, true);

		if (!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$question = Doctrine_Core::getTable('SondageQuestion')->find($_GET['id']);
			if ($question == false)
				return redirect(11, 'index.html', MSG_ERROR);

			if (verifier('sondages_editer') || ($question->Sondage['utilisateur_id'] == $_SESSION['id'] && verifier('sondages_editer_siens')))
			{
				//Suppression d'une question demandée.
				if (isset($_POST['confirmer']))
				{
					$question->Sondage['nb_questions'] = $question->Sondage['nb_questions'] - 1;
					$question->Sondage->save();

					$id = $question['sondage_id'];
					$question->delete();

					return redirect(10, 'modifier-'.$id.'.html');
				}
				elseif (isset($_POST['annuler']))
				{
					return new Symfony\Component\HttpFoundation\RedirectResponse('modifier-'.$question['sondage_id'].'.html');
				}

				fil_ariane(array(
					'Gestion des sondages' => 'gestion.html',
					htmlspecialchars($question->Sondage['nom']) => 'sondage-'.$question['sondage_id'].'-'.rewrite($question->Sondage['nom']).'.html',
					'Supprimer une question',
				));
				return render_to_response(array(
					'question'  => $question,
					'sondage'   => $question->Sondage,
				));
			}
			else
				throw new Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
		}
		else
			return redirect(7, 'index.html', MSG_ERROR);
	}
}