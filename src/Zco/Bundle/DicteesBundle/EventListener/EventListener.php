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

namespace Zco\Bundle\DicteesBundle\EventListener;

use Zco\Bundle\InformationsBundle\Event\FilterSitemapEvent;
use Zco\Bundle\InformationsBundle\InformationsEvents;
use Zco\Bundle\AdminBundle\AdminEvents;
use Zco\Bundle\CoreBundle\Menu\Event\FilterMenuEvent;
use Zco\Component\Templating\TemplatingEvents;
use Zco\Component\Templating\Event\FilterVariablesEvent;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class EventListener extends ContainerAware implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'zco_core.filter_menu.speedbarre' => 'onFilterSpeedbarre',
			'zco_core.filter_menu.speedbarre_right' => 'onFilterSpeedbarreRight',
			'zco_core.filter_menu.left_menu' => 'onFilterLeftMenu',
			TemplatingEvents::FILTER_VARIABLES => 'onTemplatingFilterVariables',
			AdminEvents::MENU => 'onFilterAdmin',
			InformationsEvents::SITEMAP => 'onFilterSitemap',
		);
	}
		
	public function onTemplatingFilterVariables(FilterVariablesEvent $event)
	{
		if (
			!$this->container->get('request')->attributes->has('_module') ||
			$this->container->get('request')->attributes->get('_module') !== 'dictees'
		)
		{
			return;
		}
		
		$config = \Config::Get('messages');
		
		$event->add('DicteeDifficultes', $config['DicteeDifficultes']);
		$event->add('DicteeEtats', $config['DicteeEtats']);
		$event->add('DicteeCouleurs', $config['DicteeCouleurs']);
	}
	
	public function onFilterAdmin(FilterMenuEvent $event)
	{
		$tab = $event
			->getRoot()
			->getChild('Contenu')
			->getChild('Dictées');
		
		$NombreDicteesProposees = $this->container->get('zco_admin.manager')->get('dictees');
		
		$tab->addChild('Voir les dictées proposées', array(
			'label' => 'Il y a '.$NombreDicteesProposees.' dictée'.pluriel($NombreDicteesProposees).' proposée'.pluriel($NombreDicteesProposees),
			'uri' => '/dictees/propositions.html', 
			'count' => $NombreDicteesProposees,
		))->secure('dictees_publier');
		
		$tab->addChild('Ajouter une dictée', array(
			'uri' => '/dictees/ajouter.html',
		))->secure('dictees_ajouter');
	}
	
	public function onFilterLeftMenu(FilterMenuEvent $event)
	{
		if (!verifier('dictees_proposer') || $event->getTemplate() !== 'legacy')
		{
			return;
		}
		
		$event->getRoot()->getChild('Mon compte')->addChild('Mes dictées', array(
			'uri'	=> '/dictees/proposer.html',
			'weight' => 30,
			'linkAttributes' => array(
				'rel'   => 'Proposez votre dictée.', 
				'title' => 'Mes dictées',
			),
		));
	}
	
	public function onFilterSpeedbarre(FilterMenuEvent $event)
	{
		$event
			->getRoot()
			->addChild('Dictées', array('uri'   => '/dictees/', 'weight' => 40))
			->setCurrent(
				$event->getRequest()->attributes->has('_module') && 
				$event->getRequest()->attributes->get('_module') === 'dictees'
			);
	}
	
	public function onFilterSpeedbarreRight(FilterMenuEvent $event)
	{
		if ($event->getTemplate() === 'bootstrap' && verifier('connecte'))
		{
			$event->getRoot()->getChild('Mon compte')->addChild('Mes dictées', array(
				'uri'	=> '/dictees/proposer.html',
				'weight' => 40,
			));
		}
	}
	
	/**
     * Met à jour le sitemap.
     *
     * @param FilterSitemapEvent $event
     */
	public function onFilterSitemap(FilterSitemapEvent $event)
	{
		$event->addLink(URL_SITE.'/dictees/', array(
			'changefreq' => 'weekly',
			'priority'	 => '0.6',
		));
		foreach (\Doctrine_Core::getTable('Dictee')->getAllId() as $dictee)
		{
			$event->addLink(URL_SITE.'/dictees/dictee-'.$dictee['id'].'-'.rewrite($dictee['titre']).'.html', array(
				'changefreq' => 'monthly',
				'priority'	 => '0.5',
			));
		}
	}
}