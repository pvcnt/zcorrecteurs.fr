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

use Symfony\Component\HttpFoundation\Response;

/**
 * Contrôleur pour le graphique des statistiques de géolocalisation.
 *
 * @author Ziame <ziame@zcorrecteurs.fr>
 */
class GraphiqueGeolocalisationAction
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl();
		include_once(BASEPATH.'/vendor/Artichow/Pie.class.php');
		include_once(BASEPATH.'/vendor/Artichow/Graph.class.php');

		if (empty($_SESSION['stats_geoloc']))
		{
			return new Response('Vous n\'avez pas l\'autorisation de voir le rapport de géolocalisation.');
		}

		$donnees = $_SESSION['stats_geoloc'];
		$graph = new Graph(500, 400);

		//Ajout d'une ombre portée
		$graph->shadow->setPosition(Shadow::RIGHT_BOTTOM);
		$graph->shadow->setSize(4);

		//Paramétrage du fond
		$graph->setBackgroundGradient(new LinearGradient(
			new Color(240, 240, 240, 0),
			new White, 0));

		//Ajout des valeurs et de leurs labels.
		$pie = new Pie(array_values($donnees));
		$pie->setLegend(array_keys($donnees));

		//Positionnements
		$pie->legend->setPosition(1.45, 0.5);
		$pie->legend->setTextFont(new Tuffy(10));
		$pie->setCenter(0.35, 0.5);
		$pie->setSize(0.65, 0.65);

		//Affiche les pourcentages avec une précision d'un dixième.
		$pie->setLabelPrecision(1);

		//Affectation des données au graphe et rendu.
		$pie->set3D(5);
		$graph->add($pie);
		$r = new Response($graph->draw(Graph::DRAW_RETURN));
		$r->headers->set('Content-type', 'image/png');

		unset($_SESSION['stats_geoloc']);
		
		return $r;
	}
}
