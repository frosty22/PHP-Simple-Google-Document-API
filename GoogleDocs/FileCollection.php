<?php

namespace GoogleDocs;

class FileCollection implements \Iterator {
	
	private $results = array();
	
	private $position = 0;
	
	public function add($object)
	{
		if (($object instanceof \GoogleDocs\BaseItem ||
			$object instanceof \GoogleDocs\UnknownItem)
			&& (!$object instanceof \GoogleDocs\FolderItem))
			$this->results[] = $object;
		else
			throw new \Nette\ArgumentOutOfRangeException("Invalid argument of object type.");
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
