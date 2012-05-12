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

namespace Zco\Bundle\AideBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Confirmation de la suppression d'un sujet d'aide.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class SupprimerController
{
	public function defaultAction()
	{
		\zCorrecteurs::VerifierFormatageUrl(null, true);

		if (!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$page = \Doctrine_Core::getTable('Aide')->find($_GET['id']);
			if (!$page)
			{
				return redirect(1, 'index.html', MSG_ERROR);
			}
			\Page::$titre = htmlspecialchars($page['titre']);

			if (isset($_POST['confirmer']))
			{
				$page->delete();
				return redirect(4, 'index.html');
			}
			if (isset($_POST['annuler']))
			{
				return new RedirectResponse('page-'.$page['id'].'-'.rewrite($page['titre']).'.html');
			}
			
			return render_to_response(array('page' => $page));
		}
		else
		{
			return redirect(1, 'index.html', MSG_ERROR);
		}
	}
}