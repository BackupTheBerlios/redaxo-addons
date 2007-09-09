<?php


/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_form.inc.php,v 1.7 2007/09/09 10:39:33 kills Exp $
 */

// Form Komponenten einbinden
$FormBasedir = dirname(__FILE__);

require_once $FormBasedir.'/class.rex_fieldContainer.inc.php';
require_once $FormBasedir.'/class.rex_fieldController.inc.php';
require_once $FormBasedir.'/class.rex_formSection.inc.php';
require_once $FormBasedir.'/class.rex_formField.inc.php';
require_once $FormBasedir.'/../../functions/function_rex_form.inc.php';

define('FORM_INFO_MSG', 0);
define('FORM_WARNING_MSG', 1);
define('FORM_ERROR_MSG', 2);

/**
 * RexForm Klasse
 */

class rexForm extends rexFieldContainer
{
  // Name des Formulars
  var $name;
  // Status-Meldungen
  var $messages;
  // Url, auf die nach druck auf "Uebernehmen" umgeleitet wird
  var $apply_url;

  // Zuletzt hinzugef�gte Section
  var $section;
  // Alle Sections
  var $sections;

  // Validierungsmeldungen
  var $errors;
  // Validatoren
  var $validator;

  // Datenbankverbindung
  var $sql;

  // Debug-Flag
  var $debug;

  // Modus des Formulars true/false
  var $edit_mode;

  function rexForm($name, $apply_url = '')
  {
    $this->name = $name;
    $this->messages = array ();
    $this->apply_url = '';
    $this->edit_mode = false;

    $this->errors = null;
    $this->validator = new rexValidator();

    $this->sql = new rex_sql();
    $this->debug = & $this->sql->debugsql;
  }

  function setApplyUrl($apply_url)
  {
    if ($apply_url != '')
    {
      $this->apply_url = $apply_url;
    }
  }

  /**
   * Versetzt das Formular in den Editier-Modus.
   * Im Editier Modus, ist ein "�BERNEHMEN" und "SPEICHERN" Button vorhanden.
   * Diese sind sonst nicht eingeblendet.
   */
  function setEditMode($edit_mode)
  {
    rex_valid_type($edit_mode, 'boolean', __FILE__, __LINE__);
    $this->edit_mode = $edit_mode;
  }

  function isEditMode()
  {
    return $this->edit_mode;
  }

  function isValid($form)
  {
    return is_object($form) && is_a($form, 'rexform');
  }

  function & getValidator()
  {
    return $this->validator;
  }

  function getMessages()
  {
    return $this->messages;
  }

  function setMessage($message, $message_type)
  {
    $this->messages[] = array (
      $message,
      $message_type
    );
  }

  function getName()
  {
    return $this->name;
  }

  function addField(& $field)
  {
    parent::addField($field, $this->getSection());
  }

  function addSection(& $section)
  {
    if (!rexFormSection :: isValid($section))
    {
      rexForm::triggerError('Unexpected type "'.gettype($section).'" for $section! Expecting type string or rexFormSection-Object!');
    }

    $section->rexform = & $this;
    $this->section = & $section;
    $this->sections[] = & $section;
  }

  function & getSection()
  {
    return $this->section;
  }

  function & getSections()
  {
    return $this->sections;
  }

  function numSections()
  {
    return count($this->getSections());
  }

  function triggerError($message, $message_type = E_USER_ERROR)
  {
    trigger_error('rexForm: '.$message, $message_type);
  }

