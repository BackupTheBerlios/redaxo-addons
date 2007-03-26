<?php

class rexHistory
{
	var $title;
	var $size;
	var $sessKey;
	
	function rexHistory($title, $size = 10)
	{
		$this->title = $title;
		$this->size = 10;
		$this->sessKey = 'a128_rexHistory_'. md5($title . $size);
	}
	
	function slice($items)
	{
		return array_slice($items, 0, $this->size);
	}
	
	function addItem($item)
	{
		$items = $this->getItems();
		
		// Items die schon in der History sind nicht mehr hinzufügen
		foreach($items as $key => $_item)
		{
			if($_item->equals($item))
			{
				unset($items[$key]);
				break;
			}
		}
		
		$items = array_merge(array($item), $items);
		$this->setItems($items);
	}
	
	function setItems($items)
	{
		$items = $this->slice($items);
		rex_set_session($this->sessKey, serialize($items));
	}
	
	function getItems()
	{
		$items = rex_session($this->sessKey, 'string');
		
		if(is_string($items) && $items != '')
		  $items = unserialize($items);
		else
		  $items = array();
		  
		return $items;
	}
	
	function hasItems()
	{
		return count($this->getItems()) > 0;
	}
	
	function getTitle()
	{
		return $this->title;
	}
}

?>