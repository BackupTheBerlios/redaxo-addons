<?

echo "<table border=0 cellpadding=0 cellspacing=0 width=770 ><tr><td class=grey><br>";

$boards = new sql;
$boards->setQuery("select distinct board_id from rex_5_board");
	
if ($boards->getRows()>0)
{

	$currentboardname = "";
		
	echo "<table border=0 cellpadding=5 cellspacing=1 width=100%>";
	for ($i=0;$i<$boards->getRows();$i++)
	{
		$boardname = $boards->getValue("board_id");
		echo "<tr><td class=dgrey><b><a href=index.php?page=community&subpage=board&FORM[boardname]=$boardname class=black>$boardname</a></b></td></tr>";
		if ($FORM[boardname] == $boardname) $currentboardname = $boardname;
		$boards->next();
	}
	echo "</table><br>";
	
	if ($currentboardname!="")
	{
	
		$board = new rex_com_board;
		$board->addLink("page","community");
		$board->addLink("subpage","board");
		$board->setBoardname($currentboardname);
		// $board->setUserjoin("rex_2_user on rex_5_board.user_id=rex_2_user.id","rex_2_user.login");
		$board->setAdmin();
		$board->setAnonymous(true);
		
		echo $board->showBoard();
	}

}else
{
	echo "&nbsp;&nbsp;Kein Board wurde eingetragen !<br>";	
}


echo "<br></td></tr></table>";



?>