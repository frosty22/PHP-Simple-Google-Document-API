<?php

namespace GoogleDocs;

/**
 * Presentation item
 *
 * @author vit-ledvinka
 */
class FolderItem extends \GoogleDocs\BaseItem {
	
	
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