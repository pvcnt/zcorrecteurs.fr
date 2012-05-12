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
 * Contrôleur gérant la suppression d'une candidature.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class SupprimerCandidatureAction extends Controller
{
	public function execute()
	{
		//Si on a bien envoyé une candidature
		if(!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$InfosCandidature = InfosCandidature($_GET['id']);
			if(empty($InfosCandidature))
				return redirect(227, 'gestion.html', MSG_ERROR);
			zCorrecteurs::VerifierFormatageUrl($InfosCandidature['candidature_pseudo'], true);

			//Si on veut supprimer
			if(isset($_POST['confirmer']))
			{
				SupprimerCandidature($_GET['id']);
				return redirect(169, 'gestion.html');
			}
			//Si on annule
			elseif(isset($_POST['annuler']))
			{
				return new Symfony\Component\HttpFoundation\RedirectResponse('candidature-'.$_GET['id'].'.html');
			}

			//Inclusion de la vue
			fil_ariane(array(
				htmlspecialchars($InfosCandidature['recrutement_nom']) => 'recrutement-'.$InfosCandidature['recrutement_id'].'.html',
				'Candidature de '.htmlspecialchars($InfosCandidature['utilisateur_pseudo']) => 'candidature-'.$_GET['id'].'.html',
				'Supprimer la candidature'
			));
			$this->get('zco_vitesse.resource_manager')->requireResource('@ZcoCoreBundle/Resources/public/css/zcode.css');
			
			return render_to_response(array('InfosCandidature' => $InfosCandidature));
		}
		else
			return redirect(226, 'gestion.html', MSG_ERROR);
	}
}
