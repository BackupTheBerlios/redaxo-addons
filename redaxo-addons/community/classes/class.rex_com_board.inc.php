<?php

class rex_com_board
{

	var $boardname;
	var $realboardname;

	var $anonymous = false;
	var $userjoin_query;
	var $userjoin_field;
	var $user_id;
	var $username;

	var $admin;

	var $addlink;
	var $linkuser;

	var $errmsg;
	var $text;

	var $msg;

	var $form_post_action;

	// ----- konstruktor
	function rex_com_board()
	{
		$this->addlink = array();
		$this->admin = false;
		$this->setBoardname("standard");
		$this->setRealBoardname("standard");
		$this->setLang("de");
		$this->setArticleId(1);
		$this->msg = array();
		$this->setFormPostAction("index.php");

		$this->setUserjoin();
		$this->setLinkUser("",0);

	}

	// ----- REX Article Id
	function setArticleId($id)
	{
		$this->article_id = $id;
		$this->addlink("article_id",$id);
	}

	// ----- Link Adds
	function addLink($name,$value)
	{
		$this->addlink[$name] = $value;
	}

	// ----- Set Form Post Action
	function setFormPostAction($name)
	{
		$this->form_post_action = $name;
	}

	// ----- Link erstellen
	function getLink($extra_params = array())
	{
		$params = array_merge($this->addlink,$extra_params);
		return rex_getUrl($this->article_id,'',$params);
	}
	
	// ----- FormArray zurückgeben
	function getFormDetails($extra_params)
	{
		$params = array_merge($this->addlink,$extra_params);
		$return["form"] = '<form name="bb_form" id="bb_form" action="'.$this->form_post_action.'" method="post">';
		for($i=1;$i<=count($params);$i++)
		{
			$return["hidden"] .= '<input type="hidden" name="'.key($params).'" value="'.htmlspecialchars(current($params)).'" />';
			next($params);
		}
		$return["submit"] = '<input type="submit" value="'.$this->text[160].'" />';
		return $return;
	}

	// ----- Admin ? -> Delete Funktionen möglich
	function setAdmin()
	{
		$this->admin = true;
	}

	// ----- Boardname - Allgemeine Bezeichnung
	function setBoardname($boardname)
	{
		$this->boardname = $boardname;
		$this->addlink("bb_boardname",$boardname);
	}

	// ----- Tabellenname
	function setRealBoardname($realboardname)
	{
		$this->realboardname = $realboardname;
	}

	// ----- Anonymous Board ?
	function setAnonymous($status)
	{
		if ($status !== false) $status = true;
		$this->anonymous = $status;
		$this->userjoin_query = "";
		$this->userjoin_field = "";
	}

	// ----- wenn userjoin -> hier link to userpage
	function setLinkUser($link,$id)
	{
		$this->linkuser = $link;
	}

	// ----- Eingeloggter User
	function setUser($user_id)
	{
		$this->user_id = $user_id;
	}

	// ----- Userjoin .. Wie ist die Tabelle verknüpft -> rex_2_user
	function setUserjoin($userjoin = "",$userfield = "")
	{
		if ($userjoin == "") $userjoin = "rex_5_user on rex_5_board.user_id=rex_5_user.user_login";
		if ($userfield == "") $userfield = "rex_5_user.user_login";
		
		$this->userjoin_query = "left join $userjoin";
		$this->userjoin_field = $userfield;
	}

	// ----- Darstellung des Users
	function showUser(&$sql)
	{
		if ($this->anonymous)
		{
			$link = $sql->getValue("user_id");	
		}else
		{
			$link = htmlspecialchars($sql->getValue($this->userjoin_field));
			if ($this->linkuser != "") $link = '<a href="'.$this->linkuser.'">'.$link.'</a>';
		}
		return $link;
	}

	// ----- Userzugriff erlaubt ?
	function checkVars()
	{
		if ($this->anonymous) return true;
		elseif ($this->user_id == "") return false;
		else return true;
	}


