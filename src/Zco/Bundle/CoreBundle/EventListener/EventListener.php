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

namespace Zco\Bundle\CoreBundle\EventListener;

use Zco\Bundle\CoreBundle\Menu\Event\FilterMenuEvent;
use Zco\Component\Templating\TemplatingEvents;
use Zco\Component\Templating\Event\FilterVariablesEvent;
use Zco\Component\Templating\Event\FilterResourcesEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Subscriber principal du module central du site.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class EventListener extends ContainerAware implements EventSubscriberInterface
{
	private $maintenance = false;
	
	/**
	 * {@inheritdoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'zco_core.filter_menu.left_menu' => array('onFilterLeftMenu', -1),
			'zco_core.filter_menu.footer2' => 'onFilterFooter2',
			TemplatingEvents::FILTER_RESOURCES => 'onTemplatingFilterResources',
			TemplatingEvents::FILTER_VARIABLES => 'onTemplatingFilterVariables',
			KernelEvents::REQUEST => 'onKernelRequest',
		);
	}
	
	/**
	 * Vérifie si le site est en mode maintenance et le cas échéant si 
	 * l'utilisateur a le droit d'y accéder ou pas. Si le site est bloqué, 
	 * renvoie directement la page de maintenance.
	 *
	 * Le mode maintenance s'active en créant le fichier app/config/maintenance.
	 * Les différentes adresses IP autorisées sont placées à l'intérieur.
	 *
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
		{
			return;
		}
		
		if (is_file(APP_PATH.'/config/maintenance'))
		{
			$this->maintenance = true;
			if (strpos(
				file_get_contents(APP_PATH.'/config/maintenance'), 
				$event->getRequest()->getClientIp(true)) === false)
			{
				$event->setResponse(new Response(render_to_string('ZcoCoreBundle::maintenance.html.php')));
			}
		}
	}
	
	/**
	 * Initialise des comportements de base communs à toutes les pages du site.
	 *
	 * @param FilterResourcesEvent $event
	 */
	public function onTemplatingFilterResources(FilterResourcesEvent $event)
	{
		//Transition adoucie lors du clic sur le lien pour remonter en haut.
		$event->initBehavior('morph-link', array('id' => 'toplink'));
		
		//Bulles sur les liens du menu latéral.
			$event->initBehavior('tips', array(
			'selector' => array('div.sidebarleft a', 'div.sidebar a'),
			'options' => array(
				'fixed' => true,
				'showDelay' => 1000,
				'offset' => array('x' => 160, 'y' => -10),
			),
		));
		
		//Désactivation des boutons d'envoi lors de la soumission.
		$event->initBehavior('disable-form-on-submit');
		
		//Exposition des routes pour y avoir accès depuis un code Javascript.
		$event->requireResource('@FOSJsRoutingBundle/Resources/public/js/router.js');
		
		//Statistiques Google Analytics.
		if ($this->container->getParameter('kernel.environment') === 'prod')
		{
			$event->initBehavior('google-analytics', array(
				'account' => $this->container->getParameter('analytics_account'),
				'domain' => $this->container->getParameter('analytics_domain'),
			));
		}
	}
	
	/**
	 * Ajoute dans la seconde ligne du pied de page un lien vers la page 
	 * Facebook du site.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterFooter2(FilterMenuEvent $event)
	{
		$event->getRoot()->addChild('Facebook', array(
			'uri'	=> 'http://www.facebook.com/pages/zCorrecteurs/292782574071649',
			'weight' => 60,
		));
	}
	
	/**
	 * Réordonne les blocs du menu de gauche.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterLeftMenu(FilterMenuEvent $event)
	{
		if ($event->getRoot()->hasChild('Mon compte'))
		{
			$event->getRoot()->getChild('Mon compte')->setWeight(10);
		}
		if ($event->getRoot()->hasChild('Communauté'))
		{
			$event->getRoot()->getChild('Communauté')->setWeight(20);
		}
		if ($event->getRoot()->hasChild('Partenaires'))
		{
			$event->getRoot()->getChild('Partenaires')->setWeight(30);
		}
	}
	
	/**
	 * Opère à quelques ultimes changements concernant les variables globales 
	 * avant le rendu de la vue.
	 *
	 * @param FilterVariablesEvent $event
	 */
	public function onTemplatingFilterVariables(FilterVariablesEvent $event)
	{
		//Génération d'un fil d'Ariane par défaut si aucun n'a été créé.
		if (empty(\Page::$fil_ariane) && !empty(\Page::$titre))
		{
			fil_ariane(\Page::$titre);
		}
		
		//Ajout de variables au layout.
		$event->add('maintenance', $this->maintenance);
	}
}