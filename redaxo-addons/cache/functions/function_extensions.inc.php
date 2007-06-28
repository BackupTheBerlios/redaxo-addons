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
 * @version $Id: function_extensions.inc.php,v 1.2 2007/06/28 16:04:04 kills Exp $
 */

function rex_a51_cache_meta_form($params)
{
  global $REX, $REX_USER, $article_id, $clang;
  
  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.config.inc.php';
  
  $config = rexCacheConfig::getInstance($article_id, $clang);
  if (!empty($_POST['meta_cache_save']))
  {
    $config->set('active', $_POST['meta_cache_activate']);
    $config->set('lifetime', $_POST['meta_cache_lifetime']);
    $config->write();
    
    if(!empty($_POST['meta_cache_regenerate']))
    {
      require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.cache.inc.php';
      
      $cache = rexCache::getInstance($article_id, $clang);
      $cache->clean();
    }
  }
  
  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.lifetimeSelect.inc.php';
  $lifetime = new lifetimeSelect('meta_cache_lifetime');
  $lifetime->set_style('class="inp100"');
  $lifetime->set_selected($config->get('lifetime'));
  
  $activated_checked = $config->isActive() ? ' checked="checked"' : '';
                
  return '
          <tr>
            <td colspan=2>Caching</td>
          </tr>
          <tr>
            <td class=grey><label for="meta_cache_activate">Cache aktiviert</label></td>
            <td class=grey>
              <input type="checkbox" id="meta_cache_activate" name="meta_cache_activate" value="1"'. $activated_checked .'/>
            </td>
          </tr>
          <tr>
            <td class=grey><label for="meta_cache_regenerate">Cache regenerieren</label></td>
            <td class=grey>
              <input type="checkbox" id="meta_cache_regenerate" name="meta_cache_regenerate" value="1"/>
            </td>
          </tr>
          <tr>
            <td class=grey><label for="meta_cache_lifetime">Lebensdauer</label></td>
            <td class=grey>'. $lifetime->out() .'</td>
          </tr>
          <tr>
            <td class=grey></td>
            <td class=grey><input type="submit" id="meta_cache_save" name="meta_cache_save" value="Einstellungen aktualisieren"/></td>
          </tr>';
}

function rex_a51_cache_write($params)
{
  global $REX, $article_id, $clang;

  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.cache.inc.php';
  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.config.inc.php';
   
  $cache = rexCache::getInstance($article_id, $clang);
  if ($cache->isActive() && $cache->isExpired())
  {
    $cache->write($params['subject']);
  }
}

function rex_a51_cache_read($params)
{
  global $REX, $article_id, $clang;

  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.cache.inc.php';
  require_once $REX['INCLUDE_PATH']. '/addons/cache/classes/class.config.inc.php';

  $cache = rexCache::getInstance($article_id, $clang);
//  var_dump($cache->isActive());
//  var_dump($cache->isCached());
  if($cache->isActive() && $cache->isCached())
  {
    echo $cache->read();
    exit();
  }
}
?>