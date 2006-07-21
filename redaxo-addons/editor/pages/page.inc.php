<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: page.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */


include $REX['INCLUDE_PATH']."/addons/$page/classes/class.editor.inc.php";
////////////////////////////////////////////////////////////////////////////////


// Für die nächsten if Abfragen
$cssDir = $REX['HTDOCS_PATH']."files/";

if ( isset($_POST['load']) AND isset($_POST['fileLoad'])) {
  $file = $_POST['fileLoad'];
  $load = new css_style();
  $load->load_css($file, $cssDir);
  $debug = $load->debug();
  $debugErrorMsg = $load->debug_errorMsg();
  $debugSuccessMsg = $load->debug_successMsg();
  
  $textarea = $load->print_css_textarea($file, $cssDir); 
  $highlight = $load->print_css_highlight($file, $cssDir); 
  
} else {
  $textarea = '';
  $highlight = '';
}


if ( isset($_POST['create']) AND isset($_POST['fileCreate'])) :
  if ($_POST['fileCreate'] == "") :
    $file = "";
  else :
    $file = $_POST['fileCreate'];
  endif;
    
  $catId = $_POST['dirCreate'];
  $create = new css_style();
  $create->create_file($file, $cssDir, $catId);
  
  $debug = $create->debug();
  $debugErrorMsg = $create->debug_errorMsg();
  $debugSuccessMsg = $create->debug_successMsg();
  
  $textarea = "/* Hier können Sie nun Styles einfügen */"; 
  $highlight = "/* Hier können Sie nun Styles einfügen */"; 
endif;


if ( isset($_POST['delete']) AND isset($_POST['fileDelete'])) :
  $fileDelete = $_POST['fileDelete'];
  $sql = new sql;
  $sql->setTable("rex_file");
  $sql->where("filename = '$fileDelete'");
  $sql->delete(); 
endif;

if ( isset($_POST['save']) OR isset($_POST['update']) AND isset($_POST['fileEdit']) AND $_POST['fileEdit'] != "") :
  
  $file = $_POST['fileEdit'];
  $styles = $_POST['styles'];
  $save = new css_style();
  $save->save_css($file, $cssDir, $styles);
  
  $debug = $save->debug();
  $debugErrorMsg = $save->debug_errorMsg();
  $debugSuccessMsg = $save->debug_successMsg();
  
  $textarea = $save->print_css_textarea($file, $cssDir); 
  $highlight = $save->print_css_highlight($file, $cssDir); 
endif;


if ( isset($_POST['validate'])) :
  $validateCSS = urlencode($_POST['styles']); 
  
  
  $url = "http://jigsaw.w3.org/css-validator/validator?text=".$validateCSS;
  $handle = fopen($url, "r");
        
  $content = '';
  while (!feof($handle)) {
    $content .= fgets($handle, 4096);
  }
        
  fclose($handle);
  
  //print $content;
  preg_match_all('/<li>(.*)<\/li>/isU',$content,$matches);
  
  
  if(is_array($matches)){
    $validatorError = array_splice($matches[0], 0, 1);
    
    $debug = '';
    foreach ($validatorError as $var){
    
      $validate = $var;
      
      preg_match('/([0-9]+)/', $validate, $line);
      $debug .= "Fehler in Zeile ".$line[0].'<a href="#"> geh hin</a><br />';

      $validate = ereg_replace('<li', '<div class="warning"', $validate);
      $validate = ereg_replace('</li', '</div', $validate);
      $validate = ereg_replace('<p', '<strong', $validate);
      $validate = ereg_replace('</p', '</strong', $validate);
      
      $debug .= $validate;
      
    }
  }
  $textarea = $_POST['styles'];
  $file = $_POST['fileEdit'];
endif;


$cssFiles = OOMedia::getMediaByExtension("css");
if (!isset ($cssSelectFile)) $cssSelectFile = '';
if (count($cssFiles) >= "1") :
  foreach ($cssFiles as $cssFile) :
    $cssFileName = $cssFile->getFileName();
    
    if (!isset($_POST['save'])) :
      if ((isset ($file) and $cssFileName == $file) OR (isset ($_POST['fileEdit']) and $cssFileName == $_POST['fileEdit']) ) :
        $selected = 'selected="selected"';
      else :
        $selected = '';
      endif;
    endif;
    
    $cssSelectFile .= '<option value="'.$cssFileName.'" '.$selected.'>'.$cssFileName.'</option>'."\n";
    
  endforeach;
