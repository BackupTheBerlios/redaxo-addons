<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: setting.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */

class editor_setting {
  
  var $errorMsg = "";
  
  function colors($value, $error) {
    if (strlen($value) == "6") :
      if (!ereg("[^0-9a-fA-F]",$value)) :
        return $value;
      else :
        $this->errorMsg .= '<p class="warning">Der Wert für <strong>'.$error.' </strong>ist keine gültige Farbangabe!</p>';
        return false;
      endif;
    else :
      $this->errorMsg .= '<p class="warning">Bei <strong>'.$error.'</strong> muss die Farbangabe aus 6 Zeichen bestehen!</p>';
      return false;
    endif;
  }

// debug Errors
////////////////////////////////////////////////////////////////////////////////
  function debug_errorMsg(){
    if($this->errorMsg != "") :
      return '<p class="warning">'.$this->errorMsg.'</p>';
    endif;
  }

}

$SQL = new sql;
$SQL->setQuery("SELECT  showJsElements,
            showProperty,
            showColor,
            showHighlight,
            editorWindow, 
            hltSelector, 
            hltProperty, 
            hltValue,
            hltDeclaration,
            color1,
            color2,
            color3,
            color4,
            color5,
            color6,
            color7,
            color8,
            color9,
            color10,
            color11,
            color12,
            color13,
            color14,
            color15,
            color16
        FROM  rex_14_setting 
        WHERE   id = '1'");
$DBshowJsElements = $SQL->getValue('showJsElements');
$DBshowProperty = $SQL->getValue('showProperty');
$DBshowColor = $SQL->getValue('showColor');
$DBshowHighlight = $SQL->getValue('showHighlight');
$DBeditorWindow = $SQL->getValue('editorWindow');
$DBhltSel = $SQL->getValue('hltSelector');
$DBhltPro = $SQL->getValue('hltProperty');
$DBhltVal = $SQL->getValue('hltValue');
$DBhltDec = $SQL->getValue('hltDeclaration');
$DBcolor1 = $SQL->getValue('color1');
$DBcolor2 = $SQL->getValue('color2');
$DBcolor3 = $SQL->getValue('color3');
$DBcolor4 = $SQL->getValue('color4');
$DBcolor5 = $SQL->getValue('color5');
$DBcolor6 = $SQL->getValue('color6');
$DBcolor7 = $SQL->getValue('color7');
$DBcolor8 = $SQL->getValue('color8');
$DBcolor9 = $SQL->getValue('color9');
$DBcolor10 = $SQL->getValue('color10');
$DBcolor11 = $SQL->getValue('color11');
$DBcolor12 = $SQL->getValue('color12');
$DBcolor13 = $SQL->getValue('color13');
$DBcolor14 = $SQL->getValue('color14');
$DBcolor15 = $SQL->getValue('color15');
$DBcolor16 = $SQL->getValue('color16');
/*$DBshowJsElements = $SQL->getValue(showJsElements);
$DBshowProperty = $SQL->getValue(showProperty);
$DBshowColor = $SQL->getValue(showColor);
$DBshowHighlight = $SQL->getValue(showHighlight);
$DBeditorWindow = $SQL->getValue(editorWindow);
$DBhltSel = $SQL->getValue(hltSelector);
$DBhltPro = $SQL->getValue(hltProperty);
$DBhltVal = $SQL->getValue(hltValue);
$DBhltDec = $SQL->getValue(hltDeclaration);
$DBcolor1 = $SQL->getValue(color1);
$DBcolor2 = $SQL->getValue(color2);
$DBcolor3 = $SQL->getValue(color3);
$DBcolor4 = $SQL->getValue(color4);
$DBcolor5 = $SQL->getValue(color5);
$DBcolor6 = $SQL->getValue(color6);
$DBcolor7 = $SQL->getValue(color7);
$DBcolor8 = $SQL->getValue(color8);
$DBcolor9 = $SQL->getValue(color9);
$DBcolor10 = $SQL->getValue(color10);
$DBcolor11 = $SQL->getValue(color11);
$DBcolor12 = $SQL->getValue(color12);
$DBcolor13 = $SQL->getValue(color13);
$DBcolor14 = $SQL->getValue(color14);
$DBcolor15 = $SQL->getValue(color15);
$DBcolor16 = $SQL->getValue(color16);*/


if (isset ($_POST['send']) and $_POST['send'] == 1) :

  $hlt = new editor_setting();
  $EDIThltSel = $hlt->colors($_POST['hltSelector'], "Selector");
  $EDIThltPro = $hlt->colors($_POST['hltProperty'], "Property");
  $EDIThltVal = $hlt->colors($_POST['hltValue'], "Value");
  $EDIThltDec = $hlt->colors($_POST['hltDeclaration'], "{ }, :, ;");
  
  
  if (!$EDIThltSel) :
    $DBhltSel = $_POST['hltSelector'];
    $update = false;
  else:
    $DBhltSel = $EDIThltSel;
  endif;
  
  if (!$EDIThltPro) :
    $DBhltPro = $_POST['hltProperty'];
    $update = false;
  else:
    $DBhltPro = $EDIThltPro;
  endif;
  
  if (!$EDIThltVal) :
    $DBhltVal = $_POST['hltValue'];
    $update = false;
  else:
    $DBhltVal = $EDIThltVal;
  endif;
  
  if (!$EDIThltDec) :
    $DBhltDec = $_POST['hltDeclaration'];
    $update = false;
  else:
    $DBhltDec = $EDIThltDec;
  endif;
  
  
  if ($_POST['color1'] != ""):
    $EDITcolor1 = $hlt->colors($_POST['color1'], "Color 1");
    if (!$EDITcolor1) :
      $DBcolor1 = $_POST['color1'];
      $update = false;
    else:
      $DBcolor1 = $EDITcolor1;
    endif;
  else :
    $DBcolor1 = "";
  endif;
  
  if ($_POST['color2'] != "") :
    $EDITcolor2 = $hlt->colors($_POST['color2'], "Color 2");
  
    if (!$EDITcolor2) :
      $DBcolor2 = $_POST['color2'];
      $update = false;
    else:
      $DBcolor2 = $EDITcolor2;
    endif;
  else :
    $DBcolor2 = ""; 
  endif;
  
  if ($_POST['color3'] != "") :
    $EDITcolor3 = $hlt->colors($_POST['color3'], "Color 3");
    if (!$EDITcolor3) :
      $DBcolor3 = $_POST['color3'];
      $update = false;
    else:
      $DBcolor3 = $EDITcolor3;
    endif;
  else :
    $DBcolor3 = "";
  endif;
  
  if ($_POST['color4'] != "") :
    $EDITcolor4 = $hlt->colors($_POST['color4'], "Color 4");
  
    if (!$EDITcolor4) :
      $DBcolor4 = $_POST['color4'];
      $update = false;
    else:
      $DBcolor4 = $EDITcolor4;
    endif;
  else :
    $DBcolor4 = "";
  endif;
  
  if ($_POST['color5'] != "") :
    $EDITcolor5 = $hlt->colors($_POST['color5'], "Color 5");
  
    if (!$EDITcolor5) :
      $DBcolor5 = $_POST['color5'];
      $update = false;
    else:
      $DBcolor5 = $EDITcolor5;
    endif;
  else :
    $DBcolor5 = "";
  endif;
  
  if ($_POST['color6'] != "") :
    $EDITcolor6 = $hlt->colors($_POST['color6'], "Color 6");
  
    if (!$EDITcolor6) :
      $DBcolor6 = $_POST['color6'];
      $update = false;
    else:
      $DBcolor6 = $EDITcolor6;
    endif;
  else :
    $DBcolor6 = "";
  endif;
  
  if ($_POST['color7'] != "") :
    $EDITcolor7 = $hlt->colors($_POST['color7'], "Color 7");
  
    if (!$EDITcolor7) :
      $DBcolor7 = $_POST['color7'];
      $update = false;
    else:
      $DBcolor7 = $EDITcolor7;
    endif;
  else :
    $DBcolor7 = "";
  endif;
  
  if ($_POST['color8'] != "") :
    $EDITcolor8 = $hlt->colors($_POST['color8'], "Color 8");
  
    if (!$EDITcolor8) :
      $DBcolor8 = $_POST['color8'];
      $update = false;
    else:
      $DBcolor8 = $EDITcolor8;
    endif;
  else :
    $DBcolor8 = "";
  endif;
  
  if ($_POST['color9'] != "") :
    $EDITcolor9 = $hlt->colors($_POST['color9'], "Color 9");
  
    if (!$EDITcolor9) :
      $DBcolor9 = $_POST['color9'];
      $update = false;
    else:
      $DBcolor9 = $EDITcolor9;
    endif;
  else :
    $DBcolor9 = "";
  endif;
  
  if ($_POST['color10'] != "") :
    $EDITcolor10  = $hlt->colors($_POST['color10'], "Color 10");
  
    if (!$EDITcolor10) :
      $DBcolor10 = $_POST['color10'];
      $update = false;
    else:
      $DBcolor10 = $EDITcolor10;
    endif;
  else :
    $DBcolor10  = "";
  endif;
  
  if ($_POST['color11'] != "") :
    $EDITcolor11  = $hlt->colors($_POST['color11'], "Color 11");
  
    if (!$EDITcolor11) :
      $DBcolor11 = $_POST['color11'];
      $update = false;
    else:
      $DBcolor11 = $EDITcolor11;
    endif;
  else :
    $DBcolor11  = "";
  endif;
  
  if ($_POST['color12'] != "") :
    $EDITcolor12  = $hlt->colors($_POST['color12'], "Color 12");
  
    if (!$EDITcolor12) :
      $DBcolor12 = $_POST['color12'];
      $update = false;
    else:
      $DBcolor12 = $EDITcolor12;
    endif;
  else :
    $DBcolor12  = "";
  endif;
  
  if ($_POST['color13'] != "") :
    $EDITcolor13  = $hlt->colors($_POST['color13'], "Color 13");
  
    if (!$EDITcolor13) :
      $DBcolor13 = $_POST['color13'];
      $update = false;
    else:
      $DBcolor13 = $EDITcolor13;
    endif;
  else :
    $DBcolor13  = "";
  endif;
  
  if ($_POST['color14'] != "") :
    $EDITcolor14  = $hlt->colors($_POST['color14'], "Color 14");
  
    if (!$EDITcolor14) :
      $DBcolor14 = $_POST['color14'];
      $update = false;
    else:
      $DBcolor14 = $EDITcolor14;
    endif;
  else :
    $DBcolor14  = "";
  endif;
  
  if ($_POST['color15'] != "") :
    $EDITcolor15  = $hlt->colors($_POST['color15'], "Color 15");
  
    if (!$EDITcolor15) :
      $DBcolor15 = $_POST['color15'];
      $update = false;
    else:
      $DBcolor15 = $EDITcolor15;
    endif;
  else :
    $DBcolor15  = "";
  endif;
  
  if ($_POST['color16'] != "") :
    $EDITcolor16  = $hlt->colors($_POST['color16'], "Color 16");
  
    if (!$EDITcolor16) :
      $DBcolor16 = $_POST['color16'];
      $update = false;
    else:
      $DBcolor16 = $EDITcolor16;
    endif;
  else :
    $DBcolor16  = "";
  endif;
  
  $hltDebug = $hlt->debug_errorMsg();

  if (isset ($_POST['showJsElements'])) { $DBshowJsElements = $_POST['showJsElements']; } else { $DBshowJsElements = ''; }
  if (isset ($_POST['showProperty'])) { $DBshowProperty = $_POST['showProperty']; } else { $DBshowProperty = ''; }
  if (isset ($_POST['showColor'])) { $DBshowColor = $_POST['showColor']; } else { $DBshowColor = ''; }
  if (isset ($_POST['showHighlight'])) { $DBshowHighlight = $_POST['showHighlight']; } else { $DBshowHighlight = ''; }
  if (isset ($_POST['editorWindow'])) { $EDITeditorWindow = $_POST['editorWindow']; } else { $EDITeditorWindow = ''; }
  
  if ($EDITeditorWindow <= "99"):
    $DBeditorWindow = $EDITeditorWindow;
    $errorMsg .= '<p class="warning">Für benutzerfreundliches Arbeiten ist<br /> ein Wert unter 100 Pixel nicht sinnvoll.<br /><br />Bitte geben Sie einen Wert von <strong>100 oder mehr</strong> ein.</p>';
    $update = false;
  endif;
  
  
  if ($update) :
    $SETTINGSQL = new sql;  
    //$SETTINGSQL->debugsql=1;
    $SETTINGSQL->setTable("rex_14_setting");
    $SETTINGSQL->setValue("showJsElements",$DBshowJsElements);
    $SETTINGSQL->setValue("showProperty",$DBshowProperty);
    $SETTINGSQL->setValue("showColor",$DBshowColor);
    $SETTINGSQL->setValue("showHighlight",$DBshowHighlight);
    $SETTINGSQL->setValue("editorWindow",$EDITeditorWindow);
    $SETTINGSQL->setValue("hltSelector",$EDIThltSel);
    $SETTINGSQL->setValue("hltProperty",$EDIThltPro);
    $SETTINGSQL->setValue("hltValue",$EDIThltVal);
    $SETTINGSQL->setValue("hltDeclaration",$EDIThltDec);
    $SETTINGSQL->setValue("color1",$DBcolor1);
    $SETTINGSQL->setValue("color2",$DBcolor2);
    $SETTINGSQL->setValue("color3",$DBcolor3);
    $SETTINGSQL->setValue("color4",$DBcolor4);
    $SETTINGSQL->setValue("color5",$DBcolor5);
    $SETTINGSQL->setValue("color6",$DBcolor6);
    $SETTINGSQL->setValue("color7",$DBcolor7);
    $SETTINGSQL->setValue("color8",$DBcolor8);
    $SETTINGSQL->setValue("color9",$DBcolor9);
    $SETTINGSQL->setValue("color10",$DBcolor10);
    $SETTINGSQL->setValue("color11",$DBcolor11);
    $SETTINGSQL->setValue("color12",$DBcolor12);
    $SETTINGSQL->setValue("color13",$DBcolor13);
    $SETTINGSQL->setValue("color14",$DBcolor14);
    $SETTINGSQL->setValue("color15",$DBcolor15);
    $SETTINGSQL->setValue("color16",$DBcolor16);
    /*$SETTINGSQL->setValue("color1",$EDITcolor1);
    $SETTINGSQL->setValue("color2",$EDITcolor2);
    $SETTINGSQL->setValue("color3",$EDITcolor3);
    $SETTINGSQL->setValue("color4",$EDITcolor4);
    $SETTINGSQL->setValue("color5",$EDITcolor5);
    $SETTINGSQL->setValue("color6",$EDITcolor6);
    $SETTINGSQL->setValue("color7",$EDITcolor7);
    $SETTINGSQL->setValue("color8",$EDITcolor8);
    $SETTINGSQL->setValue("color9",$EDITcolor9);
    $SETTINGSQL->setValue("color10",$EDITcolor10);
    $SETTINGSQL->setValue("color11",$EDITcolor11);
    $SETTINGSQL->setValue("color12",$EDITcolor12);
    $SETTINGSQL->setValue("color13",$EDITcolor13);
    $SETTINGSQL->setValue("color14",$EDITcolor14);
    $SETTINGSQL->setValue("color15",$EDITcolor15);
    $SETTINGSQL->setValue("color16",$EDITcolor16);*/
    $SETTINGSQL->update();
  else :
    $errorMsg .= '<p class="warning">Einstellungen wurden nicht gespeichert.</p>';
  endif;
endif;


if (!isset ($aid)) $aid = '';
if (!isset ($subpage)) $subpage = '';
if (!isset ($EDITeditorWindow)) $EDITeditorWindow = '';
if (!isset ($errorMsg)) $errorMsg = '';
if (!isset ($hltDebug)) $hltDebug = '';
?>

<table class="rex" border="0" cellpadding="5" cellspacing="1" width="770">
  <form name="editorSETTING" method="post" action="index.php" enctype="multipart/form-data"">
  <input type=hidden name="page" value="editor" />
  <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
  <input type=hidden name="clang" value="<?php echo $clang; ?>" />
  <input type=hidden name="aid" value="<?php echo $aid; ?>" />
  <input type=hidden name="editWindow" value="<?php echo $EDITeditorWindow; ?>" />
  <input type=hidden name="send" value="1" />
<tr>
  <th valign="top" width="370">Hinweise Einstellungen</th>
  <th valign="top" width="370"></th>
</tr>
<tr>
  <td valign="top"><?php print $errorMsg; print $hltDebug; ?></td>
  <td><br /><br /><br /></td>
</tr>
<tr>
  <th valign="top" width="370">allgemeine Einstellungen</th>
  <th valign="top" width="370">Oft benutzte Farben (stehen im Editor zur Verfügung)</th>
</tr>
<tr>
  <td valign="top">
  
  <table>
  <tr>
    <td><label for="editorWindow">Höhe des Editorfensters: </label></td>
    <td></td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="editorWindow" id="editorWindow" value="<?php print $DBeditorWindow; ?>" />px</td>
    <td></td>
  </tr>
  <tr>
    <td><label for="hltSelector">Farbe des Selektor: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="hltSelector" id="hltSelector" value="<?php print $DBhltSel; ?>" /></td>
    <td valign="top" rowspan="4" style="font:12px, courier"><strong>Vorschau</strong><br /><br /><span style="color:#<?php print $DBhltSel; ?>">body</span> <span style="font-weight:bold; color:#<?php print $DBhltDec; ?>">{</span><br/>&nbsp;&nbsp;&nbsp;<span style="color:#<?php print $DBhltPro; ?>">color</span><span style="font-weight:bold; color:#<?php print $DBhltDec; ?>">:</span> <span style="color:#<?php print $DBhltVal; ?>">#010278</span>;<br/><span style="font-weight:bold; color:#<?php print $DBhltDec; ?>">}</span></td>
  </tr>
  <tr>
    <td><label for="hltProperty">Farbe der Eigenschaft: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="hltProperty" id="hltProperty" value="<?php print $DBhltPro; ?>" /></td>
  </tr>
  <tr>
    <td><label for="hltValue">Farbe des Wertes: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="hltValue" id="hltValue" value="<?php print $DBhltVal; ?>" /></td>
  </tr>
  <tr>
    <td><label for="hltDeclaration">Farbe der "{ }" ":" ";": </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="hltDeclaration" id="hltDeclaration" value="<?php print $DBhltDec; ?>" /></td>
  </tr>
  </table>
  
  <table>
  <tr>
    <td><input type="checkbox" name="showJsElements" id="showJsElements" value="1" <?php if ($DBshowJsElements == 1) print 'checked="checked"'; ?> /></td>
    <td colspan="2"><label for="showJsElements">Javascript Special anzeigen?</label></td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="showProperty" id="showProperty" value="1" <?php if ($DBshowProperty == 1) print 'checked="checked"'; ?> /></td>
    <td><label for="showProperty">CSS Auswahllisten anzeigen?</label></td>
  </tr>
  <tr>
    <td></td>
    <td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="showColor" id="showColor" value="1" <?php if ($DBshowColor == 1) print 'checked="checked"'; ?> /></td>
    <td><label for="showColor">Eigene Farben anzeigen?</label></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="showHighlight" id="showHighlight" value="1" <?php if ($DBshowHighlight == 1) print 'checked="checked"'; ?> /></td>
    <td colspan="2"><label for="showHighlight">Vorschau anzeigen?</label></td>
  </tr>
  </table>
  
  </td>
  <td valign="top">
  
  <table>
  <tr>
    <td><label for="color1">Color 1: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color1" id="color1" value="<?php print $DBcolor1; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor1; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color2">Color 2: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color2" id="color2" value="<?php print $DBcolor2; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor2; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color3">Color3: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color3" id="color3" value="<?php print $DBcolor3; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor3; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color4">Color 4: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color4" id="color4" value="<?php print $DBcolor4; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor4; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color5">Color 5: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color5" id="color5" value="<?php print $DBcolor5; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor5; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color6">Color 6: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color6" id="color6" value="<?php print $DBcolor6; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor6; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color7">Color 7: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color7" id="color7" value="<?php print $DBcolor7; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor7; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color8">Color 8: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color8" id="color8" value="<?php print $DBcolor8; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor8; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color9">Color 9: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color9" id="color9" value="<?php print $DBcolor9; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor9; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color10">Color 10: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color10" id="color10" value="<?php print $DBcolor10; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor10; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color11">Color 11: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color11" id="color11" value="<?php print $DBcolor11; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor11; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color12">Color 12: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color12" id="color12" value="<?php print $DBcolor12; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor12; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color13">Color 13: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color13" id="color13" value="<?php print $DBcolor13; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor13; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color14">Color 14: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color14" id="color14" value="<?php print $DBcolor14; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor14; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color15">Color 15: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color15" id="color15" value="<?php print $DBcolor15; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor15; ?>">&nbsp;</div></td>
  </tr>
  <tr>
    <td><label for="color16">Color 16: </label></td>
    <td>#</td>
    <td><input class="form-input" style='width:50px; font:11px,courier;' type="text" name="color16" id="color16" value="<?php print $DBcolor16; ?>" /></td>
    <td><div style="width:100px; padding:2px; background:#<?php print $DBcolor16; ?>">&nbsp;</div></td>
  </tr>
  </table>
  
  </td>
</tr>
<tr>
  <td valign="top"><input class="form-submit" type="submit" name="update" value="Übernehmen" /></td>
  <td valign="top"></td>
</tr>
  </form>
</table>

