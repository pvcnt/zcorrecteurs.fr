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

/**
 * Contrôleur gérant l'affichage de tous les billets validés d'un membre.
 *
 * @author Barbatos <barbatos@f1m.fr>
 */
class BilletsRedigesAction extends BlogActions
{
	public function execute()
	{
		zCorrecteurs::VerifierFormatageUrl(null, true);

		if(!empty($_GET['id']) && is_numeric($_GET['id']))
		{
			$InfosUtilisateur = InfosUtilisateur($_GET['id']);
			if(empty($InfosUtilisateur))
				return redirect(123, $this->generateUrl('zco_user_index'), MSG_ERROR);

			Page::$titre = 'Liste des billets rédigés par '.htmlspecialchars($InfosUtilisateur['utilisateur_pseudo']);

			list($ListerBillets, $BilletsAuteurs) = ListerBillets(array(
				'id_utilisateur' => $_GET['id'],
				'etat' => BLOG_VALIDE,
				'lecteurs' => false,
				'futur' => false
			));

			//Inclusion de la vue
			fil_ariane('Voir les billets qu\'un membre a rédigés');
			
			return render_to_response(array(
				'InfosUtilisateur' => $InfosUtilisateur,
				'ListerBillets' => $ListerBillets,
				'BilletsAuteurs' => $BilletsAuteurs,
			));
		}
		else
			return redirect(122, '/', MSG_ERROR);
	}
}