else:
  $errorMsg .= '<p class="warning"><strong>Es sind keine CSS Files vorhanden.</strong></p>';
endif;


// Build the Medienpool Dirs as Select List
////////////////////////////////////////////////////////////////////////////////
$cats_sel = new select;
$cats_sel->set_style('class="form-select"');
$cats_sel->set_size(1);
$cats_sel->set_id("dirCreate");
$cats_sel->set_name("dirCreate");
$cats_sel->add_option($I18N->msg('pool_kats_no'),"0");
$mediacat_ids = array();
$rootCat = 0;
if ($rootCats = OOMediaCategory::getRootCategories()) {
  foreach( $rootCats as $rootCat) {
    add_mediacat_options_wperm( $cats_sel, $rootCat, $mediacat_ids);
  }
}
if (!isset ($rex_file_category)) $rex_file_category = '';
$cats_sel->set_selected($rex_file_category);
$selectOut = $cats_sel->out();


if (isset ($_POST['send']) and $_POST['send'] != 1) :
  $editorError = '<p class="warning">Bitte wählen Sie eine Datei aus oder legen eine neue Datei an!</p>';
  
  if (isset($_POST['update']) OR isset($_GET['showProp']) OR isset($_POST['validate'])) : 
    $editorError = "";
  endif;
  
    $debugErrorMsg = $editorError;
endif;
/*
if ($_GET['showProp'] == "no") :
  $showProp = 'style="display:none"';
  $link = '[<a href="index.php?page=editor&showProp=yes" target="_self">anzeigen</a>]';
else :
  $link = '[<a href="index.php?page=editor&showProp=no" target="_self">nicht anzeigen</a>]';
endif;
*/

$prop = new css_style();
$propContent   = $prop->create_list("content");
$propTypo    = $prop->create_list("typo");
$propColor     = $prop->create_list("color");
$propBox     = $prop->create_list("box");
$propList    = $prop->create_list("list");
$propDisplay   = $prop->create_list("display");
$propTable     = $prop->create_list("table");
$propEtc     = $prop->create_list("etc");
$propLayout    = $prop->create_list("layout");
$propPseudo    = $prop->create_list("pseudo");


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

?>

<script language="JavaScript" type="text/JavaScript">
<!--
function selectAll(theField) {
  var tempval=eval('document.'+theField)
  tempval.focus()
  tempval.select()
}
-->
</script>