	// ----- Board anzeigen - Zuweisung der entsprechenden Funktion
	function showBoard()
	{
		$return = "";
		$bb_func = $_REQUEST["bb_func"];
		$msg["bb_msg_id"] = (int) $_REQUEST["bb_msg_id"];
		$msg["bb_msg_subject"] = $_REQUEST["bb_msg_subject"];
		$msg["bb_msg_message"] = $_REQUEST["bb_msg_message"];
		$msg["bb_msg_anouser"] = $_REQUEST["bb_msg_anouser"];
		$this->msg = $msg;
		if ($bb_func == "deleteMessage" && $this->admin) $return .= $this->deleteMessage($msg["bb_msg_id"]);
		else if ($bb_func == "reply" && $this->checkVars() ) $return .= $this->saveMessage();
		else if ($bb_func == "addtopic" && $this->checkVars() ) $return .= $this->saveMessage();
		else if ($bb_func == "showMessage") $return .= $this->showMessage();
		else if ($bb_func == "showAddMessage" && $this->checkVars()) $return .= $this->showAddMessage();
	    else if ($bb_func == "showAddTopic" && $this->checkVars()) $return .= $this->showAddTopic();
		else $return .= $this->showMessages();

		return $return;
	}

	// ----- Nachrichtenübersicht anzeigen
	function showMessages()
	{
		$msql = new sql();
		$msql->setQuery("select * from rex_5_board $this->userjoin_query where rex_5_board.re_message_id='0' and rex_5_board.board_id='".$this->boardname."' and rex_5_board.status='1' order by last_entry desc");
		$mout = '<table>
			<tr>
				<td colspan="5" class="bb_forumname">'.$this->text[10].'<a href="'.$this->getLink().'">'.$this->realboardname.'</a></td>
			</tr>
			<tr>
				<td colspan="5" class="bb_topicsfound"> ';
		if ($msql->getRows()==0) $mout .= $this->text[23];
		elseif ($msql->getRows()==1) $mout .= $this->text[22];
		else $mout .= $msql->getRows()." ".$this->text[20];
		if ( $this->checkVars() ) $mout .= ' - <a href="'.$this->getLink(array("bb_func"=>"showAddTopic")).'">'.$this->text[30].'</a>';
		$mout .= '</td>
			</tr>
			<tr>
				<th>'.$this->text[40].'</th>
				<th>'.$this->text[50].'</th>
				<th>'.$this->text[60].'</th>
				<th>'.$this->text[70].'</th>
				<th>'.$this->text[80].'</th>
			</tr>';
		if ($this->errmsg != "") $mout .= '<tr><td colspan="5">'.$this->errmsg.'</td></tr>';
		for ($i=0;$i<$msql->getRows();$i++)
		{
			$mout .= '<tr>
					<td class="bb_msgs_topic"><a href="'.$this->getLink(array("bb_func" => "showMessage", "bb_msg_id" => $msql->getValue("rex_5_board.message_id"))).'">';

			if ($msql->getValue("subject")== "") $mout .= $this->text[90];
			else $mout .= $msql->getValue("subject");

			$mout .= '</a></td>';
			$mout .= '<td class="bb_msgs_user">'.$this->showUser($msql).'</td>';

			$mout .= '
				<td class="bb_msgs_replies">'.$msql->getValue("rex_5_board.replies").'</td>
				<td class="bb_msgs_created">'.date($this->text[150],$msql->getValue("rex_5_board.stamp")).$this->text[155].'</td>
				<td class="bb_msgs_lastentry">'.date($this->text[150],$msql->getValue("rex_5_board.last_entry")).$this->text[155].' '.$add_marker.'</td>
				</tr>';

			$msql->next();
		}
		if ($msql->getRows()==0) $mout .= '<tr><td colspan="5" class="bb_msgs_notopic">'.$this->text[130].'</td></tr>';
		$mout .= '</table>';
		return $mout;
	}


	// ----- Topic hinzufügen.. Form

	function showAddTopic()
	{
		$form = $this->getFormDetails(array("bb_func"=>"addtopic"));
		$mout .= $form["form"].$form["hidden"].'<table>
			<tr>
				<td colspan="2" class="bb_forumname">'.$this->text[10].'<a href="'.$this->getLink().'">'.$this->realboardname.'</a></td>
			</tr>
			<tr>
				<td colspan="2" class="bb_sat_newtopic">'.$this->text[30].'</td>
			</tr>
			'.$this->warning();
		if($this->anonymous)
		{
			$mout.= '<tr>
					<td class="bb_sat_anonametext">'.$this->text[290].'</td>
					<td class="bb_sat_anonameinp"><input type="text" name="bb_msg_anouser" maxlength="30" value="'.htmlspecialchars(stripslashes($this->msg["bb_msg_anouser"])).'" class="bb_auser" /></td>
				</tr>';
		}
		$mout.= '
			<tr>
				<td class="bb_sat_topic">'.$this->text[40].'</td>
				<td class="bb_sat_topicinp"><input type="text" name="bb_msg_subject" value="'.htmlspecialchars($this->msg["bb_msg_subject"]).'" /></td>
			</tr>
			<tr>
				<td class="bb_sat_message">'.$this->text[140];
		
		if(!$this->anonymous) $mout .= '<br />'.$this->user_id;
		
		$mout .= '<br />'.date($this->text[150]).$this->text[155].'</td>
				<td class="bb_sat_messageinp"><textarea cols="60" rows="5" name="bb_msg_message">'.stripslashes(htmlspecialchars($this->msg["bb_msg_message"])).'</textarea></td>
			</tr>
			<tr>
				<td class="bb_sat_submit">&nbsp;</td>
				<td class="bb_sat_submitinp">'.$form["submit"].'</td>
			</tr>
			</table>
			</form>';
		return $mout;
	}


