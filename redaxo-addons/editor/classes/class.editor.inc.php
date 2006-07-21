<?php
/** 
 * Addon: Editor
 * @package redaxo3 
 * @version $Id: class.editor.inc.php,v 1.1 2006/07/21 13:53:09 kills Exp $
 */
/*
  Editor Addon by Thomas Blum
  www.blumbeet.com
  2005/09/02
  Version : Non Stable 0.1

  These class based on a part by Nicolas de Haen.
  Thanks Nico for useing these source.
*/


  
// Show just Dirs with right Permissions
////////////////////////////////////////////////////////////////////////////////
function add_mediacat_options_wperm( &$select, &$mediacat, &$mediacat_ids, $groupName = '') {
  global $PERMALL, $REX_USER;
      
  if(empty($mediacat)) return;
    
  $mname = $mediacat->getName();
  $mediacat_ids[] = $mediacat->getId();
  
  if ($PERMALL || $REX_USER->isValueOf("rights","media[".$mediacat->getId()."]")) :
    $select->add_option($mname,$mediacat->getId(), $mediacat->getId(),$mediacat->getParentId());
  endif;
  
  $childs = $mediacat->getChildren();
  if (is_array($childs)) :
    foreach ( $childs as $child) {
      add_mediacat_options_wperm( $select, $child, $mediacat_ids, $mname);
    }
  endif;
}


class css_style {               
  var $propContent_arr = array ( // Content
                "content",
                "counter",
                "counter-increment",
                "counter-reset",
                "quotes");
                
  var $propTypo_arr = array ( // Typographie
                "direction",
                "font",
                "@font-face",
                "font-family",
                "font-size",
                "font-size-adjust",
                "font-stretch",
                "font-style",
                "font-variant",
                "font-weight",
                "line-height",
                "text-align",
                "text-decoration",
                "text-indent",
                "text-shadow",
                "text-transform",
                "letter-spacing",
                "unicode-bidi",
                "vertical-align",
                "white-space",
                "word-spacing");
                
  var $propColor_arr = array ( // Colors / Background
                "background",
                "background-attachment",
                "background-color",
                "background-image",
                "background-position",
                "background-repeat",
                "color");
                
  var $propBox_arr = array (  // Box Format
                "border",
                "border-bottom",
                "border-left",
                "border-right",
                "border-top",
                
                "border-color",
                "border-bottom-color",
                "border-left-color",
                "border-right-color",
                "border-bottom-color",
                
                "border-style",
                "border-bottom-style",
                "border-left-style",
                "border-right-style",
                "border-top-style",
                
                "border-width",
                "border-bottom-width",
                "border-left-width",
                "border-right-width",
                "border-top-width",
                
                "margin",
                "margin-bottom",
                "margin-left",
                "margin-right",
                "margin-top",
                
                "padding",
                "padding-bottom",
                "padding-left",
                "padding-right",
                "padding-top",
                
                "width",
                "min-width",
                "max-width",
                
                "height",
                "min-height",
                "max-height",
                
                "overflow",
                "overflow-x",
                "overflow-y",
                
                "position",
                "bottom",
                "left",
                "right",
                "top",
                
                "box-sizing",
                "clear",
                "clip",
                "float",
                "opacity",
                "visibility",
                "z-index");
                
  var $propList_arr = array ( // Lists
                "list-style",
                "list-style-image",
                "list-style-position",
                "list-style-type");
                
  var $propDisplay_arr = array ( // Display
                "display");
                
  var $propTable_arr = array ( // Tables
                "border-collapse",
                "border-spacing",
                "caption-side",
                "empty-cells",
                "speak-header",
                "table-layout");

  var $propEtc_arr = array ( // ETC
                "cursor",
                "filters",
                "outline",
                "scrollbars");
                
  var $propLayout_arr = array ( // Layout
                ":first",
                ":left",
                ":right",
                "marks",
                "orphans",
                "page-break-before",
                "page-break-after",
                "page-break-inside",
                "page",
                "size",
                "widows");
                
