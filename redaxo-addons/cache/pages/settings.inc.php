<?php

/**
 * Article Cache Addon
 *  
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * 
 * @author info[at]thomas-peterson[dot]de Thomas Peterson
 * @author <a href="http://www.thomas-peterson.de/">http://www.thomas-peterson.de/</a>
 * 
 * @package redaxo3
 * @version $Id: settings.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */
 
if(!empty($_POST['save_cache_settings']))
{
  // variablenwert aktualisieren
  $REX['ADDON_CACHE']['DEFAULT_LIFETIME'] = $_POST['meta_cache_lifetime'];
  
  $content = "// --- DYN\n\n";
  $content .= "\$REX['ADDON_CACHE']['DEFAULT_LIFETIME'] = '". $_POST['meta_cache_lifetime'] ."';\n\n";
  $content .= "// --- /DYN";
  
  $file = $REX['INCLUDE_PATH']. '/addons/cache/config.inc.php';
  if (is_writable($file))
  {
    if ($h = fopen($file, "r"))
    {
      $fcontent = fread($h, filesize($file));
      fclose($h);
      $fcontent = ereg_replace("(\/\/.---.DYN.*\/\/.---.\/DYN)", $content, $fcontent);
      
      if ($h = fopen($file, "w+"))
      {
        if (!fwrite($h, $fcontent, strlen($fcontent)))
        {
          return 'Konnte Inhalt nicht in Datei "'.$file.'" schreiben';
        }
        fclose($h);
      }
    }
  }
}

require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.lifetimeSelect.inc.php';

$lifetime = new lifetimeSelect('meta_cache_lifetime', false);
$lifetime->set_selected($REX['ADDON_CACHE']['DEFAULT_LIFETIME']);

?>
<form action="index.php" method="post">
  <input type="hidden" name="page" value="<?php echo $page ?>" />
  <p>
    <label for="meta_cache_lifetime">Standard Cache-Lebensdauer</label>
    <?php echo $lifetime->out() ?>
  </p>
  <p>
    <input type="submit" name="save_cache_settings" value="Speichern" />
  </p>
</form>