	// ----- Einzelne Message anzeigen
	function showMessage()
	{
		$msql = new sql();
		$msql->setQuery("select * from rex_5_board $this->userjoin_query where rex_5_board.re_message_id='0' and rex_5_board.board_id='".$this->boardname."' and rex_5_board.message_id='".$this->msg["bb_msg_id"]."' and rex_5_board.status='1'");

		if ($msql->getRows() == 1)
		{
			$mout = '<table>
				<tr>
					<td colspan="2" class="bb_forumname">'.$this->text[10].' <a href="'.$this->getLink().'">'.$this->realboardname.'</a></td>
				</tr>
				<tr>
					<td class="bb_sm_subjecttext">'.$this->text[40].'</td>
					<td class="bb_sm_subject">'.$msql->getValue("rex_5_board.subject").'</td>
				</tr><tr>';

			$mout .= '<td class="bb_sm_user">'.$this->showUser($msql).'<br />'.date($this->text[150],$msql->getValue("rex_5_board.stamp")).$this->text[155].'</td>';
			$mout .= '<td class="bb_sm_message">'.nl2br(htmlspecialchars($msql->getValue("rex_5_board.message")));
			if ($this->admin) $mout .= '<br /><br /><a href="'.$this->getLink(array("bb_func" => "deleteMessage", "bb_msg_id" => $msql->getValue("rex_5_board.message_id"))).'">'.$this->text[270].'</a>';
			$mout .= '</td></tr>';

			$mrsql = new sql();
			$mrsql->setQuery("select * from rex_5_board $this->userjoin_query where rex_5_board.re_message_id='".$this->msg["bb_msg_id"]."' and rex_5_board.status=1");

			if ($mrsql->getRows()>0)
			{
				$mout .= '<tr><td colspan="2" class="bb_sm_msgs_replies">'.$this->text[60].'</td></tr>';
				for ($i=0;$i<$mrsql->getRows();$i++)
				{
					$mout .= '<tr>';
					$mout .= '<td class="bb_sm_msgs_user">'.sprintf ("%03d",($i+1)).'<br />'.$this->showUser($mrsql).'<br />';
					$mout .= date($this->text[150],$mrsql->getValue("rex_5_board.stamp")).$this->text[155].'</td>';
					$mout .= '<td class="bb_sm_msgs_message">'.nl2br(htmlspecialchars($mrsql->getValue("rex_5_board.message")));
					if ($this->admin) $mout .= '<br /><br /><a href="'.$this->getLink(array("bb_func" => "deleteMessage", "bb_msg_id" => $mrsql->getValue("rex_5_board.message_id"))).'">'.$this->text[280].'</a>';
					$mout .= '</td></tr>';
					$mrsql->next();
				}
			}else
			{
				$mout .= '<tr><td colspan="2" class="bb_sm_noreplies">'.$this->text[170].'</td></tr>';
			}
			$mout .= '</table>';

			if ( $this->checkVars() )
			{

				$form = $this->getFormDetails(array("bb_func" => "reply", "bb_msg_id" => $this->msg["bb_msg_id"]));

				$mout .= $form["form"].$form["hidden"].'
						<table>
						<tr>
							<td colspan="2" class="bb_sm_ad_reply">'.$this->text[180].'</td>
						</tr>';
				$mout .= $this->warning(2);
				if ($this->anonymous)
				{
	                $mout.= '<tr>
		                    	<td class="bb_sm_ad_anouser">'.$this->text[290].'</td>
		                    	<td class="bb_sm_ad_anouserinp"><input type="text" name="bb_msg_anouser" maxlength="30" value="'.stripslashes(htmlspecialchars($this->msg["bb_msg_anouser"])).'" /></td>
	                      	</tr>';
				}
				$mout .= '<tr>
						<td class="bb_sm_ad_user">'.sprintf ("%03d",($i+1));
				if (!$this->anonymous) $mout .= '<br />'.$this->user_id;
				$mout .= '<br />'.date($this->text[150]).$this->text[155].'</td>
						<td class="bb_sm_ad_message"><textarea cols="60" rows="5" name="bb_msg_message">'.stripslashes(htmlspecialchars($this->msg["bb_msg_message"])).'</textarea></td>
					</tr>
					<tr>
						<td class="bb_sm_ad_submit">&nbsp;</td>
						<td class="bb_sm_ad_submitinp">'.$form["submit"].'</td>
					</tr>';
				$mout .= '</table></form>';
			}
		}
		return $mout;
	}