  var $propPseudo_arr = array ( // Pseudo-Elemente
                ":active",
                ":focus",
                ":hover",
                ":link",
                ":visited",
                ":lang",
                ":first-letter",
                ":first-child",
                ":first-line",
                ":before",
                ":after",
                ":not");
  var $warnings = "";
  var $errorMsg = "";
  var $successMsg = "";
  var $style_arr; //multi leveled array to store all the CSS styles
  var $noStylesFound = "Keine CSS Styles gefunden!";
  var $noFileFound = "Diese Datei existiert nicht";
  var $thisFileExists = "Diese Datei existiert bereits.";
  var $thisFileIsNoCss = "Dieser Name ist für eine CSS-Datei nicht gültig. <br />Sie wurde NICHT im Medienpool sowie auf den Server angelegt.";



  
// parse CSS
////////////////////////////////////////////////////////////////////////////////
  function parse_css($str){
    $temp_arr = split('}',$str);
    array_splice($temp_arr,-1,1);
    //$temp_arr2 = explode('@',$str);                   // für @import
    //array_shift($temp_arr2);
    //print_r($temp_arr2);
    for ($i=0; $i < count($temp_arr); $i++){
      $temp1_arr = split('{',trim($temp_arr[$i]));  //split style name and property string
      if (isset ($temp1_arr[1]) and strstr($temp1_arr[1],';')) {
        $temp1_arr[1] = trim($temp1_arr[1]);
        if (strrpos($temp1_arr[1],";") == (strlen($temp1_arr[1])-1)){
          $temp1_arr[1] = substr($temp1_arr[1],0,strlen($temp1_arr[1])-1);
        }
        $temp2_arr = split(';',$temp1_arr[1]);  //split properties
        $prop_arr = array();
        for ($j=0; $j < count($temp2_arr); $j++){
          $temp3_arr = split(':',$temp2_arr[$j]);   //split property name and value

          $propName = trim($temp3_arr[0]);
          $propValue = trim($temp3_arr[1]);

          $prop_arr[$propName] = $propValue;
          
          if (!in_array($propName,$this->propContent_arr) AND !in_array($propName,$this->propTypo_arr) AND !in_array($propName,$this->propColor_arr) AND !in_array($propName,$this->propBox_arr) AND !in_array($propName,$this->propList_arr) AND !in_array($propName,$this->propDisplay_arr) AND !in_array($propName,$this->propTable_arr) AND !in_array($propName,$this->propEtc_arr) AND !in_array($propName,$this->propLayout_arr)) :
            $this->warnings .= 'Die Eigenschaft <strong>'.$temp3_arr[0].' </strong>im Selektor <strong>'.$temp1_arr[0].'</strong> ist keine CSS Eigenschaft.<br /><br />';
          endif;
        }
      }
      else {
        $prop_arr = array();
        if (isset ($temp1_arr[1])) {
          $temp2_arr = split(':', $temp1_arr[1]);
        } else {
          $temp2_arr = '';
        }
        if (isset ($temp2_arr[1])) {
          $prop_arr[] = array(trim($temp2_arr[0]),trim($temp2_arr[1]));
        } else {
          $prop_arr[] = '';
        }
      }
      $style_arr[] = array(trim($temp1_arr[0]), $prop_arr);
    }
    if (count($style_arr) < 1){
      print "fehler";
      $this->warnings .= $this->noStylesFound;
      return;
    }
    $this->style_arr = $style_arr;
    return $style_arr;
    
  }

  
// Create DropDownList with Properties
//------------------------------------------------------------------------------
  function create_list($list) {
    
    if ($list == "content") :
      $property_arr = $this->propContent_arr;
      $listHeadl = "Content";

    elseif ($list == "typo") :
      $property_arr = $this->propTypo_arr;
      $listHeadl = "Typographie";
      
    elseif ($list == "color") :
      $property_arr = $this->propColor_arr;
      $listHeadl = "Background/Color";

    elseif ($list == "box") :
      $property_arr = $this->propBox_arr;
      $listHeadl = "Box Format";
      
    elseif ($list == "list") :
      $property_arr = $this->propList_arr;
      $listHeadl = "Lists";
      
    elseif ($list == "display") :
      $property_arr = $this->propDisplay_arr;
      $listHeadl = "Displays";
      
    elseif ($list == "table") :
      $property_arr = $this->propTable_arr;
      $listHeadl = "Tables";
      
    elseif ($list == "etc") :
      $property_arr = $this->propEtc_arr;
      $listHeadl = "ETC";
      
    elseif ($list == "layout") :
      $property_arr = $this->propLayout_arr;
      $listHeadl = "Layout";
      
    elseif ($list == "pseudo") :
      $property_arr = $this->propPseudo_arr;
      $listHeadl = "Pseudo-Elemente";
    
    else :
      $this->warnings .= 'ERROR Thomas';
    endif;
      
    $RETURN = '<select onChange="this.selectedIndex=0" class="form-select"><option value="" selected="selected">'.$listHeadl.'</option><option value="">---------------------------</option>';
    
    while(list($key, $var) = each($property_arr)) :
      if ($list != "pseudo") :
        $RETURN .= '<option value="" onClick="insert(\''.$var.': \', \';\')">'.$var.'</option>';
      else :
        $RETURN .= '<option value="" onClick="insert(\'\', \''.$var.' { }\')">'.$var.'</option>';
      endif;
    endwhile;
      
    $RETURN .= '</select>';
    return $RETURN;
  }

  
// Load the File
//------------------------------------------------------------------------------

