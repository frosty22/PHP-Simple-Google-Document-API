<?php

namespace GoogleDocs;

/**
 * Presentation item
 *
 * @author vit-ledvinka
 */
class PresentationItem extends \GoogleDocs\BaseItem {
	
	/**
	 * Format types 
	 */
	const FORMAT_PDF = "pdf"; // PDF
	const FORMAT_PNG = "png"; // PNG
	const FORMAT_TXT = "txt"; // Plain txt
	const FORMAT_PPT = "ppt"; // Powerpoint file
	const FORMAT_ZIP = "zip"; // Zip archive - images + html output
	 
	/**
	 * Default format 
	 */
	const FORMAT_DEFAULT = "pdf"; // Default format

	
	
}