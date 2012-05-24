<?php

namespace GoogleDocs;

/**
 * Spreadsheet item
 *
 * @author vit-ledvinka
 */
class SpreadsheetItem extends \GoogleDocs\BaseItem {
	
	/**
	 * Format types 
	 */
	const FORMAT_HTML = "html";
	const FORMAT_XLS = "xls"; // Microsoft Excel
	const FORMAT_CSV = "csv"; // CSV
	const FORMAT_PDF = "pdf"; // PDF
	const FORMAT_ODS = "ods"; // Open Document Format
	const FORMAT_TSV = "tsv"; // Tab separated value
	const FORMAT_ZIP = "zip"; // Zip archive - images + html output
	 
	/**
	 * Default format 
	 */
	const FORMAT_DEFAULT = "html"; // Default format
	
}