  function load_css($cssFile = false, $cssDir) {
    if ($cssFile != "") :
      if (filesize($cssDir.$cssFile) >= 1) :
        $openFile = fopen($cssDir.$cssFile, "r");
        $readFile = fread($openFile, filesize($cssDir.$cssFile));
        fclose($openFile);
    
        $style_arr = $this->parse_css($readFile);
        
        return $style_arr;
      else :
        $this->warnings .= $this->noStylesFound;
      endif;
    
    else: 
      $this->errorMsg .= $this->noFileFound;
    endif;
  }
    
  function print_css_textarea($cssFile = false, $cssDir) {
    
    $style_arr = $this->load_css($cssFile, $cssDir);
    return $this->print_style_arr($style_arr);
    
  }
  
  function print_css_highlight($cssFile = false, $cssDir) {
    
    $style_arr = $this->load_css($cssFile, $cssDir);
    return $this->print_style_arr_highlight($style_arr);
  }
  
  
// Save the Input in File and Database
//------------------------------------------------------------------------------
  function save_css($cssFile = false, $cssDir, $styles) {
    
    $style_arr = $this->parse_css($styles);
    
    global $REX_USER;
    
    $FILESQL = new sql;
    $FILESQL->setQuery("select * from rex_file where filename='$cssFile'");
  
    if ($FILESQL->getRows()==1):
  
      $openFile = fopen($cssDir.$cssFile, "w");
      fputs($openFile, $styles);
      fclose($openFile);
      
      $FILESIZE = filesize($cssDir.$cssFile);
      //if($upload) {
        @chmod($cssDir."/$NFILENAME", 0777);

        //$FILESQL->debugsql=1;
        $FILESQL->setTable("rex_file");
        $FILESQL->where("filename='$cssFile'");
        //$FILESQL->setValue("filetype",$FILETYPE);
        $FILESQL->setValue("filesize",$FILESIZE);
        $FILESQL->setValue("updatedate",time());
        $FILESQL->setValue("updateuser",$REX_USER->getValue("login"));
        $FILESQL->update();
        $ok = 1;
      //}
    elseif ($FILESQL->getRows()>1):
      $this->errorMsg .= 'Es gibt mehere Dateinamen mit '.$cssFile.' in der Datenbank';
    else:
      $this->errorMsg .= 'Dateinamen '.$cssFile.' im Medienpool nicht gefunden';
    endif;
  }
  
  
// Create a new File
//------------------------------------------------------------------------------
  function create_file($cssFile, $cssDir, $cssCatId) {
    
    if ($cssFile != "") :
    
      global $REX_USER;
    
      $extension = strstr($cssFile, '.');
      if ( strlen($extension) == "4" ) :
        // ----- neuer filename und extension holen
        $NFILENAME = strtolower(preg_replace("/[^a-zA-Z0-9.]/","_",$cssFile));
        if (strrpos($NFILENAME,".") != "") :
          $NFILE_NAME = substr($NFILENAME,0,strlen($NFILENAME)-(strlen($NFILENAME)-strrpos($NFILENAME,".")));
          $NFILE_EXT  = substr($NFILENAME,strrpos($NFILENAME,"."),strlen($NFILENAME)-strrpos($NFILENAME,"."));
  
        else:
          $NFILE_NAME = $NFILENAME;
          $NFILE_EXT  = "";
        endif;
    

        // ---- ext checken
        $ERROR_EXT = array("php","php3","php4","php5","phtml","pl","asp","aspx","cfm");
        if (in_array($NFILE_EXT,$ERROR_EXT))
        {
          $NFILE_NAME .= $NFILE_EXT;
          $NFILE_EXT = ".txt";
        } 

        $NFILENAME = $NFILE_NAME.$NFILE_EXT;
            
        if ($NFILE_EXT == ".css") :
          $FILETYPE = "text/css";
        else :
          $FILETYPE = "text/plain";
          return $this->errorMsg .= $this->thisFileIsNoCss;
        endif;
            
    
        // ----- datei schon vorhanden -> warnung ausgeben ->
        if (!file_exists($cssDir.$NFILENAME)) :
          $openFile = fopen($cssDir.$NFILENAME, "w");
          fputs($openFile, "");
          fclose($openFile);
          $upload = true;
        
        else :
          return $this->errorMsg .= $this->thisFileExists;      
        endif;
      
        $FILESIZE = filesize($cssDir.$NFILENAME);
      
        if($upload) {

          @chmod($cssDir."/$NFILENAME", 0777);
  
          $FILESQL = new sql;
          //$FILESQL->debugsql=1;
          $FILESQL->setTable("rex_file");
          $FILESQL->setValue("filetype",$FILETYPE);
          $FILESQL->setValue("title",$FILEINFOS[title]);
          $FILESQL->setValue("description",$FILEINFOS[description]);
          $FILESQL->setValue("copyright",$FILEINFOS[copyright]);
          $FILESQL->setValue("filename",$NFILENAME);
          $FILESQL->setValue("originalname",$NFILENAME);
          $FILESQL->setValue("filesize",$FILESIZE);
          $FILESQL->setValue("width",0);
          $FILESQL->setValue("height",0);
          $FILESQL->setValue("category_id",$cssCatId);
          $FILESQL->setValue("createdate",time());
          $FILESQL->setValue("createuser",$REX_USER->getValue("login"));
          $FILESQL->setValue("updatedate",time());
          $FILESQL->setValue("updateuser",$REX_USER->getValue("login"));
          $FILESQL->insert();
          $ok = 1;
        }
        
        $this->successMsg .= "Die Datei ".$NFILENAME." wurde im Medienpool und auf dem Server erfolgeich angelegt.";
    
      else :
        $this->errorMsg .= "Falsche Extension. Eine Extension besteht aus 3 Zeichen. <br /> Datei wurde <strong>NICHT</strong> angelegt.";
      endif;
    
    else :
      $this->errorMsg .= "Bitte geben Sie einen Dateinamen ein.";
    endif;
  }
  
