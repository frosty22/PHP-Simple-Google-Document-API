<?php

namespace GoogleDocs;


class Tools 
{

	/**
	 * Parse folder name from URL
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

}