<script type="text/javascript">
<!--
function insert(aTag, eTag) {
  var input = document.forms['editor'].elements['styles'];
  input.focus();
  /* für Internet Explorer */
  if(typeof document.selection != 'undefined') {
    /* Einfügen des Formatierungscodes */
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = aTag + insText + eTag;
    /* Anpassen der Cursorposition */
    range = document.selection.createRange();
    if (insText.length == 0) {
      range.move('character', -eTag.length);
    } else {
      range.moveStart('character', aTag.length + insText.length + eTag.length);      
    }
    range.select();
  }
  /* für neuere auf Gecko basierende Browser */
  else if(typeof input.selectionStart != 'undefined')
  {
    /* Einfügen des Formatierungscodes */
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + aTag + insText + eTag + input.value.substr(end);
    /* Anpassen der Cursorposition */
    var pos;
    if (insText.length == 0) {
      pos = start + aTag.length;
    } else {
      pos = start + aTag.length + insText.length + eTag.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  }
  
  /* für die übrigen Browser */
  else
  {
    /* Abfrage der Einfügeposition */
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos)) {
      pos = prompt("Einfügen an Position (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }
    /* Einfügen des Formatierungscodes */
    var insText = prompt("Bitte geben Sie den zu formatierenden Text ein:");
    input.value = input.value.substr(0, pos) + aTag + insText + eTag + input.value.substr(pos);
  }
}
//-->
</script>

<style type="text/css">
<!--

/*  undo
------------------------------------------------------------------------------*/

:link,:visited { text-decoration:none; }
ul,ol { list-style:none }
h1,h2,h3,h4,h5,h6,pre,code { font-size:1em; font-weight:normal; }

ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, html, p, 
blockquote, fieldset, input, dl, dt, dd { margin:0; padding:0 }

img, a img,:link img,:visited img, fieldset { border:none }
img { margin:0; padding:0; vertical-align:bottom; }


.clear {
  clear:both;
}

.site {
  position:relative;
  width:770px;
  background-color:#f0efeb;
}

.boxError, 
.boxFile {
  float:left;
  width:385px;
}

.boxJsElements,
.boxTextarea, 
.boxHighlight {
  float:left;
}

.boxJsElements { width:160px; }
.boxTextarea { width:330px; }
.boxHighlight { width:280px; }


.contError, 
.contFile, 
.contJsElements,
.contTextarea, 
.contHighlight { margin:5px; }

.contTextarea .form-textarea, 
.contHighlight {
  font-family:courier;
  font-size:12px;
}

.contTextarea .form-textarea {
  width:320px;
  height:<?php print $DBeditorWindow; ?>px;
  border:1px solid #999; 
  background-color:#fff; 
  padding:0 2px;
}

.contHighlight .hltSel { color:#<?php print $DBhltSel; ?>; }
.contHighlight .hltPro { color:#<?php print $DBhltPro; ?>; }
.contHighlight .hltVal { color:#<?php print $DBhltVal; ?>; }
.contHighlight .hltDec { color:#<?php print $DBhltDec; ?>; font-weight:bold; }

.color1, .color2, .color3, .color4, 
.color5, .color6, .color7, .color8, 
.color9, .color10, .color11, .color12, 
.color13, .color14, .color15, .color16 {
  float:left;
  margin:2px;
  padding:0;
  width:14px;
  height:14px;
  background:#f0efeb;
  border:1px solid #999;
  cursor:pointer;
  color:#999;
  font-family:courier;
  
}

.color1 { background:#<?php print $DBcolor1; ?>; color:#<?php print $DBcolor1; ?>;}
.color2 { background:#<?php print $DBcolor2; ?>; color:#<?php print $DBcolor2; ?>;}
.color3 { background:#<?php print $DBcolor3; ?>; color:#<?php print $DBcolor3; ?>;}
.color4 { background:#<?php print $DBcolor4; ?>; color:#<?php print $DBcolor4; ?>;}
.color5 { background:#<?php print $DBcolor5; ?>; color:#<?php print $DBcolor5; ?>;}
.color6 { background:#<?php print $DBcolor6; ?>; color:#<?php print $DBcolor6; ?>;}
.color7 { background:#<?php print $DBcolor7; ?>; color:#<?php print $DBcolor7; ?>;}
.color8 { background:#<?php print $DBcolor8; ?>; color:#<?php print $DBcolor8; ?>;}
.color9 { background:#<?php print $DBcolor9; ?>; color:#<?php print $DBcolor9; ?>;}
.color10 { background:#<?php print $DBcolor10; ?>; color:#<?php print $DBcolor10; ?>;}
.color11 { background:#<?php print $DBcolor11; ?>; color:#<?php print $DBcolor11; ?>;}
.color12 { background:#<?php print $DBcolor12; ?>; color:#<?php print $DBcolor12; ?>;}
.color13 { background:#<?php print $DBcolor13; ?>; color:#<?php print $DBcolor13; ?>;}
.color14 { background:#<?php print $DBcolor14; ?>; color:#<?php print $DBcolor14; ?>;}
.color15 { background:#<?php print $DBcolor15; ?>; color:#<?php print $DBcolor15; ?>;}
.color16 { background:#<?php print $DBcolor16; ?>; color:#<?php print $DBcolor16; ?>;}


h1 {
  padding:2px 5px;
  background-color:#d7d6d3;
  font-size:10px;
  font-weight:bold;
}

h2 {
  font-size:10px;
  font-weight:bold;
}

p.headline { font-weight:bold; }

label {
  display:block;
  float:left;
  clear:both;
  margin-top: 5px;
  width:80px;
}


.form-select, 
.form-input, 
.form-submit,
.prop-submit,
.editorWindow-submit {
  border:1px solid #999; 
  background-color:#fff; 
  padding:0; 
}

.form-select, 
.form-input { width:150px; margin-top: 5px;}

.form-input { padding:2px; }

.form-submit { width:120px; }
.prop-submit {  width:150px; padding:2px; margin: 5px 0;}
.editorWindow-submit { width:100px; padding:2px; }

<?php 

if ($DBshowJsElements == 0):
  print '.boxJsElements { display:none; }';
  print '.boxTextarea { width:490px; }';
  print '.contTextarea .form-textarea { width:480px; }';
endif;
  
if ($DBshowProperty == 0):
  print '.boxProperty { display:none; }';
endif;
  
if ($DBshowColor == 0):
  print '.boxColor { display:none; }';
endif;

if ($DBshowHighlight == 0):
  print '.boxHighlight { display:none; }';
  print '.boxTextarea { width:610px; }';
  print '.contTextarea .form-textarea { width:600px; }';
endif;

if ($DBshowJsElements == 0 AND $DBshowHighlight == 0):
  print '.boxTextarea { width:770px; }';
  print '.contTextarea .form-textarea { width:760px; }';
endif;


?>

<?php

if (!isset ($aid)) $aid = '';
if (!isset ($subpage)) $subpage = '';
if (!isset ($debug)) $debug = '';
if (!isset ($debugErrorMsg)) $debugErrorMsg = '';
if (!isset ($debugSuccessMsg)) $debugSuccessMsg = '';

?>

-->
</style>
<!-- Hinweise -->
<div class="site">
  <div class="boxError">
    <h1>Hinweise</h1>
    
    <div class="contError">
      <?php echo $debug; echo $debugErrorMsg; echo $debugSuccessMsg; ?>
    </div>
    
  </div>
  <div class="boxFile">
    <h1>Datei laden, anlegen oder löschen</h1>
    
    <!-- START Datei laden -->
    <div class="contFile">
    <form name="load" method="post" action="index.php" enctype="multipart/form-data">
    <input type=hidden name="page" value="editor" />
    <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
    <input type=hidden name="clang" value="<?php echo $clang; ?>" />
    <input type=hidden name="aid" value="<?php echo $aid; ?>" />
    <input type=hidden name="send" value="1" />
    
    <label for="selectFile">Datei: </label>
    <select class="form-select" name="fileLoad" id="selectFile">
      <!--<option value=""></option>-->
      <?php echo $cssSelectFile; ?>
    </select>
    <input class="form-submit" type="submit" name="load" value="Datei laden" />
    
    </form>
    </div>
    <!-- END Datei laden -->
    
    <!-- START Datei anlegen -->
    <div class="contFile">
    <form name="create" method="post" action="index.php" enctype="multipart/form-data">
    <input type=hidden name="page" value="editor" />
    <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
    <input type=hidden name="clang" value="<?php echo $clang; ?>" />
    <input type=hidden name="aid" value="<?php echo $aid; ?>" />
    <input type=hidden name="send" value="1" />
    
    <label for="fileCreate">Dateiname: </label>
    <input class="form-input" type="text" name="fileCreate" id="fileCreate" value="" />
    <br class="clear" />
    
    <label for="dirCreate">im Ordner: </label>
    <?php print $selectOut; ?>
    <input class="form-submit" type="submit" name="create" value="Datei anlegen" />
    
    </form>
    </div>
    <!-- END Datei anlegen -->
    
    <!-- START Datei löschen -->
    <div class="contFile">
    <form name="delete" method="post" action="index.php" enctype="multipart/form-data">
    <input type=hidden name="page" value="editor" />
    <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
    <input type=hidden name="clang" value="<?php echo $clang; ?>" />
    <input type=hidden name="aid" value="<?php echo $aid; ?>" />
    <input type=hidden name="send" value="2" />
    
    <label for="selectFile">Datei: </label>
    <select class="form-select" name="fileDelete" id="selectFile">
      <?php echo $cssSelectFile; ?>
    </select>
    <input class="form-submit" type="submit" name="delete" value="Datei löschen" />
    
    </form>
    </div>
    <!-- END Datei löschen -->
  </div>
  <br class="clear" />
</div>
  
  

<?php
if ($debugErrorMsg == "") :
?>
<div class="site">
  <!-- START JS Eleements -->
  <div class="boxJsElements">
    <h1>Javascript Specials</h1>
    
    <form name="inserti" method="get" action="index.php" enctype="multipart/form-data">
    <input type=hidden name="page" value="editor" />
    <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
    <input type=hidden name="clang" value="<?php echo $clang; ?>" />
    <input type=hidden name="aid" value="<?php echo $aid; ?>" />
    <input type=hidden name="send" value="1" />
    
    <div class="contJsElements">
    <div class="boxProperty">
    <h2>Auswahl einfügen</h2> 
    
      <!-- START Properties -->
      <div class="contProperty">
        <?php print $propTypo ?>
        <?php print $propColor ?>
        <?php print $propBox ?>
        <?php print $propDisplay ?>
        <?php print $propList ?>
        <?php print $propPseudo ?>
        <?php print $propContent ?>
        <?php print $propTable ?>
        <?php print $propLayout ?>
        <?php print $propEtc ?>
        <input class="prop-submit" type="button" value="Class-Selektor einfügen" onClick="insert('.', ' { }')">
        <input class="prop-submit" type="button" value="ID-Selektor einfügen" onClick="insert('#', ' { }')">
      </div>
      <!-- END Properties -->
    </div>
    </div>
    
    
    <div class="contJsElements boxColor">
    <h2>Eigene Farbe einfügen</h2>
      
      <!-- START Color -->
      <div class="contColor">
        <input class="color1" type="button" value="-" onClick="insert('#','<?php print $DBcolor1; ?>')" />
        <input class="color2" type="button" value="R" onClick="insert('#','<?php print $DBcolor2; ?>')" />
        <input class="color3" type="button" value="E" onClick="insert('#','<?php print $DBcolor3; ?>')" />
        <input class="color4" type="button" value="D" onClick="insert('#','<?php print $DBcolor4; ?>')" />
        <input class="color5" type="button" value="A" onClick="insert('#','<?php print $DBcolor5; ?>')" />
        <input class="color6" type="button" value="X" onClick="insert('#','<?php print $DBcolor6; ?>')" />
        <input class="color7" type="button" value="O" onClick="insert('#','<?php print $DBcolor7; ?>')" />
        <input class="color8" type="button" value="-" onClick="insert('#','<?php print $DBcolor8; ?>')" />
      
        <input class="color9" type="button" value="-" onClick="insert('#','<?php print $DBcolor9; ?>')" />
        <input class="color10" type="button" value="E" onClick="insert('#','<?php print $DBcolor10; ?>')" />
        <input class="color11" type="button" value="D" onClick="insert('#','<?php print $DBcolor11; ?>')" />
        <input class="color12" type="button" value="I" onClick="insert('#','<?php print $DBcolor12; ?>')" />
        <input class="color13" type="button" value="T" onClick="insert('#','<?php print $DBcolor13; ?>')" />
        <input class="color14" type="button" value="O" onClick="insert('#','<?php print $DBcolor14; ?>')" />
        <input class="color15" type="button" value="R" onClick="insert('#','<?php print $DBcolor15; ?>')" />
        <input class="color16" type="button" value="-" onClick="insert('#','<?php print $DBcolor16; ?>')" />
      </div>
      <!-- END Color -->
    
      </form>
    
    </div>
  </div>
  <!-- END Js Elements -->
  
  
  <!-- START Textarea -->
  <div class="boxTextarea">
    <h1>Editor Fenster</h1>
    
    <div class="contTextarea">
      <p class="warning">Editierdatei:
      <?php 
      if (isset($file) AND $file != "") :
        echo $file; 
      else:
        echo "keine";
        $file = '';
      endif;
      ?>
      </p>
      
      <form name="editor" method="post" action="index.php" enctype="multipart/form-data">
      <input type=hidden name="page" value="editor" />
      <input type=hidden name="subpage" value="<?php echo $subpage; ?>" />
      <input type=hidden name="clang" value="<?php echo $clang; ?>" />
      <input type=hidden name="aid" value="<?php echo $aid; ?>" />
      <input type=hidden name="send" value="2" />
      <input type=hidden name="fileEdit" value="<?php echo $file; ?>" />
    
      <textarea class="form-textarea" name="styles"><?php print $textarea; ?></textarea>    
      
      <input class="editorWindow-submit" type="submit" name="save" value="Speichern" />
      <input class="editorWindow-submit" type="submit" name="update" value="Übernehmen" />
      <br class="clear" />
      <input class="editorWindow-submit" type="reset" name="Abschicken2" value="Zurücksetzen" />
      <input class="editorWindow-submit" type="submit" name="validate" value="Validieren" />
    
    </form>
    </div>
  </div>
  <!-- END Textarea -->
  
  
  <!-- START Highlight -->
  <div class="boxHighlight">
    <h1>Ansicht der geladenen/angelegten Datei</h1>
    
    <p>&nbsp;</p>
    <div class="contHighlight">
      <?php print $highlight; ?>
    </div>
  
  </div>  
  <!-- END Highlight -->

  
  <br class="clear" />
</div>

<?php
endif;
?>