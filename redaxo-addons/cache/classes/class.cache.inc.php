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
 * @version $Id: class.cache.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */

class rexCache
{
  var $file;
  var $config;

  function rexCache($article_id, $clang = false)
  {
    $this->config = rexFileCacheConfig::getInstance($article_id, $clang);
  }
  
  function clean()
  {
    $config =& $this->getConfig();
    $path = $config->getPath();
    
    if(is_dir($path))
    {
      if($hdl = opendir($config->getPath()))
      {
        while(($file = readdir($hdl)) !== false)
        {
          if($file == '.' || $file == '..') continue;
          
          if($config->isCacheFile($file))
          {
            unlink($path.$file);
          }
        }
      }
    }
  }
  
  function write($content)
  {
    $config =& $this->getConfig();
    $config->writeCache();
    
    $hdl = fopen($config->getFileName(), 'w');
    fwrite($hdl, $content);
    fclose($hdl);
    
    return true;
  }

  function read()
  {
    $config =& $this->getConfig();
    $config->readCache();
    
    if ($cache = file($config->getFileName()))
    {
      ob_clean();
      $content = implode('', $cache);
      
      // ----- EXTENSION POINT
      $content = rex_register_extension_point('OUTPUT_CACHE', $content);
      
      return $content;
    }
    
    return false;
  }
  
  /**
   * @static
   */
  function getInstance($article_id, $clang = false)
  {
    static $instance;
    if(empty($instance))
    {
      $instance = new rexCache($article_id, $clang);
    }
    return $instance;
  }
  
  function &getConfig()
  {
    return $this->config;
  }
  
  function isActive()
  {
    $config =& $this->getConfig();
    return $config->isActive();
  }
  
  function isExpired()
  {
    $config =& $this->getConfig();
    return $config->isExpired();
  }
  
  function isCached()
  {
    return !$this->isExpired();
  }
}
?>