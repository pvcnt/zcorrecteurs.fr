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

use Zco\Bundle\FileBundle\Model\GenericEntityInterface;

/**
 * Blog
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @author vincent1870 <vincent@zcorrecteurs.fr>
 */
class Blog extends BaseBlog implements GenericEntityInterface
{
	const STATUS_DRAFT = 1;
	const STATUS_SUBMITTED = 2;
	const STATUS_ACCEPTED = 3;
	const STATUS_PUBLISHED = 4;
	const STATUS_REFUSED = 5;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getCategoryId()
	{
		return $this->category_id;
	}
	
	public function getCurrentVersionId()
	{
		return $this->current_version_id;
	}
	
	public function getDate()
	{
		return $this->date;
	}
	
	public function getEditionDate()
	{
		return $this->edition_date;
	}
	
	public function getPropositionDate()
	{
		return $this->proposition_date;
	}
	
	public function getValidationDate()
	{
		return $this->validation_date;
	}
	
	public function getPublicationDate()
	{
		return $this->publication_date;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getTopicUrl()
	{
		return $this->topic_url;
	}
	
	public function getCommentsStatus()
	{
		return $this->comments_status;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function getRedirectionUrl()
	{
		return $this->redirection_url;
	}
	
	public function isVirtual()
	{
		return !empty($this->redirection_url);
	}
	
	public function getLinkName()
	{
		return $this->link_name;
	}
	
	public function getLinkUrl()
	{
		return $this->link_url;
	}
	
	public function getTitle()
	{
		return $this->CurrentVersion->title;
	}
	
	public function getSubtitle()
	{
		return $this->CurrentVersion->subtitle;
	}
	
	public function getIntroduction()
	{
		return $this->CurrentVersion->introduction;
	}
	
	public function getContent()
	{
		return $this->CurrentVersion->content;
	}
	
    public function __toString()
    {
        return $this->getTitle();
    }
    
    public function generateUrl()
    {
        return '/blog/billet-'.$this->getId().'-'.rewrite($this->getTitle()).'.html';
    }

	public function export()
	{
		$authors = array();
		foreach ($this->Authors as $author)
		{
			$authors[] = array(
				'id' => $author->User->id,
				'pseudo' => $author->User->pseudo,
			);
		}
		
		return array(
			'id' => $this->getId(), 
			'category' => array(
				'id' => $this->Category->id, 
				'name' => $this->Category->nom,
			),
			'date' => $this->getPublicationDate(),
			'title' => $this->getTitle(), 
			'subtitle' => $this->getSubtitle(), 
			'introduction' => $this->getIntroduction(), 
			'content' => $this->getContent(),
			'authors' => $authors,
		);
	}
}
