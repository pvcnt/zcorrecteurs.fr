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

namespace Zco\Bundle\StatistiquesBundle\EventListener;

use Zco\Bundle\AdminBundle\AdminEvents;
use Zco\Bundle\CoreBundle\Menu\Event\FilterMenuEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventListener implements EventSubscriberInterface
{
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			AdminEvents::MENU => 'onFilterAdmin',
		);
	}
	
	public function onFilterAdmin(FilterMenuEvent $event)
	{
	    $tab = $event
	        ->getRoot()
	        ->getChild('Informations')
	        ->getChild('Statistiques générales');
	        
		$tab->addChild('Statistiques générales (GA)', array(
			'credentials' => 'voir_stats_generales', 
			'uri' => 'https://www.google.com/analytics/reporting/dashboard?id=6978501&scid=1725896',
		));
		
		$tab->addChild('Statistiques Alexa (classement du site)', array(
			'credentials' => 'voir_stats_generales', 
			'uri' => '/statistiques/alexa.html',
		));
	
		$tab->addChild('Statistiques d\'inscription', array(
			'credentials' => 'stats_inscription', 
			'uri' => '/statistiques/inscription.html',
		));
	
		$tab->addChild('Statistiques de géolocalisation', array(
			'credentials' => 'stats_geolocalisation', 
			'uri' => '/statistiques/geolocalisation.html',
		));
	
		$tab->addChild('Âge des membres', array(
			'credentials' => 'voir_stats_ages', 
			'uri' => '/statistiques/graphique-ages.html',
		));
	
		$tab->addChild('Statistiques de consultation du flux du blog', array(
			'credentials' => 'stats_blog_flux', 
			'uri' => '/statistiques/blog-flux.html',
		));
		
		$tab = $event
	        ->getRoot()
	        ->getChild('Informations')
	        ->getChild('Statistiques d\'activité');
        
		$tab->addChild('Statistiques de zCorrection', array(
			'credentials' => 'stats_prive', 
			'uri' => '/statistiques/zcorrection.html',
		));
	
		$tab->addChild('Mes statistiques de zCorrection', array(
			'credentials' => 'stats_zcorrecteur', 
			'uri' => '/statistiques/zcorrecteur.html',
		));
	
		$tab->addChild('Rapport d\'activité des zCorrecteurs', array(
			'credentials' => 'voir_rapport_zcorr', 
			'uri' => '/statistiques/activite.html',
		));
	
		$tab->addChild('Rapport d\'activité des développeurs', array(
			'credentials' => 'stats_developpement', 
			'uri' => '/statistiques/developpement.html',
		));
	}
}