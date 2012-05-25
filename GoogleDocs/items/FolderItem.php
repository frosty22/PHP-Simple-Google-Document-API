<?php

namespace GoogleDocs;

/**
 * Presentation item
 *
 * @author vit-ledvinka
 */
class FolderItem extends \GoogleDocs\BaseItem {
	
	
	/**
	 * Get ID of folder
	 * @return string
	 */
	public function getId()
	{ 
		if (!isset($this->id))
			$this->id = \GoogleDocs\Tools::parseFolderIdFromUrl($this->content_url);
		return $this->id;
	}
	
	/**
	 * Get list of items from folder
	 * @return \GoogleDocs\ListItems 
	 */
	public function getListItems()
	{
		$listQuery = new \GoogleDocs\ListItems($this->authorization);
		$listQuery->setFolder($this->getId());
		return $listQuery;
	}
	
	
	
}