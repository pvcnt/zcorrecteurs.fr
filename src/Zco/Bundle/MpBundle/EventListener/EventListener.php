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

namespace Zco\Bundle\MpBundle\EventListener;

use Zco\Bundle\AdminBundle\AdminEvents;
use Zco\Bundle\CoreBundle\Menu\Event\FilterMenuEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAware;

class EventListener extends ContainerAware implements EventSubscriberInterface
{
    static public function getSubscribedEvents()
    {
        return array(
            'zco_core.filter_menu.speedbarre' => 'onFilterSpeedbarre',
            KernelEvents::REQUEST => array('onKernelRequest', 100),
			AdminEvents::MENU => 'onFilterAdmin',
        );
    }
    
	/**
	 * Enregistre le compteur de tâches d'administration.
	 * Met à jour les compteurs de MP pour le membre connecté.
	 *
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		//Enregistrement du compteur de tâches admin.
		$this->container->get('zco_admin.manager')->register('alertesMP', 'mp_alertes');
		
		// Mise à jour du nombre de MPs non lus.
		$rafraichir = $this->container->get('zco_core.cache')->get('MPnonLu'.$_SESSION['id']);
		if ($rafraichir)
		{
			$this->container->get('zco_core.cache')->delete('MPnonLu'.$_SESSION['id']);
		}
		if (verifier('mp_voir') && ($rafraichir || !isset($_SESSION['MPsnonLus'])))
		{
			include_once(__DIR__.'/../modeles/mp_cache.php');
			$_SESSION['MPsnonLus'] = CompteMPnonLu();
		}

		// Mise à jour du nombre de MP total.
		if (verifier('mp_voir') && ($rafraichir || !isset($_SESSION['MPs'])))
		{
			include_once(__DIR__.'/../modeles/mp_cache.php');
			$_SESSION['MPs'] = CompteMPTotal();
		}
	}
	
	/**
	 * Ajoute le lien vers la messagerie privée dans la speedbarre.
	 *
	 * @param FilterMenuEvent $event
	 */
	public function onFilterSpeedbarre(FilterMenuEvent $event)
	{
		if (!verifier('mp_voir'))
		{
		    return;
	    }
	    
		$event->getRoot()->addChild('Messagerie', array(
			'uri'    => '/mp/',
			'weight' => 50,
			'label' => ($_SESSION['MPsnonLus'] > 0) ? 'Message'.pluriel($_SESSION['MPsnonLus']) : 'Messagerie',
			'count' => ($_SESSION['MPsnonLus'] > 0) ? $_SESSION['MPsnonLus'] : null,
			'linkAttributes' => array(
				'title' => ($_SESSION['MPsnonLus'] > 0) ? 'Vous avez des nouveaux messages !' : null,
			)
		))->setCurrent($event->getRequest()->attributes->get('_module') === 'mp');
	}
	
	public function onFilterAdmin(FilterMenuEvent $event)
	{
		$tab = $event
		    ->getRoot()
		    ->getChild('Communauté')
		    ->getChild('Messagerie privée');
		
		$NombreAlertesMP = $this->container->get('zco_admin.manager')->get('alertesMP');
		
		$tab->addChild('Voir les alertes non résolues', array(
			'label' => 'Il y a ' . $NombreAlertesMP . ' alerte' . pluriel($NombreAlertesMP) . ' non résolue' . pluriel($NombreAlertesMP),
			'uri' => '/mp/alertes.html' . ($NombreAlertesMP ? '?solved=0' : ''),
			'count' => $NombreAlertesMP,
		))->secure('mp_alertes');
	}
}