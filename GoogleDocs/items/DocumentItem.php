<?php

namespace GoogleDocs;

/**
 * Document item
 *
 * @author vit-ledvinka
 */
class DocumentItem extends \GoogleDocs\BaseItem {
	
	
	/**
	 * Format types 
	 */
	const FORMAT_HTML = "html";
	const FORMAT_DOC = "doc"; // Microsoft Word
	const FORMAT_ODT = "odt"; // Open Document Format
	const FORMAT_PDF = "pdf"; // PDF
	const FORMAT_PNG = "png"; // PNG
	const FORMAT_RTF = "rtf"; // Rich format
	const FORMAT_TXT = "txt"; // Plain txt
	const FORMAT_ZIP = "zip"; // Zip archive - images + html output
	 
	/**
	 * Default format 
	 */
	const FORMAT_DEFAULT = "html"; // Default format


	
}