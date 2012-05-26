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

namespace Zco\Bundle\ParserBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Événement permettant de manipuler un contenu sous forme de chaîne 
 * de caractères.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class FilterContentEvent extends ParserEvent
{
	private $content;
	
	/**
	 * Constructeur.
	 *
	 * @param string $content Le contenu à filtrer
	 * @param array $options Une liste d'options
	 */
	public function __construct($content, array $options = array())
	{
		parent::__construct($options);
		$this->content = $content;
	}
	
	/**
	 * Retourne le contenu courant.
 	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * Remplace le contenu.
	 *
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}
}