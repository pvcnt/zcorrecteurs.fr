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
 * Contrôleur pour les statistiques d'utilisation de géolocalisation.
 *
 * @author Ziame <ziame@zcorrecteurs.fr>
 */
class GeolocalisationAction
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl();
		Page::$titre = 'Statistiques de géolocalisation';

		//Inclusion du modèle et récupération des données
		include(dirname(__FILE__).'/../modeles/geolocalisation.php');
		list($StatsGraph, $Stats, $NbUtilisateurs) = RecupStatistiquesGeolocalisation();
		$_SESSION['stats_geoloc'] = $StatsGraph;

		//Inclusion de la vue
		fil_ariane('Statistiques de géolocalisation');
		
		return render_to_response(array(
			'Stats' => $Stats,
			'NbUtilisateurs' => $NbUtilisateurs
		));
	}
}
