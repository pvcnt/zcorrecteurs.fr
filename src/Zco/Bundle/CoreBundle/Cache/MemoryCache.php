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

namespace Zco\Bundle\CoreBundle\Cache;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Implémentation d'un cache stockant toutes les données en mémoire. Est utile 
 * principalement pour les tests.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class MemoryCache extends AbstractCache
{
	/**
	 * {@inheritdoc}
	 */
	protected function read($id)
	{
		return $this->index[$id]['data'];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function write($id, $data, $lifetime)
	{
		$this->index[$id]['data'] = $data;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function remove($id)
	{
		unset($this->index[$id]);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function readIndex()
	{
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function writeIndex()
	{
	}
}
