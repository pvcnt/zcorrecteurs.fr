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

namespace Zco\Bundle\AboutBundle\EventListener;

use Zco\Bundle\InformationsBundle\Event\FilterSitemapEvent;
use Zco\Bundle\InformationsBundle\InformationsEvents;
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
			'zco_core.filter_menu.footer1'   => 'onFilterFooter1',
			'zco_core.filter_menu.left_menu' => 'onFilterLeftMenu',
			InformationsEvents::SITEMAP 	 => 'onFilterSitemap',
		);
	}
	
	/**
	 * Ajoute les liens dans le pied de page.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterFooter1(FilterMenuEvent $event)
	{
		$event
			->getRoot()
			->addChild('À propos', array(
				'uri'    => $this->container->get('router')->generate('zco_about_index'),
				'weight' => 10,
				'linkAttributes' => array(
					'title' => 'Pour en savoir plus sur le site et son organisation.'
				),
			));
		
		$event
			->getRoot()
			->addChild('Contact', array(
				'uri'    => $this->container->get('router')->generate('zco_about_contact'),
				'weight' => 20,
				'linkAttributes' => array(
					'title' => 'Si vous avez besoin de contacter les administrateurs de ce site.'
				),
			));
		
		$event
			->getRoot()
			->addChild('Code source', array(
				'uri'    => $this->container->get('router')->generate('zco_about_opensource'),
				'weight' => 25,
			));
	}
	
	/**
	 * Ajoute dans le menu latéral le lien vers la page de l'équipe.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterLeftMenu(FilterMenuEvent $event)
	{
		$event->getRoot()->getChild('Communauté')->addChild('L\'équipe', array(
			'uri'	=> $this->container->get('router')->generate('zco_about_team'),
			'weight' => 30,
			'linkAttributes' => array(
				'title'   => 'Une page spéciale pour présenter ceux qui dépensent tant d\'énergie pour corriger vos documents et faire vivre le site.',
			),
		));
	}
	
	/**
     * Met à jour le sitemap.
     *
     * @param FilterSitemapEvent $event
     */
	public function onFilterSitemap(FilterSitemapEvent $event)
	{
		$router = $this->container->get('router');
		$event->addLink($router->generate('zco_about_index', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
		$event->addLink($router->generate('zco_about_contact', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
		$event->addLink($router->generate('zco_about_team', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
		$event->addLink($router->generate('zco_about_corrigraphie', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
		$event->addLink($router->generate('zco_about_banners', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
		$event->addLink($router->generate('zco_about_opensource', array(), true), array(
			'changefreq' => 'monthly',
			'priority'	 => '0.3',
		));
	}
}