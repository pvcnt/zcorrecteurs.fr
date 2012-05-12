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
namespace Zco\Bundle\UserBundle\Form\Type;

use Zco\Bundle\UserBundle\Form\EventListener\AddUserFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Formulaire de dépôt d'une sanction sur un utilisateur.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class PunishmentType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('Group', null, array(
			'label' => 'Sanction',
			'query' => \Doctrine_Core::getTable('Groupe')->getByPunishmentQuery()
		));
		$builder->add('duration', 'integer', array(
			'label' => 'Durée', 
		));
		$builder->add('link', null, array(
			'label' => 'Lien du litige', 
		));
		$builder->add('reason', null, array(
			'label'  => 'Raison donnée au membre',
			'attr' => array('class' => 'textarea'),
		));
		$builder->add('admin_reason', 'zform', array(
			'label'  => 'Raison visible par les admins',
		));
		
		$subscriber = new AddUserFieldSubscriber($builder->getFormFactory());
		$builder->addEventSubscriber($subscriber);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'zco_user_punishment';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'UserPunishment',
		);
	}
}