  function print_style_arr($style_arr){
    $RETURN = '';
    if(count($style_arr)>0) :
      foreach ($style_arr as $styles) :
      
        foreach ($styles as $style) :
        
          if (!is_array($style))
            $RETURN .= "\n".$style." { ";
          
          if (is_array($style)) :
            foreach ($style as $prop => $value) :
              if ($prop == "") :
                break;
              else :
                $RETURN .= "\n\t".$prop.': '.$value.';';
              endif;
            endforeach;
          $RETURN .= "\n}\n";
          endif;
        endforeach;
      endforeach;
    else :
      $RETURN .= "\n/* ".$this->noStylesFound." */";
    endif;

    return $RETURN;
    
  }

  function print_style_arr_highlight($style_arr){
    $RETURN = '';
    if(count($style_arr)>0) :
      foreach ($style_arr as $styles) :
      
        foreach ($styles as $style) :
        
          if (!is_array($style)) :
            $style = ereg_replace("\n", '<br />', $style);
            $style = ereg_replace("\t", '&nbsp;&nbsp;&nbsp;', $style);
            
            $RETURN .= '<span class="hltSel">'.$style.'</span> <span class="hltDec">{</span> ';
          endif;
          
          if (is_array($style)) :
            foreach ($style as $prop => $value) :
              if ($prop == "") :
                break;
              else :
                $prop = ereg_replace("$prop", "&nbsp;&nbsp;&nbsp;$prop", $prop);
                $RETURN .= '<br /><span class="hltPro">'.$prop.'</span><span class="hltDec">: </span><span class="hltVal">'.$value.'</span><span class="hltDec">;</span>';
              endif;
            endforeach;
          $RETURN .= '<br /><span class="hltDec">}</span><br /><br />';
          endif;
        endforeach;
      endforeach;
    else :
      $RETURN .= "/* ".$this->noStylesFound." */";
    endif;
    
    return $RETURN;
  }


// debug
////////////////////////////////////////////////////////////////////////////////
  function debug() {
    if($this->warnings != "") :
      return '<p class="warning">'.$this->warnings.'</p>';
    endif;
  }


// debug Errors
////////////////////////////////////////////////////////////////////////////////
  function debug_errorMsg(){
    if($this->errorMsg != "") :
      return '<p class="warning">'.$this->errorMsg.'</p>';
    endif;
  }


// debug Success Messages
////////////////////////////////////////////////////////////////////////////////
  function debug_successMsg(){
    if($this->successMsg != "") :
      return '<p class="success">'.$this->successMsg.'</p>';
    endif;
  }

}
?>