	// ----- Nachrichten speichern
	function saveMessage()
	{
		if(($this->anonymous == true) && ($this->msg["bb_msg_anouser"] == ''))
		{
			$this->errmsg = $this->text[300];

			if($this->msg["bb_msg_id"] > 0)
			{
				return $this->showMessage();
			} else 
			{
				return $this->showAddTopic();
			}
		}

		if ($this->msg["bb_msg_id"] > 0)
		{
			// reply
			$r_sql = new sql();
			$r_sql->setQuery("select * from rex_5_board where message_id='".$this->msg["bb_msg_id"]."' and board_id='".$this->boardname."' and status='1'");

			if (trim($this->msg["bb_msg_message"]) == "" && $r_sql->getRows() == 1)
			{
				$this->errmsg = $this->text[200];

			}elseif ($r_sql->getRows() == 1)
			{
				// insert reply
				$r_sql = new sql();
				$r_sql->setTable("rex_5_board");
				if ($this->anonymous) $r_sql->setValue("user_id",$this->msg["bb_msg_anouser"]);
				else $r_sql->setValue("user_id",$this->user_id);
				$r_sql->setValue("message",$this->msg["bb_msg_message"]);
				$r_sql->setValue("re_message_id",$this->msg["bb_msg_id"]);
				$r_sql->setValue("stamp",time());
				$r_sql->setValue("board_id",$this->boardname);
				$r_sql->setValue("status",1);
				$r_sql->insert();

				// update message
				$u_sql = new sql();
				$u_sql->setQuery("select * from rex_5_board where re_message_id='".$this->msg["bb_msg_id"]."' and status='1'");
				$u_sql->setTable("rex_5_board");
				$u_sql->where("message_id='".$this->msg["bb_msg_id"]."'");
				$u_sql->setValue("last_entry",time());
				$u_sql->setValue("replies",$u_sql->getRows());
				$u_sql->update();
				$this->errmsg = $this->text[210];
				
				$this->msg["bb_msg_message"] = "";
				$this->msg["bb_msg_subject"] = "";

			}else
			{
				$this->errmsg = $this->text[220];
			}
			$return = $this->showMessage();

		}else
		{
			// new topic

			if ($this->msg["bb_msg_subject"] != "")
			{
				$r_sql = new sql();
				//$r_sql->debugsql = 1;
				$r_sql->setTable("rex_5_board");
				if ($this->anonymous) $r_sql->setValue("user_id",$this->msg["bb_msg_anouser"]);
				else $r_sql->setValue("user_id",$this->user_id);
				$r_sql->setValue("subject",$this->msg["bb_msg_subject"]);
				$r_sql->setValue("message",$this->msg["bb_msg_message"]);
				$r_sql->setValue("re_message_id",0);
				$r_sql->setValue("stamp",time());
				$r_sql->setValue("last_entry",time());
				$r_sql->setValue("board_id",$this->boardname);
				$r_sql->setValue("replies",0);
				$r_sql->setValue("status",1);
				$r_sql->insert();
				$this->errmsg = $this->text[230];
				$return = $this->showMessages();

				$this->msg["bb_msg_message"] = "";
				$this->msg["bb_msg_subject"] = "";

			}else
			{
				$this->errmsg = $this->text[240];
				$return = $this->showAddTopic();
			}
		}
		return $return;
	}

