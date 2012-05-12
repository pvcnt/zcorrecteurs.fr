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
 * Contrôleur gérant la création d'un nouveau sondage.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class AjouterAction
{
	public function execute()
	{
		//Ajout d'un sondage demandé.
		if (!empty($_POST['nom']))
		{
			$sondage = new Sondage;
			$sondage['utilisateur_id'] = $_SESSION['id'];
			$sondage['nom']            = $_POST['nom'];
			$sondage['description']    = $_POST['texte'];
			$sondage['date_debut']     = $_POST['date_debut'];
			$sondage['date_fin']       = !empty($_POST['date_fin']) ? $_POST['date_fin'] : null;
			$sondage['ouvert']         = isset($_POST['ouvert']);
			$sondage->save();

			return redirect(1, 'modifier-'.$sondage['id'].'.html');
		}

		Page::$titre = 'Ajouter un sondage';
		
		return render_to_response(array());
	}
}