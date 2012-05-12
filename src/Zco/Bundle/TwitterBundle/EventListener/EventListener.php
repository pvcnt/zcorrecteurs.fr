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

namespace Zco\Bundle\TwitterBundle\EventListener;

use Zco\Bundle\AdminBundle\AdminEvents;
use Zco\Bundle\CoreBundle\Menu\Event\FilterMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Observateur pour les éléments de l'interface.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class EventListener extends ContainerAware implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'zco_core.filter_menu.footer2' => 'onFilterFooter2',
			AdminEvents::MENU => 'onFilterAdmin',
		);
	}
	
	/**
	 * Ajoute un lien vers Twitter dans le pied de page.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterFooter2(FilterMenuEvent $event)
	{
		$event->getRoot()->addChild('Twitter', array(
			'uri'    => $this->container->get('router')->generate('zco_twitter_index'),
			'weight' => 50,
			'linkAttributes' => array(
				'title' => 'Derniers tweets',
			),
		));
	}
	
	/**
	 * Ajoute les liens vers les pages d'administration.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterAdmin(FilterMenuEvent $event)
	{
		$tab = $event
		    ->getRoot()
		    ->getChild('Contenu')
		    ->getChild('Twitter');
		    
			$NombreMentions = $this->container->get('zco_admin.manager')->get('mentions');
	
		$tab->addChild('Voir les mentions non lues', array(
			'label' => 'Il y a '.$NombreMentions.' nouvelle'.pluriel($NombreMentions).' mention'.pluriel($NombreMentions),
			'credentials' => 'twitter_tweeter',
			'uri' => $this->container->get('router')->generate('zco_twitter_mentions'), 
			'count' => $NombreMentions
		));
	
		$tab->addChild('Nouveau tweet', array(
			'credentials' => 'twitter_tweeter', 
			'uri' => $this->container->get('router')->generate('zco_twitter_newTweet'),
		));
	
		$tab->addChild('Gérer les comptes', array(
			'credentials' => 'twitter_comptes', 
			'uri' => $this->container->get('router')->generate('zco_twitter_accounts'),
		));
	}
}