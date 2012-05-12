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

namespace Zco\Bundle\UserBundle\Event;

class FilterRegisterEvent extends RegisterEvent
{
	private $aborted = false;
	private $errorMessage;
	
	public function setUser(\Utilisateur $user)
	{
		$this->user = $user;
	}
	
	public function abort($message = '')
	{
		$this->aborted      = true;
		$this->errorMessage = $message;
		$this->stopPropagation();
	}
	
	public function isAborted()
	{
		return $this->aborted;
	}
	
	/**
	 * Retourne le message d'erreur associé à un fichier non acceptable.
	 *
	 * @throws \LogicException Si le fichier est acceptable
	 * @return string Le message d'erreur, éventuellement vide
	 */
	public function getErrorMessage()
	{
		if (!$this->isAborted())
		{
			throw new \LogicException('Cannot get the error message when login is not aborted.');
		}
		
		return $this->errorMessage;
	}
}