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

namespace Zco\Bundle\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PasswordValidator extends AbstractValidator
{
	public function isValid($value, Constraint $constraint)
	{
		//Hack pour parer au cas où deux mots de passe différents ont été saisis.
		//Le mot de passe est alors nul et on n'a pas à continuer la validation, 
		//une erreur sera déjà retournée.
		if (null === $value)
		{
			return true;
		}
		
		return $this->validate($value, 'validatePassword', $constraint);
	}
}