  function registerValidators()
  {
    // register our validators
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->registerValidators();
    }
  }

  function activateValidators()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->activateValidators();
    }
  }

  function delete()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->delete();
    }
    // trigger extensions point
    rex_register_extension_point('REX_FORM_'.strtoupper($this->getName()).'_DELETE', '', array (
      'form' => $this
    ));
  }

  function save()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->save();
    }
    // trigger extensions point
    // Entscheiden zwischen UPDATE <-> CREATE via editMode m�glich
    rex_register_extension_point('REX_FORM_'.strtoupper($this->getName()).'_SAVE', '', array (
      'form' => $this
    ));
  }

  function _get($addDefaultFields = true)
  {
    $this->activateValidators();

    if ($addDefaultFields)
    {
      $section = & $this->getSection();
      $section->addField(new rexSaveField());
    }

    $def_params = rex_a22_getDefaultGlobalParams();
    if (is_array($def_params))
    {
      foreach ($def_params as $name => $value)
      {
        $field = & new hiddenField($name);
        $field->setValue($value);
        $this->addField($field);
      }
    }

    $s = '';
    $s .= '<!-- rexForm start -->'. "\n";
    $s .= '<div class="a22-rexform">'. "\n";
    $s .= '  <form action="index.php" method="post">'."\n";

    // Show Messages
    $s .= $this->formatMessages();

    // Show Hidden fields
    $s .= '    <div class="a22-rexform-hidden">'."\n";

    $fields = & $this->getFields();
    $numFields = $this->numFields();

    for ($t = 0; $t < $numFields; $t++)
    {
      if (is_a($fields[$t], 'hiddenfield'))
      {
        $s .= '      '.$fields[$t]->get()."\n";
      }
    }

    $s .= '    </div>'."\n";

    // Show Sections
    $sections = & $this->getSections();
    for ($i = 0; $i < $this->numSections(); $i++)
    {
      $section = & $sections[$i];
      $s .= $section->get();
    }

    $s .= '  </form>'."\n";
    $s .= '</div>'."\n";
    $s .= '<!-- rexForm end -->'. "\n";

    return $s;
  }

  function get($addDefaultFields = true)
  {
    if (isset ($_POST['rexform_cancel_button']))
    {
      // Abbrechen Button wurde gedr�ckt
      $this->redirect();
    }
    elseif (isset ($_POST['rexform_delete_button']))
    {
      // L�schen Button wurde gedr�ckt
      $this->delete();
      $this->redirect();
    }

    // Nur auf buttons reagieren, die von rex_form sind
    if (empty ($_POST) || !empty ($_POST) && empty ($_POST['rexform_save_button']) && empty ($_POST['rexform_update_button']) && empty ($_POST['rexform_delete_button']) && empty ($_POST['rexform_cancel_button']))
    {
      // new form, we (re)set the session data
      rexValidateEngine :: connect($this->getValidator());
      rexValidateEngine :: register_form($this->getName(), true);

      // register our validators
      $this->registerValidators();

      return $this->_get($addDefaultFields);
    }
    else
    {
      // validate after a POST
      rexValidateEngine :: connect($this->getValidator());

      if (!rexValidateEngine :: has_registered_validators($this->getName()) || (rexValidateEngine :: has_registered_validators($this->getName()) && rexValidateEngine :: is_valid($_POST, $this->getName())))
      {
        $this->setMessage('Daten <span class="emphasize">erfolgreich</span> gespeichert!', FORM_INFO_MSG);

        // save values
        $this->save();

        if (isset ($_POST['rexform_save_button']))
        {
          // Speichern Button wurde gedr�ckt
          $this->redirect();
        }
        else
        {
          // �bernehmen Button wurde gedr�ckt
          // Formular wieder anzeigen
          return $this->_get($addDefaultFields);
        }
      }
      else
      {
        $this->setMessage('Daten wurden <span class="emphasize">nicht</span> gespeichert!', FORM_ERROR_MSG);

        // error, redraw the form
        return $this->_get($addDefaultFields);
      }
    }
  }

  function show($addDefaultFields = true)
  {
    echo $this->get($addDefaultFields);
  }

  function formatMessages()
  {
    $messages = $this->getMessages();

    $msg = rex_request($this->getName(). '_msg', 'string');
    $msg_type = rex_request($this->getName(). '_msgtype', 'int');
    if($msg != '')
    {
      $messages[] = array($msg, $msg_type);
    }

    return rex_a22_formatMessages($messages);
  }

  function redirect($params = '')
  {
    $url = $this->apply_url;
    $hasParams = strpos($url, '?') !== false;
    if ($params != '')
    {
      if ($hasParams)
      {
        $url .= '&'.$params;
      }
      else
      {
        $url .= '?'.$params;
        $hasParams = true;
      }
    }

    $hasParams = strpos($url, '?') !== false;
    $def_params = rex_a22_getDefaultGlobalParams();
    if (is_array($def_params))
    {
      foreach ($def_params as $name => $value)
      {
        if (strpos($url, '&'.$name.'=') === false &&
            strpos($url, '?'.$name.'=') === false)
        {
          if ($hasParams)
          {
              $url .= '&'.$name.'='.$value;
          }
          else
          {
              $url .= '?'.$name.'='.$value;
              $hasParams = true;
          }
        }
      }
    }

    if ($this->debug)
    {
      exit ('<hr />Redirect to: <a href="'.$url .'">'.$url .'</a>');
    }

    header('Location: '.$url);
    exit ();
  }

  /**
   * Durchsucht das Formular nach einem Feld
   * @param string Name des Feldes, wonach gesucht werden soll
   * @return object|null Bei erfolgreicher Suche wird ein rexFormField-Objekt zur�ckgegeben, sonst null
   * @access public
   */
  function searchField($name)
  {
    $result = parent :: searchField($name);
    if ($result !== null)
    {
      return $result;
    }

    $sections = & $this->getSections();
    for ($i = 0; $this->numSections(); $i++)
    {
      $section = & $sections[$i];
      $result = $section->searchField($name);
      if ($result !== null)
      {
        return $result;
      }
    }
    return null;
  }

  function toString()
  {
    return 'rexForm: name: "'.$this->getName().'", edit_mode: "'. ($this->isEditMode() ? 'true' : 'false').'", sections: "'.$this->numSections().'"';
  }
}
?>