<?php

namespace GoogleDocs;


class Tools 
{

	/**
	 * Parse folder id from URL
	 * @param string $url
	 * @return string|false 
	 */
	public static function parseFolderIdFromUrl($url)
	{
		if (preg_match("/folder%3A(.*?)\/contents/i", $url, $match)) {
			return $match[1];
		}
		return false;
	}

	/**
	 * Parse file id from URL
	 * @param string $url
	 * @return string|false 
	 */
	public static function parseFileIdFromUrl($url)
	{
		if (preg_match("/id=(.*)$/i", $url, $match)) {
			return $match[1];
		}
		return false;		
	}
	
}