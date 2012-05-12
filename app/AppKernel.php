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

use Zco\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	/**
	 * {@inheritdoc}
	 */
	public function registerBundles()
	{
		$bundles = array(
			//Bundles génériques.
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Zco\Bundle\Doctrine1Bundle\ZcoDoctrine1Bundle(),
			new Zco\Bundle\VitesseBundle\ZcoVitesseBundle(),
			new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
			new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
			new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
			new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
			
			//Bundles nécessaires pour que les modules fonctionnent.
			new Zco\Bundle\CoreBundle\ZcoCoreBundle(),
			new Zco\Bundle\ParserBundle\ZcoParserBundle(),
			new Zco\Bundle\UserBundle\ZcoUserBundle(),
			
			//Modules du site.
			new Zco\Bundle\AccueilBundle\ZcoAccueilBundle(),
			new Zco\Bundle\AdminBundle\ZcoAdminBundle(),
			new Zco\Bundle\AideBundle\ZcoAideBundle(),
			new Zco\Bundle\AnnoncesBundle\ZcoAnnoncesBundle(),
			new Zco\Bundle\AboutBundle\ZcoAboutBundle(),
			new Zco\Bundle\AuteursBundle\ZcoAuteursBundle(),
			new Zco\Bundle\BlogBundle\ZcoBlogBundle(),
			new Zco\Bundle\CaptchaBundle\ZcoCaptchaBundle(),
			new Zco\Bundle\CategoriesBundle\ZcoCategoriesBundle(),
			new Zco\Bundle\CitationsBundle\ZcoCitationsBundle(),
			new Zco\Bundle\DicteesBundle\ZcoDicteesBundle(),
			new Zco\Bundle\DonsBundle\ZcoDonsBundle(),
			new Zco\Bundle\EvolutionBundle\ZcoEvolutionBundle(),
			new Zco\Bundle\ForumBundle\ZcoForumBundle(),
			new Zco\Bundle\GroupesBundle\ZcoGroupesBundle(),
			new Zco\Bundle\InformationsBundle\ZcoInformationsBundle(),
			new Zco\Bundle\IpsBundle\ZcoIpsBundle(),
			new Zco\Bundle\LivredorBundle\ZcoLivredorBundle(),
			new Zco\Bundle\MpBundle\ZcoMpBundle(),
			new Zco\Bundle\OptionsBundle\ZcoOptionsBundle(),
			new Zco\Bundle\PubliciteBundle\ZcoPubliciteBundle(),
			new Zco\Bundle\QuizBundle\ZcoQuizBundle(),
			new Zco\Bundle\RechercheBundle\ZcoRechercheBundle(),
			new Zco\Bundle\RecrutementBundle\ZcoRecrutementBundle(),
			new Zco\Bundle\SondagesBundle\ZcoSondagesBundle(),
			new Zco\Bundle\StatistiquesBundle\ZcoStatistiquesBundle(),
			new Zco\Bundle\TagsBundle\ZcoTagsBundle(),
			new Zco\Bundle\TechniqueBundle\ZcoTechniqueBundle(),
			new Zco\Bundle\TwitterBundle\ZcoTwitterBundle(),
			new Zco\Bundle\ZcorrectionBundle\ZcoZcorrectionBundle(),
			new Zco\Bundle\FileBundle\ZcoFileBundle(),
		);
		
		if (in_array($this->getEnvironment(), array('dev', 'test')))
		{
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		}
		
		return $bundles;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.dist.yml');
		
		if (is_file(__DIR__.'/config/config_'.$this->getEnvironment().'.yml'))
		{
			$loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
		}
	}
	
	/**
	 * Gère le passage automatique vers le site de tests si celui-ci est 
	 * ouvert et que l'utilisateur en a émis le souhait. Rien d'automatisé 
	 * n'est en place, il faut (dé)commenter cette section de code.
	 */
	/*public function onKernelRequest(GetResponseEvent $event)
	{
		if (isset($_COOKIE['beta_tests']) && $_COOKIE['beta_tests'] === 'participer' && substr($_SERVER['SERVER_NAME'], 0, strpos($_SERVER['SERVER_NAME'], '.')) !== 'test')
		{
			if ($this->getEnvironment() === 'prod')
			{
				$url = 'test'.substr($_SERVER['SERVER_NAME'], strpos($_SERVER['SERVER_NAME'], '.'));
			}
			else
			{
				$url = 'test.'.$_SERVER['SERVER_NAME'];
			}
			
			$event->setProcessed(true);
			$event->setReturnValue(new Symfony\Component\HttpFoundation\RedirectResponse('//'.$url.$_SERVER['REQUEST_URI']));
		}
	}*/
}
