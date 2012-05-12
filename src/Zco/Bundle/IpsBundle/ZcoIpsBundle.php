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

namespace Zco\Bundle\IpsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Fournit une boîte à outils permettant d'analyser, de rapprocher et de bannir 
 * les adresses IP utilisées par les utilisateurs.
 * 
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class ZcoIpsBundle extends Bundle
{
	/**
	 * Charge le modèle de base pour la gestion des adresses IP.
	 */
	public function boot()
	{
		include_once(__DIR__.'/modeles/ips.php');
	}
}