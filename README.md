PHP-Simple-Google-Document-API
==============================

PHP API for Google Document - provided simple access to your documents.


Simple example:
-------------------------

<?php
// Create authorizator for connect to Google Document
$auth = new \GoogleDocs\Authorization("your_email@gmail.com", "your_password", "Name of APP - whatever");		
		
// Get list of items
$query = new \GoogleDocs\ListItems($auth);

// Set base folder - need ID of folder, or ROOT, or null for list of all files/folders		
$query->setFolder(\GoogleDocs\ListItems::ROOT);		

// Get list of folders (FolderCollection)		
$folders = $query->getFolders();

// Get list of files (FileCollection)
$files = $query->getFiles();

// Iterate on all folders
foreach ($folders as $folder)
{
   // $folder = FolderItem object
   echo $folder->getId();
   echo $folder->getTitle();
   ....
}

// Iterate on all files
foreach ($files as $file)
{
   // $file = DrawingItem | DocumentItem | PresentationItem | SpreadsheetItem | UnknownItem
   echo $file->getId();
   echo $file->getTitle();

   // Print content of all Document
   if ($file instanceof DocumentItem) {
       echo $file->getContent(DocumentItem::FORMAT_HTML);
   }

   // Save content of Presentation like PDF
   if ($file instanceof PresentationItem) {
       echo $file->saveContent(__DIR__ . "/" . $file->getTitle() . ".pdf", DocumentItem::FORMAT_PDF);
   }
   ....   
} 

	
?>
