<?php

/**
 * 
 * @package redaxo3
 * @version $Id: field.captchaField.inc.php,v 1.1 2006/08/04 17:46:28 kills Exp $
 */
class captchaField extends textField
{
  var $captcha;

  function captchaField($name, $label, $attributes = array (), $id = '')
  {
    if (empty ($attributes['class']))
    {
      $attributes['class'] = 'inp_captcha';
    }
    $this->textField($name, $label, $attributes = array (), $id = '');
    $this->setCaptcha(new rexCaptcha());
  }

  function setCaptcha($captcha)
  {
    $this->captcha = $captcha;
  }
  
  function registerValidators()
  {
    $section =& $this->getSection();
    $form =& $section->getForm();
    
    rexValidateEngine :: register_object('captcha', $this->captcha);
    rexValidateEngine :: register_criteria('equals', 'captcha->isValidCode', $form->getName());
    
    parent::registerValidators();
  }
  
  function getValue()
  {
    // Immer "" zurückgeben, damit nach dem Speichern 
    // nicht der eingegebene Wert wieder angezeigt wird
    return '';
  }

  function getInsertValue()
  {
    // Immer null zurückgeben, damit dieses Feld nicht im SQL auftaucht
    return null;
  }
  
  function get()
  {
    global $REX;
    
    $params = '';
    if($REX['REDAXO'])
    {
      $params ='&amp;page=guestbook&amp;func=edit';
    }
    else
    {
      global $article_id, $clang;
      $params = '&amp;article_id='. $article_id .'&amp;clang='. $clang;
    }
    
    $textfield = parent :: get();
    return '<img src="index.php?rex_captcha=crypt'. $params .'" class="captcha" />'.$textfield;
  }
}


if (!defined('REX_CAPTCHA_FONT'))
{
  define('REX_CAPTCHA_FONT', false);
}

if (!defined('REX_CAPTCHA_FONT_CHAR_WIDTH'))
{
  define('REX_CAPTCHA_FONT_CHAR_WIDTH', 10);
}

if (isset ($_GET['rex_captcha']))
{
  ob_clean();
  $captcha = new rexCaptcha();
  $captcha->sendImage();
  exit ();
}

class rexCaptcha
{
  var $text;
  var $type;

  var $height;
  var $width;

  function rexCaptcha($text = '', $type = 'png')
  {
    if($text == '')
    {
      $text = substr(strtoupper(md5(rand().microtime())), 3, 5);
    }
    $this->type = $type;
    $this->setText($text);
    
  }
  
  function setText($text)
  {
    if(empty($_SESSION['REX_CAPTCHA']))
    {
      $_SESSION['REX_CAPTCHA'] = $text;
    }
    else
    {
      $text = $_SESSION['REX_CAPTCHA'];
    }
    $this->text = $text;
    
    $width = strlen($text) * REX_CAPTCHA_FONT_CHAR_WIDTH;
    $this->setDimension(25, $width);
  }

  function setDimension($height, $width)
  {
    $this->height = $height;
    $this->width = $width;
  }
  
  function isValidCode($value, $empty, & $params, & $formvars)
  {
    $valid = $_SESSION['REX_CAPTCHA'] == $value;
    
    if($valid)
    {
      $_SESSION['REX_CAPTCHA'] = '';
    }
    
    return $valid;
  }

  function sendHeader()
  {
    switch ($this->type)
    {
      case 'gif' :
        header('Content-type: image/gif');
        break;
      case 'jpeg' :
        header('Content-type: image/jpeg');
        break;
      case 'png' :
      default :
        header('Content-type: image/png');
        break;
    }
  }

  function sendImage()
  {
    // Alle Outputbuffer schließen
    $level = ob_get_level();
    while($level > 0)
    {
      ob_end_clean();
      $level--;
    }
    
    $this->sendHeader();
    
    $image = imagecreate($this->width, $this->height);

    $bgcolor = imagecolorallocate($image, 222, 222, 222);
    imagecolortransparent($image, $bgcolor); 
    $stringcolor = imagecolorallocate($image, 0, 0, 0);
    $linecolor = imagecolorallocate($image, 0, 0, 0);
    if (REX_CAPTCHA_FONT)
    {
      imagettftext($image, 25, 0, 8, 22, $stringcolor, REX_CAPTCHA_FONT, $this->text);
    }
    else
    {
      imagestring($image, 9, 3, 4, $this->text, $stringcolor);
    }

    switch ($this->type)
    {
      case 'gif' :
        imagegif($image);
        break;
      case 'jpeg' :
        imagejpeg($image);
        break;
      case 'png' :
      default :
        imagepng($image);
        break;
    }
    exit();
  }

  function get()
  {
    return '';
  }
}
?>