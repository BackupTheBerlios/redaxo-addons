<?php

class rexHistoryManager
{
	var $histories;
	
	function rexHistoryManager()
	{
		$this->histories = array();
	}
	
	function &getInstance()
	{
		static $instance = null;
		if($instance === null)
		{
			$instance = new rexHistoryManager();
		}
		return $instance;
	}
	
	function &createHistory($historyId, $size = null)
	{
		global $I18N_HISTORY;
		
		$msgKeySize = 'history_'. $historyId .'_size';
		$msgKeyTitle= 'history_'. $historyId .'_title';
		
		if($size == null)
		{
			if($I18N_HISTORY->hasMsg($msgKeySize))
			  $size = $I18N_HISTORY->msg($msgKeySize);
			else
			  $size = 10;
		}
		
		$title = $I18N_HISTORY->msg($msgKeyTitle);
		
		$history = new rexHistory($title, $size);
		return $history;
	}
	
	function addHistory($historyId, $history)
	{
		$this->histories[$historyId] = $history;
	}
	
	function hasHistory($historyId)
	{
		return isset($this->histories[$historyId]);
	}
	
	function &getHistory($historyId, $size = null)
	{
		$manager =& rexHistoryManager::getInstance();
		if(!$manager->hasHistory($historyId))
		{
			$history =& $manager->createHistory($historyId, $size);
			$manager->addHistory($historyId, $history);
		}
		return $manager->histories[$historyId];
	}
	
	function getHistories()
	{
		$manager =& rexHistoryManager::getInstance();
		return $manager->histories;
	}
	
	function formatHistories()
	{
		$s = "\n";
		
		foreach(rexHistoryManager::getHistories() as $historyId => $history)
		{
			if($history->hasItems())
			{
				$s .= '<div class="a128_history" id="history_'. $historyId .'">'. "\n";
				$s .= '  <h3>'. $history->getTitle() .'</h3>'. "\n";
				
				$s .= '  <ul>'. "\n";
				foreach($history->getItems() as $item)
				{
					$s .= '    <li><a href="'. $item->getLink() .'">'. $item->getTitle() .'</a></li>'. "\n";
				}
				$s .= '  </ul>'. "\n";
				
				$s .= '</div>'. "\n";
			}
		}
		
		return $s;
	}
}
?>