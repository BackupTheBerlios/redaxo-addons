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
 * @version $Id: class.lifetimeSelect.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */
 
class lifetimeSelect extends select
{
  function lifetimeSelect($name, $addDefaultLifetime = true)
  {
    $this->init($addDefaultLifetime);
    
    $this->set_name($name);
    $this->set_id($name);
    $this->set_size(1);
  }
  
  function init($addDefaultLifetime)
  {
    parent::init();
    
    $lifetimes = array (
      '-1' => 'Unbegrenzt',
      '60' => '1 Min',
      '300' => '5 Min',
      '900' => '15 Min',
      '1800' => '30 Min',
      '3600' => '1 Stunde',
      '14400' => '4 Stunden',
      '86400' => '1 Tag',
      '172800' => '2 Tage',
      '604800' => '7 Tage',
      '2678400' => '1 Monat'
    );
    
    if($addDefaultLifetime)
    {
      $lifetimes['0'] = 'Standard';
    }

    foreach($lifetimes as $time => $label)
    {
      $this->add_option($label,$time);
    }
  }
}
?>