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
 * Requêtes sur la table des tentatives de connexion ratées.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class TentativeTable extends Doctrine_Table
{
	public function deleteByUserIdAndIp($userId, $ip)
	{
		$this->createQuery()
			->delete()
			->where('user = ?', $userId)
			->andWhere('ip = ?', $ip)
			->execute();
	}
	
	public function countByIp($ip)
	{
		$this->createQuery()
			->where('ip = ?', $ip)
			->count();
	}
	
	public function getByBlocked($hydrationMode = null)
	{
		return $this->getByBlockedQuery()
			->execute(array(), $hydrationMode);
	}
	
	public function getByBlockedQuery()
	{
		return $this->createQuery('t')
			->select('t.ip, t.date, u.id, u.pseudo, g.id, g.nom, g.class')
			->leftJoin('t.Utilisateur u')
			->leftJoin('u.Groupe g')
			->where('t.blocage = 1');
	}
}