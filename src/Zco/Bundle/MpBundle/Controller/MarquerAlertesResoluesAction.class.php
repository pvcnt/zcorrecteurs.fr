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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Contrôleur gérant le marquage en résolues de toutes les alertes.
 *
 * @author DJ Fox <djfox@zcorrecteurs.fr>
 */
class MarquerAlertesResoluesAction extends Controller
{
	public function execute()
	{
		include(BASEPATH.'/src/Zco/Bundle/MpBundle/modeles/alertes.php');
		ResoudreAlertes();
		$this->get('zco_core.cache')->delete('taches_admin_alertes_mp');
		
		return redirect(289, '/admin/index.html');
	}
}
