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

namespace Zco\Bundle\CoreBundle;

/**
 * Événements générés par le ZcoCoreBundle.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
final class CoreEvents
{
	/**
	 * Événement déclenché chaque heure par l'exécution du cron horaire.
	 *
	 * L'événement associé est de type Zco\Bundle\CoreBundle\Event\CronEvent.
	 */
	const HOURLY_CRON = 'zco_core.hourly_cron';
	
	/**
	 * Événement déclenché chaque jour par l'exécution du cron quotidien.
	 *
	 * L'événement associé est de type Zco\Bundle\CoreBundle\Event\CronEvent.
	 */
	const DAILY_CRON = 'zco_core.daily_cron';
}