	function deleteMessage($message_id)
	{
		// reply
		$r_sql = new sql();
		$r_sql->setQuery("select * from rex_5_board where message_id='$message_id' and board_id='".$this->boardname."'");


		if ($r_sql->getRows() == 1)
		{
			if ($r_sql->getValue("re_message_id")!=0)
			{

				// reply
				$ur_sql = new sql();
				$ur_sql->setTable("rex_5_board");
				$ur_sql->where("message_id='$message_id'");
				$ur_sql->setValue("status",0);
				$ur_sql->update();

				$message_id = $r_sql->getValue("re_message_id");

				// update topic
				$u_sql = new sql();
				$u_sql->setQuery("select * from rex_5_board where re_message_id='$message_id' and status='1'");

				$u_sql->setTable("rex_5_board");
				$u_sql->where("message_id='$message_id'");
				$u_sql->setValue("replies",$u_sql->getRows());
				$u_sql->update();

				$this->msg["bb_msg_id"] = $r_sql->getValue("re_message_id");

				$return = $this->showMessage();
			}else
			{
				// topic
				$u_sql = new sql();
				$u_sql->setTable("rex_5_board");
				$u_sql->where("message_id='$message_id' or re_message_id='$message_id'");
				$u_sql->setValue("status",0);
				$u_sql->update();

				$this->errmsg = $this->text[250];
				$return = $this->showMessages();
			}
		}else
		{
			$this->errmsg = $this->text[260];
			$return = $this->showMessages();
		}


		return $return;
	}

	function warning($colspan=2)
	{
		if ($this->errmsg != "") return '<tr><td colspan="'.$colspan.'">'.$this->errmsg.'</td></tr>';
		else return "";
	}

	function setLang($lang)
	{
		if ($lang == "en")
		{
			// --- en
			$this->text[10] = "Forum name: ";
			$this->text[20] = "Topics found"; // 10 Themen gefunden
			$this->text[22] = "One topic found"; // 1 Thema gefunden
			$this->text[23] = "No topics found"; // Kein Thema gefunden
			$this->text[30] = "Add new topic";
			$this->text[40] = "Topic";
			$this->text[50] = "Author";
			$this->text[60] = "Replies";
			$this->text[70] = "Created";
			$this->text[80] = "Last entry";
			$this->text[90] = "[ No title entered ]";
			$this->text[100]= "New";
			$this->text[110]= "Today";
			$this->text[120]= "Yesterday";
			$this->text[130]= "No topics found"; // ! doppelt, siehe text[23]
			$this->text[140]= "Message";
			$this->text[150]= "d M H:i";
			$this->text[155]= "h";
			$this->text[160]= "Add topic";
			$this->text[170]= "No replies";
			$this->text[180]= "Your reply";
			$this->text[190]= "Add reply";
			$this->text[200]= "Please enter a reply!";
			$this->text[210]= "Reply added";
			$this->text[220]= "No such topic.";
			$this->text[230]= "Topic added";
			$this->text[240]= "You forgot to enter a title for your topic. The topic was not added!";
			$this->text[250]= "Topic and replies deleted!";
			$this->text[260]= "No such topic!";
			$this->text[270]= "[ delete topic and messages ]";
			$this->text[280]= "[ delete message ]";
			$this->text[290]= "Name";
			$this->text[300]= "Please enter your name";
		}else
		{
			// --- de
			$this->text[10] = "Forumname: ";
			$this->text[20] = "Themen gefunden"; // 10 Themen gefunden
			$this->text[22] = "Ein Thema gefunden"; // 1 Thema gefunden
			$this->text[23] = "Keine Themen gefunden"; // Kein Thema gefunden
			$this->text[30] = "Neues Thema hinzufügen";
			$this->text[40] = "Thema";
			$this->text[50] = "Ersteller";
			$this->text[60] = "re";
			$this->text[70] = "Erstellt";
			$this->text[80] = "Letzter Eintrag";
			$this->text[90] = "[ Kein Titel eingeben ]";
			$this->text[100]= "Neu";
			$this->text[110]= "Heute";
			$this->text[120]= "Gestern";
			$this->text[130]= "Keine Themen gefunden";
			$this->text[140]= "Nachricht";
			$this->text[150]= "d M H:i";
			$this->text[155]= "h";
			$this->text[160]= "Thema hinzufügen";
			$this->text[170]= "Keine Antworten";
			$this->text[180]= "Deine Antwort";
			$this->text[190]= "Antwort hinzufügen";
			$this->text[200]= "Bitte gib eine Antwort ein !";
			$this->text[210]= "Antwort wurde hinzugefügt";
			$this->text[220]= "Dieses Thema existiert nicht.";
			$this->text[230]= "Thema wurde hinzugefügt";
			$this->text[240]= "Du hast keine Themaüberschrift eingegeben. Thema wurde nicht hinzugefügt !";
			$this->text[250]= "Thema und Antworten wurden gelöscht !";
			$this->text[260]= "Dieses Thema existiert nicht !";
			$this->text[270]= "[ delete topic and messages ]";
			$this->text[280]= "[ delete message ]";
			$this->text[290]= "Name";
			$this->text[300]= "Bitte gib einen Namen ein";
		}
	}
}

?>