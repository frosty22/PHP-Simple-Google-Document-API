<?php

namespace GoogleDocs;

/**
 * Base of all types Google documents
 *
 * @author vit-ledvinka
 */
abstract class BaseItem {
	
	
	/**
	 * Link types 
	 */
	const LINK_PARENT = "parent";
	const LINK_EDIT = "edit";
	const LINK_PREVIEW = "preview";
	const LINK_ICON = "icon";
	const LINK_THUMBNAIL = "thumbnail";
	
	/**
	 * Get ID of folder
	 * @var string 
	 */
	protected $id;
	
	/**
	 * Authorization
	 * @var \GoogleDocs\Authorization
	 */
	protected $authorization;
	
	/**
	 * Published timestamp
	 * @var \DateTime
	 */
	protected $published;
	
	/**
	 * Last update timestamp
	 * @var \DateTime 
	 */
	protected $updated;
	
	/**
	 * Title of document
	 * @var string
	 */
	protected $title;
	
	/**
	 * Content (list of contents)
	 * @var array
	 */
	protected $content = array();
	
	/**
	 * URL of HTML content
	 * @var string
	 */
	protected $content_url;
	
	/**
	 * List of links
	 * @var array	
	 */
	protected $links = array(
		"parent" => null,
		"edit" => null,
		"preview" => null,
		"icon" => null,
		"thumbnail" => null
	);
	
	/**
	 * Raw output of thumbnal
	 * @var string
	 */
	protected $thumbnail;
	
	/**
	 * Name of author
	 * @var string
	 */
	protected $author_name;
	
	/**
	 * Email of author
	 * @var string
	 */
	protected $author_email;
	
	/**
	 * Parse element and create object
	 * @param \GoogleDocs\Authorization $auth
	 * @param \SimpleXMLElement $element 
	 */
	public function __construct(\GoogleDocs\Authorization $auth, \SimpleXMLElement $element) {
		$this->authorization = $auth;
		$this->published = new \DateTime($element->published);
		$this->updated = new \DateTime($element->updated);
		$this->title = $element->title;
		$this->content_url = $element->content["src"];
		$this->author_name = $element->author->name;
		$this->author_email = $element->author->email;
		
		foreach ($element->link as $link) {
			switch ($link["rel"]) {
				case "http://schemas.google.com/docs/2007#parent": 
					$this->links[self::LINK_PARENT] = $link["href"];
					break;
				
				case "alternate":	
					$this->links[self::LINK_EDIT] = $link["href"];
					break;
				
				case "http://schemas.google.com/docs/2007#embed":
					$this->links[self::LINK_PREVIEW] = $link["href"];
					break;
				
				case "http://schemas.google.com/docs/2007#icon":
					$this->links[self::LINK_ICON] = $link["href"];
					break;
				
				case "http://schemas.google.com/docs/2007/thumbnail":
					$this->links[self::LINK_THUMBNAIL] = $link["href"];
			}
		}
	}
	
	/**
	 * Get ID of folder
	 * @return string
	 */
	public function getId()
	{ 
		if (!isset($this->id))
			$this->id = \GoogleDocs\Tools::parseFileIdFromUrl($this->content_url);
		return $this->id;
	}
	
	/**
	 * Get RAW content of document
	 * @param string $format Format of document
	 * @return string
	 */
	public function getContent($format = null)
	{
		if ($format === null) $format = static::FORMAT_DEFAULT;
		
		if (!isset($this->content[$format])) { 
			$parsed_url = parse_url($this->content_url);
			$request = $this->authorization->createAuthorizedRequest($parsed_url["scheme"]."://".$parsed_url["host"].$parsed_url["path"]);
			
			parse_str($parsed_url["query"], $query);
			$query["exportFormat"] = $format;
			
			$request->setGets($query);
			$this->content[$format] = $request->getResponse();
		}
		return $this->content[$format];
	}
	
	/**
	 * Save content of document
	 * @param string $filename File name
	 * @param string $format Format
	 * @return bool
	 */
	public function saveContent($filename, $format = null)
	{
		if ($format === null) $format = static::FORMAT_DEFAULT;
		
		$content = $this->getContent($format);
		if ($content === false) return false;
		if (!file_put_contents($filename, $content)) return false;
		return true;
	}
	
	/**
	 * Return raw output of PNG thumbnail
	 * @param bool $base64 If true base64, else raw data
	 * @return false|string 
	 */
	public function getThumbnail($base64 = true)
	{
		if (!isset($this->thumbnail)) {
			if (!isset($this->links[self::LINK_THUMBNAIL])) return false;
			$request = $this->authorization->createAuthorizedRequest($this->links[self::LINK_THUMBNAIL]);
			$this->thumbnail = $request->getResponse();
		}
		if ($this->thumbnail === false) return false;
		return $base64 ? base64_encode($this->thumbnail) : $this->thumbnail;
	}
	
	/**
	 * Get external link of document external source
	 * @param string $type Type of link
	 * @return string
	 * @throws \InvalidArgumentException 
	 */
	public function getLink($type)
	{
		if (!isset($this->links[$type]))
			throw new \InvalidArgumentException("Link type $type doesnt exist.");
		return $this->links[$type];
	}
	
	/**
	 * Get date of published
	 * @return \DateTime
	 */
	public function getPublished() {
		return $this->published;
	}

	/**
	 * Get date of updated
	 * @return \DateTime
	 */
	public function getUpdated() {
		return $this->updated;
	}

	/**
	 * Get title of document
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get author name
	 * @return string
	 */
	public function getAuthorName() {
		return $this->author_name;
	}

	/**
	 * Get author email
	 * @return string
	 */
	public function getAuthorEmail() {
		return $this->author_email;
	}

	
	
}