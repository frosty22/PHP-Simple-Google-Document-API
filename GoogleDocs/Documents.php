<?php

namespace GoogleDocs;

/**
 * Description of Documents
 *
 * @author vit-ledvinka
 */
class Documents {
	
	/**
	 * Authorzation 
	 * @var \GoogleDocs\Authorization 
	 */
	private $auth;
	
	/**
	 * Contructor of documents
	 * @param \GoogleDocs\Authorization $auth 
	 */
	public function __construct(\GoogleDocs\Authorization $auth) {
		$this->auth = $auth;
	}
	
	/**
	 * Get list of all folders
	 * @return array
	 */
	public function getAllFoldersList()
	{
		$query = new \GoogleDocs\ListItems($this->auth);
		return $this->getFoldersFromList($query);
	}

	/**
	 * Get list of all folders
	 * @param int $folderId
	 * @return array of \GoogleDocs\FolderItem 
	 */
	public function getFolderList($folderId = null)
	{		
		$query = new \GoogleDocs\ListItems($this->auth);
		
		if ($folderId === null) $folderId = \GoogleDocs\ListItems::ROOT;
		$query->setFolder($folderId);
		
		return $this->getFoldersFromList($query);
	}
	
	/**
	 * Get array of folders from ListItems
	 * @param \GoogleDocs\ListItems $listItems
	 * @return array of \GoogleDocs\FolderItem 
	 */
	private function getFoldersFromList(\GoogleDocs\ListItems $listItems)
	{
		$return = array();
		foreach ($listItems as $row) {
			if ($row instanceof \GoogleDocs\FolderItem) 
				$return[] = $row;
		}		
		return $return;		
	}
	
	/**
	 * Get folder tree 
	 */
	public function getFolderTree() 
	{
		$return = array();
		
		$folder_lists = $this->getFolderList();
		foreach ($folder_lists as $list) {
			$subitems = $list->getListItems();
		}
		
	}
	
	public function gaaetListFolders($parentFolderId = null)
	{
		$query = new \GoogleDocs\ListItems($this->auth);
		$this->printResult($query);			
	}	
	
	public function printResult(\GoogleDocs\ListItems $results, $dlm = "-")
	{
		echo "<h1>".$results->getTitle()."</h1>";
		$results = $results->getResults();
		foreach ($results as $result) {
			if ($result instanceof \GoogleDocs\BaseItem)
				echo $dlm . " " . $result->getTitle() . "<br>";
			else 
				$this->printResult($result, $dlm . "-");
		}		
	}
		
	public function getListFoxlders() {	
		
		$query = new \GoogleDocs\ListItems($this->auth);
		$results = $query->getResults();
		
		foreach ($results as $result) {
			if ($result instanceof \GoogleDocs\DocumentItem) {
				echo "<img src='data:image/png;base64,".$result->getThumbnail(true)."'><br />";
				echo "Icon: <img src='" . $result->getLink(\GoogleDocs\DocumentItem::LINK_ICON) . "'><br />";
				echo "Links: <a href='".$result->getLink(\GoogleDocs\DocumentItem::LINK_EDIT)."'>Edit</a> 
							 <a href='".$result->getLink(\GoogleDocs\DocumentItem::LINK_PREVIEW)."'>Preview</a> 
						     <a href='".$result->getLink(\GoogleDocs\DocumentItem::LINK_PARENT)."'>Parent</a><br>";
				echo "Title: " . $result->getTitle() . "<br>";
				echo "Autor email: " . $result->getAuthorEmail() . "<br>";
				echo "Autor name: " . $result->getAuthorName() . "<br>";
				echo "Updated: " . $result->getUpdated()->format("d.n.Y H:i:s") . "<br>";
				echo "Created: " . $result->getPublished()->format("d.n.Y H:i:s") . "<br><br><br>";
				//echo $result->getContent(\GoogleDocs\DocumentItem::FORMAT_HTML);	
				$result->saveContent("test.png", \GoogleDocs\DocumentItem::FORMAT_PNG);
				exit;
			}
		}
	}
	
	
	
	
}