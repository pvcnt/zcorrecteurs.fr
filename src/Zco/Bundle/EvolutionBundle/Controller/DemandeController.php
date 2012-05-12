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

namespace Zco\Bundle\EvolutionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Contrôleur se chargeant de l'affichage d'un ticket et de ses réponses liées.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class DemandeController extends Controller
{
	public function defaultAction()
	{
		if(!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$InfosTicket = InfosTicket($_GET['id']);
			if(empty($InfosTicket))
				return redirect(357, 'index.html', MSG_ERROR);

			\Page::$titre = htmlspecialchars($InfosTicket['ticket_titre']);
		}
		else
		{
			return redirect(364, 'index.html', MSG_ERROR);
		}

		\zCorrecteurs::VerifierFormatageUrl($InfosTicket['ticket_titre'], true, true);

		//Si on veut changer le suivi du sujet
		if(isset($_GET['suivi']) && is_numeric($_GET['suivi']) && verifier('connecte'))
		{
			ChangerSuiviTicket($_SESSION['id'], $_GET['id'], $_GET['suivi'] != 0 ? 1 : 0);
			
			return redirect($_GET['suivi'] != 0 ? 388 : 389, 'demande-'.$_GET['id'].'-'.rewrite($InfosTicket['ticket_titre']).'.html');
		}

		MarquerTicketCommeLu($_GET['id'], $InfosTicket['ticket_id_version_courante']);

		//Inclusion de la vue
		fil_ariane(array(
			htmlspecialchars($InfosTicket['ticket_titre']) => 'demande-'.$_GET['id'].'.html',
			'Voir l\'anomalie'
		));
		$this->get('zco_vitesse.resource_manager')->requireResources(array(
		    '@ZcoCoreBundle/Resources/public/css/zcode.css',
		));
		
		return render_to_response(array(
			'InfosTicket' => $InfosTicket,
			'ListerSuivisTicket' => ListerSuivisTicket($_GET['id']),
			'ListerReponses' => ListerReponses($_GET['id']),
		));
	}
}
