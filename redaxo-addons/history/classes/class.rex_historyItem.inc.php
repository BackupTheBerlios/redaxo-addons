<?php

class rexHistoryItem
{
	var $title;
	var $link;
	
	function rexHistoryItem($title, $link)
	{
		$this->title = $title;
		$this->link = $link;
	}
	
	function getLink()
	{
		return $this->link;
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function equals($historyItem)
	{
		return(
		  $historyItem->getTitle() == $this->getTitle() &&
		  $historyItem->getLink() == $this->getLink()
		);
	}
}
?>