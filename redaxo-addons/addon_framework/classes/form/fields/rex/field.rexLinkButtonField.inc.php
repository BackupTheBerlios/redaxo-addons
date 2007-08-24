<?php


/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.rexLinkButtonField.inc.php,v 1.2 2007/08/24 11:43:58 kills Exp $
 */

class rexLinkButtonField extends popupButtonField
{
  function rexLinkButtonField($name, $label, $attributes = array (), $id = '')
  {
    $this->popupButtonField($name, $label, $attributes, $id);
  }

  function getArticleName()
  {
    $article_id = $this->getValue();
    $article_name = '';
    if ($article_id != '' && $article_id != 0)
    {
      $ooa = OOArticle :: getArticleById($article_id);
      if($ooa !== null)
      {
        $article_name = $ooa->getName();
      }
    }
    return $article_name;
  }

  function getInputFields()
  {
    return sprintf('<input type="text" id="'.$this->getId().'_NAME" value="%s" readonly="readonly" class="inpgrey100" style="width: 80%%;" />', $this->getArticleName()).parent :: getInputFields();
  }

  function get()
  {
    $section = & $this->getSection();
    $form = $section->getForm();
    // Buttons erst hier einfügen, da vorher die ID noch nicht vorhanden ist
    $this->addButton('Linkmap öffnen', 'javascript:openLinkMap(\'&amp;opener_input_field='.$this->getId().'\');');
    $this->addButton('Link entfernen', 'javascript:setValue(\''.$this->getId().'\',\'\');setValue(\''.$this->getId().'_NAME\',\'\');', 'file_del.gif');

    return parent :: get();
  }
}
?>