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
function RecupVisitesFluxBlog($annee, $id_cat, $periode)
{
	$dbh = Doctrine_Manager::connection()->getDbh();

	$stmt = $dbh->prepare('SELECT COUNT(*) AS nb_vues, '
		.$periode.'(visite_date) AS periode, '
		.'visite_id_categorie '
		.'FROM zcov2_blog_flux_visites '
		.'WHERE YEAR(visite_date) = :annee AND visite_id_categorie = :id_cat '
		.'GROUP BY '.$periode.'(visite_date)');
	$stmt->bindParam(':annee', $annee);
	$stmt->bindParam(':id_cat', $id_cat);
	$stmt->execute();
	return $stmt->fetchAll();
}

function RecupNbVuesHier()
{
	$dbh = Doctrine_Manager::connection()->getDbh();

	$stmt = $dbh->prepare('SELECT COUNT(*) '
		.'FROM zcov2_blog_flux_visites '
		.'WHERE visite_date = DATE(NOW()) - INTERVAL 1 DAY');
	$stmt->execute();
	return $stmt->fetchColumn();
}
