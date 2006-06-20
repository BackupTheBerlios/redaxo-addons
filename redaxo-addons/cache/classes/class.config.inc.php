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
 * @version $Id: class.config.inc.php,v 1.1 2006/06/20 09:24:20 kills Exp $
 */

class rexCacheConfig
{
  var $cachefile;
  var $cachepath;
  
  var $article_id;
  var $clang;
  
  var $vars;
  
  function rexCacheConfig($article_id, $clang = false)
  {
    global $REX;
    
    if ($clang === false || $clang === '')
    {
      $clang = $REX['CUR_CLANG'];
    }
    
    $this->article_id = $article_id;
    $this->clang = $clang;
    $this->vars = array();
    
    $identifier .= implode('',$_POST).implode('',$_GET);
    if(!empty($_SESSION))
    {
      $identifier .= implode('',$_SESSION);
    }
    
    $this->cachepath = $REX['INCLUDE_PATH'].'/addons/cache/generated/cache/';
    $this->cachefile = $this->cachepath.$article_id.'.'.$clang.'.'. md5($identifier).'.artcache';
    
    $this->read();
  }
  
  function getInstance($article_id, $clang = false)
  {
    static $instance;
    if(empty($instance))
    {
      $instance = new rexFileCacheConfig($article_id, $clang);
    }
    return $instance;
  }
  
  function getPath()
  {
    return $this->cachepath;
  }
  
  function getFileName()
  {
    return $this->cachefile;
  }
  
  function getLifeTime()
  {
    global $REX;
    
    $lifetime = $this->get('lifetime');
    return $lifetime == '0' ? $REX['ADDON_CACHE']['DEFAULT_LIFETIME'] : $lifetime;
  }
  
  function isActive()
  {
    return $this->get('active') == 1;
  }
  
  function isExpired()
  {
    if(!file_exists($this->getFileName()))
    {
      return true;
    }
    
    $lifetime = $this->getLifeTime();
    return $lifetime != '-1' && time() - $lifetime > filemtime($this->getFileName());
  }
  
  function isCacheFile($filename)
  {
    $string = explode('.', $filename);
    $lastindex = count($string) - 1;
    return ($string[0] == $this->article_id && $string[1] == $this->clang && $string[$lastindex] == 'artcache');
  }
  
  function get($key)
  {
    return $this->vars[$key];
  }
  
  function set($key, $value)
  {
    $this->vars[$key] = $value;
  }
  
  function read()
  {
    global $REX;
    
    $sql = new sql();
    // $sql->debugsql = true;
    $sql->setQuery('SELECT * FROM '.$REX['TABLE_PREFIX'].'51_cache_article WHERE article_id='. $this->article_id .' AND clang='. $this->clang);
  
    $this->vars = array();
    if($sql->getRows() > 0)
    {
      $result = $sql->get_array();
      $this->vars = $result[0];
    }
  }
  
  function write()
  {
    global $REX, $REX_USER;
    
    $sql = new sql();
    $sql->setTable($REX['TABLE_PREFIX'].'51_cache_article');
    
    foreach($this->vars as $key => $value)
    {
      $sql->setValue($key, $value);
    }
    
    $user = $REX['REDAXO'] ? $REX_USER->getValue('login') : '';
    
    if($this->exists())
    {
      $sql->where('article_id='. $this->article_id .' AND clang='. $this->clang);
      $sql->setValue('updatedate',time());
      $sql->setValue('updateuser',$user);
      $sql->update();
    }
    else
    {
      $sql->setValue('article_id', $this->article_id);
      $sql->setValue('clang', $this->clang);
      $sql->setValue('createdate',time());
      $sql->setValue('createuser',$user);
      $sql->insert();
    }
  }
  
  function exists()
  {
    return empty($this->vars);
  }
  
  function readCache()
  {
    // nichts tun
  }
  
  function writeCache()
  {
    if($this->getLifeTime() != -1)
    {
      $this->set('updatedate', time());
      $this->write();
    }
  }
}

class rexFileCacheConfig extends rexCacheConfig
{
  var $configfile;
  var $configpath;
  
  function rexFileCacheConfig($article_id, $clang = false)
  {
    global $REX;
    
    $this->configpath = $REX['INCLUDE_PATH'].'/addons/cache/generated/config/';
    $this->configfile = $this->configpath.$article_id.'.'.$clang.'.cacheconf';
    $this->rexCacheConfig($article_id, $clang);
  }
  
  function getConfigPath()
  {
    return $this->configpath;
  }
  
  function getConfigFileName()
  {
    return $this->configfile;
  }
  
  function read()
  {
    // falls vorhanden, konfig aus der Datei holen -> keine DB nötig
    if (file_exists($this->getConfigFileName()))
    {
      require($this->getConfigFileName());
      if(isset($REX_CACHE_CONFIG))
      {
        foreach($REX_CACHE_CONFIG as $key => $value)
        {
          $this->set($key, $value);
        }
      }
    }
    else
    {
      parent::read();
    }
  }
  
  function write()
  {
    parent::write();
    
    $str = '';
    $str .= '<?php'."\n\n";
    $str .= '$REX_CACHE_CONFIG = array('."\n";
    
    foreach($this->vars as $key => $value)
    {
      $str .= ' "'. $key .'" => "'. $value .'",'."\n";
    }
    
    $str .= ');'."\n\n";
    $str .= '?>';
    
    $hdl = fopen($this->getConfigFileName(), 'w');
    fwrite($hdl, $str);
    fclose($hdl);
  }
}
?>