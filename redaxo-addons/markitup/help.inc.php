<?php

/**
 * markitup Addon
 *
 * @author markus[dot]staab[at]redaxo[dot]de Markus Staab

 *
 * @package redaxo4
 * @version $Id: help.inc.php,v 1.2 2008/03/12 14:54:12 kills Exp $
 */

?>
<p>
Erweitert REDAXO um den WYSIWYG-Editor, MarkItUp
<br /><br />

<?php
  $file = dirname( __FILE__) .'/_changelog.txt';
  if(is_readable($file))
    echo str_replace( '+', '&nbsp;&nbsp;+', nl2br(file_get_contents($file)));
?>
</p>