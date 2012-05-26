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

namespace Zco\Bundle\AideBundle\Controller;

/**
 * Affiche un sujet d'aide.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class PageController
{
	public function defaultAction()
	{
		if (!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$page = \Doctrine_Core::getTable('Aide')->find($_GET['id']);
			if (!$page)
			{
				return redirect(1, 'index.html', MSG_ERROR);
			}
			\zCorrecteurs::VerifierFormatageUrl($page['titre'], true);
			\Page::$titre = htmlspecialchars($page['titre']);
			
			$contenu = strip_tags($page['contenu']);
			if (mb_strlen($contenu) > 240)
			{
				\Page::$description = htmlspecialchars(mb_substr($contenu, 0, mb_strpos($contenu, ' ', (mb_strlen($contenu) > 250 ? 240 : 250))));
			}
			else
			{
				\Page::$description = htmlspecialchars($contenu);
			}
			
			return render_to_response(array('page' => $page));
		}
		else
		{
			return redirect(1, 'index.html', MSG_ERROR);
		}
	}
}