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

/**
 * UserPreference
 * 
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class UserPreference extends BaseUserPreference
{
	protected static $preferences = array(
		'display_admin_bar',
		'beta_tests',
		'time_difference',
		'email_on_mp',
	);

	public function setUserId($userId)
	{
		$this->user_id = $userId;
	}

	public function getUserId()
	{
		return $this->user_id;
	}

	public function setDisplayAdminBar($display)
	{
		$this->display_admin_bar = (boolean) $display;
	}

	public function getDisplayAdminBar()
	{
		return $this->display_admin_bar;
	}

	public function setBetaTests($beta)
	{
		$this->beta_tests = (boolean) $beta;
	}

	public function getBetaTests()
	{
		return $this->beta_tests;
	}

	public function setEmailOnMp($email)
	{
		$this->email_on_mp = (boolean) $email;
	}

	public function getEmailOnMp()
	{
		return $this->email_on_mp;
	}

	public function setTimeDifference($timeDifference)
	{
		$this->time_difference = $timeDifference;
	}

	public function getTimeDifference()
	{
		return $this->time_difference;
	}

	/**
	 * Stocke les préférences en session.
	 */
	public function apply()
	{
		foreach (self::$preferences as $pref)
	    {
		    $_SESSION['prefs'][$pref] = $this->$pref;
	    }
	}
}