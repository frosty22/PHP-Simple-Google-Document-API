<?php

namespace GoogleDocs;

class FolderCollection implements \Iterator {
	
	private $results = array();
	
	private $position = 0;
	
	
	
	public function add(\GoogleDocs\FolderItem $object)
	{
		$this->results[] = $object;
	}
	
    /**************************** INTERATOR INTERFACE **************************/
    public function rewind() {
		if (!isset($this->results)) $this->execute();
        $this->position = 0;
    }

    public function current() {
		if (!isset($this->results)) $this->execute();
        return $this->results[$this->position];
    }

    public function key() {
		if (!isset($this->results)) $this->execute();
        return $this->position;
    }

    public function next() {
		if (!isset($this->results)) $this->execute();
        ++$this->position;
    }

    public function valid() {
		if (!isset($this->results)) $this->execute();
        return isset($this->results[$this->position]);
    }	
		
	
}
