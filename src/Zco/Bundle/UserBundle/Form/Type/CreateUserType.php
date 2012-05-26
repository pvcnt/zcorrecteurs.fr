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
namespace Zco\Bundle\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Formulaire de création d'un nouveau compte utilisateur.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class CreateUserType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('username', 'text', array(
			'label' => 'Pseudonyme',
		));
		$builder->add('email', null, array(
			'label' => 'Adresse courriel', 
			'required' => true,
		));
		$builder->add('rawPassword', 'repeated', array(
			'type'  => 'password',
			'label' => 'Mot de passe', 
			'first_name' => 'Mot de passe',
			'second_name' => 'Confirmez le mot de passe',
			'invalid_message' => 'Saisissez deux fois le même mot de passe.',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'zco_user_createUser';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class'        => 'Utilisateur',
			'validation_groups' => array('registration')
		);
	}
}