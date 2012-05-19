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

use Zco\Bundle\UserBundle\Validator\Constraints;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Utilisateur inscrit sur le site.
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class Utilisateur extends BaseUtilisateur
{
	protected $rawPassword;
	
	public function __toString()
	{
		return $this->pseudo;
	}
		
	public function getId()
	{
		return $this->id;
	}
	
	public function getUsername()
	{
		return $this->pseudo;
	}
	
	public function setUsername($username)
	{
		$this->pseudo = $username;
	}
	
	public function getPassword()
	{
		return $this->mot_de_passe;
	}
	
	public function setPassword($password)
	{
		$this->mot_de_passe = $password;
	}
	
	public function getRawPassword()
	{
		return $this->rawPassword;
	}
	
	public function setRawPassword($password)
	{
		$this->rawPassword = $password;
		$this->setPassword(sha1($password));
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
		
	public function getGroupId()
	{
		return $this->groupe_id;
	}
	
	public function setGroupId($groupId)
	{
		$this->groupe_id = $groupId;
	}
	
	public function getGroup()
	{
		return $this->Groupe;
	}
	
	public function isAccountValid()
	{
		return $this->valide;
	}
	
	public function setAccountValid($valid)
	{
		return $this->valide = (bool) $valid;
	}
	
	public function isAbsent()
	{
		return (bool) $this->absent;
	}
	
	public function getAbsenceEndDate()
	{
		return $this->absence_end_date;
	}
	
	public function hasAbsenceReason()
	{
		return !empty($this->absence_reason);
	}
	
	public function getAbsenceReason()
	{
		return $this->absence_reason;
	}
	
	public function hasCitation()
	{
		return !empty($this->citation);
	}
	
	public function getCitation()
	{
		return $this->citation;
	}
	
	public function hasSignature()
	{
		return !empty($this->signature);
	}
	
	public function getSignature()
	{
		return $this->signature;
	}
	
	public function hasBiography()
	{
		return !empty($this->biography);
	}
	
	public function getBiography()
	{
		return $this->biography;
	}
	
	public function hasTitle()
	{
		return !empty($this->title);
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function hasAvatar()
	{
		return !empty($this->avatar);
	}
	
	public function getAvatar()
	{
		return $this->avatar;
	}
	
	public function getGender()
	{
		return $this->sexe;
	}
	
	public function isEmailDisplayed()
	{
		return $this->email_displayed;
	}
	
	public function isCountryDisplayed()
	{
		return $this->country_displayed;
	}
	
	public function setRegistrationHash($hash)
	{
		$this->registration_hash = $hash;
	}
	
	public function getRegistrationHash()
	{
		return $this->registration_hash;
	}
	
	public function setRegistrationDate($date)
	{
		$this->date_inscription = $date;
	}
	
	public function getRegistrationDate()
	{
		return $this->date_inscription;
	}
	
	public function hasBirthDate()
	{
		return !empty($this->birth_date) && $this->birth_date != '0000-00-00';
	}
	
	public function getBirthDate()
	{
		return $this->birth_date;
	}
	
	public function getLastActionDate()
	{
		return $this->date_derniere_visite;
	}
	
	public function getLastIpAddress()
	{
		return $this->ip;
	}
	
	public function hasPGPKey()
	{
		return !empty($this->pgp_key);
	}
	
	public function getPGPKey()
	{
		return $this->pgp_key;
	}
	
	public function getNbMessages()
	{
		return $this->forum_messages;
	}
	
	public function getNbSanctions()
	{
		return $this->nb_sanctions;
	}
	
	public function incrementPercentage($step)
	{
		$this->percentage = max(0, min($this->percentage + $step, 100));
		$this->save();
	}
	
	public function decrementPercentage($step)
	{
		$this->incrementPercentage(-$step);
	}
	
	public function getPercentage()
	{
		return $this->percentage;
	}
	
	public function hasJob()
	{
		return !empty($this->job);
	}
	
	public function getJob()
	{
		return $this->job;
	}
	
	public function hasHobbies()
	{
		return !empty($this->hobbies);
	}
	
	public function getHobbies()
	{
		return $this->hobbies;
	}
	
	public function hasWebsite()
	{
		return !empty($this->website);
	}
	
	public function getWebsite()
	{
		return $this->website;
	}
	
	public function hasLocalisation()
	{
		return !empty($this->localisation);
	}
	
	public function getLocalisation()
	{
		return $this->localisation;
	}
	
	public function hasAddress()
	{
		return !empty($this->address);
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	public function getAge()
	{
		if (!$this->birth_date)
		{
			return null;
		}
		
		return floor((time() - strtotime($this->birth_date)) / (365.25 * 3600 * 24));
	}
	
	public function getSecondaryGroups()
	{
		return $this->SecondaryGroups;
	}
	
	public function applyPunishment(\UserPunishment $punishment)
	{
		$this->nb_sanctions += 1;
		$this->groupe_id = $punishment->getGroupId();
		$this->save();
	}
	
	public function unapplyPunishment(\UserPunishment $punishment)
	{
		$this->groupe_id = $punishment->getOriginalGroupId();
		$this->save();
	}
	
	public function applyNewUsername(\UserNewUsername $query)
	{
		$this->pseudo = $query->getNewUsername();
		$this->save();
	}
	
	public function preDelete($event)
	{
		$dbh = Doctrine_Manager::connection()->getDbh();

		$stmt = $dbh->prepare("
		DELETE FROM zcov2_forum_lunonlu
		WHERE lunonlu_utilisateur_id = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		DELETE FROM zcov2_utilisateurs_preferences
		WHERE preference_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		DELETE FROM zcov2_utilisateurs_ips
		WHERE ip_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		DELETE FROM zcov2_sanctions
		WHERE sanction_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		DELETE FROM zcov2_changements_pseudos
		WHERE changement_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		//Mises à jour
		$stmt = $dbh->prepare("
		UPDATE zcov2_blog_auteurs
		SET auteur_id_utilisateur = NULL
		WHERE auteur_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_blog_commentaires
		SET commentaire_id_utilisateur = NULL
		WHERE commentaire_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_blog_validation
		SET valid_id_utilisateur = NULL
		WHERE valid_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_blog_versions
		SET version_id_utilisateur = NULL
		WHERE version_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_push_corrections
		SET correction_id_correcteur = NULL
		WHERE correction_id_correcteur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_changements_pseudos
		SET changement_id_admin = NULL
		WHERE changement_id_admin = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_forum_sondages_votes
		SET vote_membre_id = NULL
		WHERE vote_membre_id = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_forum_sujets
		SET sujet_auteur = NULL
		WHERE sujet_auteur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_forum_messages
		SET message_edite_auteur = NULL
		WHERE message_edite_auteur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_forum_messages
		SET message_auteur = NULL
		WHERE message_auteur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_livredor
		SET livredor_id_utilisateur = NULL
		WHERE livredor_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_quiz
		SET quiz_id_utilisateur = NULL
		WHERE quiz_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_quiz_questions
		SET question_id_utilisateur = NULL
		WHERE question_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_quiz_scores
		SET score_id_utilisateur = NULL
		WHERE score_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_recrutements
		SET recrutement_id_utilisateur = NULL
		WHERE recrutement_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();

		$stmt = $dbh->prepare("
		UPDATE zcov2_recrutements_candidatures
		SET candidature_id_utilisateur = NULL
		WHERE candidature_id_utilisateur = :id");
		$stmt->bindParam(':id', $this->id);
		$stmt->execute();
	}
	
	public static function loadValidatorMetadata(ClassMetadata $metadata)
	{
		$metadata->addGetterConstraint('username', new Constraints\Username(array(
			'groups' => 'registration',
		)));
		$metadata->addGetterConstraint('rawPassword', new Constraints\Password(array(
			'groups' => 'registration',
		)));
		$metadata->addGetterConstraint('email', new Constraints\Email(array(
			'groups' => 'registration',
		)));
	}
}
