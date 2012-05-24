<?php

namespace GoogleDocs;

/**
 * Collection of documents
 *
 * @author vit-ledvinka
 */
class ListItems implements \ArrayAccess {

	/**
	 * Const for root element 
	 */
	const ROOT = "root";
	
	/**
	 * Base URL for gettign documents 
	 */
	const URL = "https://docs.google.com/feeds/default/private/full/";
	
	/**
	 * Authorization
	 * @var \GoogleDocs\Authorization
	 */
	private $auth;
	
	/**
	 * Files
	 * @var \GoogleDocs\FileCollection 
	 */
	private $files;
	
	/**
	 * Folders
	 * @var \GoogleDocs\FolderCollection 
	 */
	private $folders;
	
	/**
	 * Array of results
	 * @var array
	 */
	private $results;
	
	/**
	 * Feed info
	 * @var string 
	 */
	private $feedInfo;
	
	/**
	 * Folder ID of list items collection
	 * @var string
	 */
	private $folder = null;
	
	/**
	 * Parent ID
	 * @var string 
	 */
	private $parent = null;
	
	/**
	 * Create a list items collection
	 * @param \GoogleDocs\Authorization $auth 
	 */
	public function __construct(\GoogleDocs\Authorization $auth) {
		$this->auth = $auth;
	}

	/**
	 * Get files
	 * @return \GoogleDocs\FileCollection 
	 */
	public function getFiles()
	{
		if (!isset($this->files)) $this->execute();	
		return $this->files;
	}

	/**
	 * Get folders
	 * @return \GoogleDocs\FolderCollection 
	 */
	public function getFolders()
	{
		if (!isset($this->folders)) $this->execute();	
		return $this->folders;
	}
	
	/**
	 * Set folder for collection
	 * For folder use const ROOT
	 * @param string|null|self::ROOT $folderId 
	 */
	public function setFolder($folderId)
	{
		$this->folder = $folderId;
	}
	
	/**
	 * Get last updated type 
	 * @return \DateTime
	 */
	public function getUpdated()
	{
		return $this->__get("updated");
	}
	
	/**
	 * Get title of feed
	 * @return string
	 */
	public function getTitle()
	{
		return $this->__get("title");
	}
	
	/**
	 * Get author name
	 * @return string
	 */
	public function getAuthorName()
	{
		$author = $this->__get("author");
		if (isset($author["name"])) return $author["name"];
	}
	
	/**
	 * Get author email
	 * @return string
	 */
	public function getAuthorEmail()
	{
		$author = $this->__get("author");
		if (isset($author["email"])) return $author["email"];		
	}
	
	/**
	 * Magic get for get collection information
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		if (!isset($this->results)) $this->execute();
		
		if (!isset($this->feedInfo[$name])) return null;
		return $this->feedInfo[$name];
	}
	
	/**
	 * Execute and get collection 
	 */
	private function execute()
	{
		$response = $this->getResponse();
		$xml = \simplexml_load_string($response);
		
		$this->feedInfo = array(
			"updated" => new \DateTime((string)$xml->updated),
			"title" => (string)$xml->title,
			"author" => array(
				"name" => (string)$xml->name,
				"email" => (string)$xml->email
			),
			"links" => array(
				"parent" => null
			)
		);

		foreach ($xml->link as $link) { 
			switch ($link["rel"]) {
				case "http://schemas.google.com/docs/2007#parent": 
					$this->feedInfo["links"]["parent"] = $link["href"];
					break;
			}
		}		
		
		$this->files = new \GoogleDocs\FileCollection();
		$this->folders = new \GoogleDocs\FolderCollection();
		foreach ($xml->entry as $entry) {
			$object = $this->objectFromEntry($entry);
			if ($object instanceof \GoogleDocs\FolderItem)
				$this->folders->add($object);
			else
				$this->files->add($object);
		}
	}
	
	/**
	 * Convert row of XML output to Google Doc object
	 * @param \SimpleXMLElement $entry
	 * @return \GoogleDocs\FolderItem|\GoogleDocs\ListItems|\GoogleDocs\DocumentItem|\GoogleDocs\SpreadsheetItem|\GoogleDocs\DrawingItem|\GoogleDocs\PresentationItem|\GoogleDocs\UnknownItem 
	 */
	private function objectFromEntry(\SimpleXMLElement $entry)
	{
		// Is folder?
		if ($entry->content["type"] == "application/atom+xml;type=feed") { 
				return new \GoogleDocs\FolderItem($this->auth, $entry);
		}
		
		// Exist item
		foreach ($entry->category as $category) {
			switch ($category["label"]) {
				case "document": return new \GoogleDocs\DocumentItem($this->auth, $entry);
				case "spreadsheet": return new \GoogleDocs\SpreadsheetItem($this->auth, $entry);
				case "drawing": return new \GoogleDocs\DrawingItem($this->auth, $entry);
				case "presentation": return new \GoogleDocs\PresentationItem($this->auth, $entry);
			}
		}
		
		// No item, create unknown item
		return new \GoogleDocs\UnknownItem($entry);
	}
	
	/**
	 * Get response form server
	 * @return string
	 */
	private function getResponse()
	{
		$url = "";
		if (!is_null($this->folder)) {
			$url = "folder%3A".$this->folder."/contents"; 
		} 
		
		$request = $this->auth->createAuthorizedRequest(self::URL . $url);
		$request->setGets(array("showfolders" => "true"));
		
		return $request->getResponse();		
	}
		
	
	
	/******************************* ARRAY ACCESS ***********************************/	
    public function offsetSet($offset, $value) {
		throw new \BadMethodCallException("Cant set feed of list items value.");
    }
	
    public function offsetExists($offset) {
		if (!isset($this->results)) $this->execute();
        return isset($this->feedInfo[$offset]);
    }
    public function offsetUnset($offset) {
		if (!isset($this->results)) $this->execute();
        unset($this->feedInfo[$offset]);
    }
    public function offsetGet($offset) {
		if (!isset($this->results)) $this->execute();
        return isset($this->feedInfo[$offset]) ? $this->feedInfo[$offset] : null;
    }
			
	
}