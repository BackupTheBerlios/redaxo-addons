<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: module.form.inc.php,v 1.1 2007/06/11 11:54:26 kills Exp $
 */
 
function gbook_form_output($article_id)
{
  global $REX;

  if (($errorfields = validFields()) === true)
  {
    $author = $_POST['name'];
    $message = $_POST['text'];
    $url = $_POST['url'];
    $email = $_POST['email'];

    $qry = 'INSERT INTO '.TBL_GBOOK.' SET  author = "'.$author.'", message = "'.$message.'", url ="'.$url.'", email="'.$email.'", created = UNIX_TIMESTAMP()';
    $sql = new sql();
    $sql->query($qry);
    // Formular wegen CSS
?>

<form name="gbook" class="gbook" action="index.php" method="post">
 <div class="error">Danke für Ihren Eintrag!</div>
</form>

<?php


  }
  else
  {
    $error = '';
    $name = '';
    $email = '';
    $url = '';
    $text = '';

    if (!empty ($_POST['gbook_save']))
    {
      // var_dump($_POST);
      // Felder mit Werten füllen
      $name = $_POST['name'];
      $email = $_POST['email'];
      $url = $_POST['url'];
      $text = $_POST['text'];

      $error .= '<div class="error">';

      foreach ($errorfields as $fieldname)
      {
        $error .= 'Pflichtfeld "'.ucwords($fieldname).'" bitte korrekt ausf&uuml;llen!<br/>';
      }

      $error .= '</div>';
    }
?>

<form class="gbook" action="index.php" method="post">
 <div><input type="hidden" name="article_id" value="<?php echo $article_id ?>" /></div>
 <?php echo $error ?>
 <div>
 <div class="label"><label for="gbook_name">Name*</label></div>
 <input type="text" id="gbook_name" name="name" value="<?php echo $name ?>" maxlength="255" />
 </div>
 <div>
 <div class="label"><label for="gbook_email">Email*</label></div>
 <input type="text" id="gbook_email" name="email" value="<?php echo $email ?>" maxlength="255" />
 </div>
 <div>
 <div class="label"><label for="gbook_url">Homepage</label></div>
 <input type="text" id="gbook_url" name="url" value="<?php echo $url ?>" maxlength="255" />
 </div>
 <div>
 <div class="label"><label for="gbook_text">Text*</label></div>
 <textarea id="gbook_text" name="text" cols="0" rows="0"><?php echo $text ?></textarea>
 </div>
 <div class="buttons">
 <input class="button" type="submit" name="gbook_save"value="eintragen" />
 <input class="button" type="reset" value="zur&uuml;cksetzen" />
 </div>
 <div class="info">
 * Pflichtfelder
 </div>
</form>

<?php


  }
}

function validFields()
{
  if (empty ($_POST['gbook_save']))
    return false;
  $return = array ();

  $reqfields = array ('name', 'email', 'text');
  foreach ($reqfields as $name)
  {
    if (empty ($_POST[$name]))
    {
      $return[] = $name;
    }
  }

  // Email Syntax Prüfung
  if ($_POST['email'] != '' && 
     !(
      !(preg_match('!@.*@|\.\.|\,|\;!', $_POST['email']) ||
      !preg_match('!^.+\@(\[?)[a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$!', $_POST['email'])))
     )
  {
    $return[] = 'email';
  }
  
  // URL Syntax Prüfung
  if ($_POST['url'] != '' && 
      !preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i',$_POST['url'])) 
  {
    $return[] = 'url';
  }

  return empty ($return) ? true : $return;
}
?>