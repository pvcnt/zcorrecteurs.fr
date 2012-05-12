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
 */
class ForumAlerteTable extends Doctrine_Table
{
	/**
	 * Liste toutes les alertes, par sujet et / ou par résolution.
	 * @param null|boolean $solved			Alerte résolue ?
	 * @param null|integer $sujet_id		ID de sujet.
	 * @return Doctrine_Collection
	 */
	public function ListerAlertes($solved = null, $sujet_id = null)
	{
		$query = Doctrine_Query::create()
			->select('a.id, a.resolu, a.date, a.raison, a.ip, u1.id, u2.pseudo, '.
				'u2.id, u2.pseudo, s.id, s.titre, s.ferme, s.corbeille, '.
				'g1.class, g2.class, s.forum_id')
			->from('ForumAlerte a')
			->leftJoin('a.Utilisateur u1')
			->leftJoin('a.Admin u2')
			->leftJoin('a.Sujet s')
			->leftJoin('u1.Groupe g1')
			->leftJoin('u2.Groupe g2')
			->orderBy('a.resolu, a.date DESC');
		if (!is_null($solved))
		{
			$query->addWhere('a.resolu = ?', $solved);
		}
		if (!is_null($sujet_id))
		{
			$query->addWhere('a.sujet_id = ?', $sujet_id);
		}
		$alertes = $query->execute();

		//Tri selon les droits.
		if(is_null($sujet_id))
		{
			foreach($alertes as &$alerte)
			{
				if(!verifier('voir_alertes', $alerte->Sujet['forum_id']))
					unset($alerte);
			}
		}

		return $alertes;
	}

	/**
	 * Vérifie si un membre a le droit d'alerter les modérateurs
	 * sur un sujet.
	 * @param integer $sujet_id			ID du sujet.
	 * @return boolean
	 */
	public function VerifierAutorisationAlerter($sujet_id)
	{
		return !(boolean)(Doctrine_Query::create()
			->select('COUNT(*)')
			->from('ForumAlerte')
			->where('sujet_id = ?', $sujet_id)
			->andWhere('resolu = ?', false)
			->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR));
	}
}
