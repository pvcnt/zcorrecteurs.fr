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
 * Contrôleur gérant l'affichage de Mes billets.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class MesBilletsAction extends BlogActions
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl(null, true);
		Page::$titre = 'Mes billets';

		$params = array('id_utilisateur' => $_SESSION['id']);
		if(!empty($_GET['id'])) $params['etat'] = $_GET['id'];
		list($ListerBillets, $BilletsAuteurs) = ListerBillets($params);

		//Inclusion de la vue
		fil_ariane('Mes billets');
		
		return render_to_response(array(
			'ListerBillets' => $ListerBillets,
			'BilletsAuteurs' => $BilletsAuteurs,
		));
	}
}
