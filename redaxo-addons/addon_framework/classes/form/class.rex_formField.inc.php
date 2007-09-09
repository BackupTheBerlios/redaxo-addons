<?php


/**
 * Addon Framework Classes
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formField.inc.php,v 1.16 2007/09/09 10:55:14 kills Exp $
 */

class rexFormField
{
  var $id;
  var $name;
  var $value;
  var $attributes;
  var $needFullColumn;
  var $activateSave;

  var $validators;

  /**
   * Direkter Vorgänger des Elements
   * @var rexForm|rexSection
   */
  var $parent;
  var $rexsection;
  var $valueManager;

  function rexFormField($name, $label, $attributes = array (), $id = '')
  {
    $this->name = $name;
    $this->label = $label;
    $this->attributes = $attributes;
    $this->id = $id;
    $this->parent = null;

    $this->validators = array ();
    $this->transformators = array ();
    $this->needFullColumn(false);
    $this->activateSave(true);

    $this->setValueManager(new rex_simpleValueManager($this));
  }

  function setValueManager($valueManager)
  {
    $this->valueManager = $valueManager;
  }

  function &getParent()
  {
    return $this->parent;
  }

  function getRawName()
  {
    return $this->name;
  }

  function getName()
  {
    $prefix = '';
    if(rexFormSection::isValid($this->getParent()))
    {
      $section =& $this->getSection();
      $prefix = 's'. $section->getId() .'_';
    }
    return $prefix . $this->getRawName();
  }

  function getLabel()
  {
    return $this->label;
  }

  function setValue($value)
  {
    $this->value = $value;
  }

  function stripslashes($value)
  {
    if (is_array($value))
    {
      for ($i = 0; $i < count($value); $i++)
      {
        $value[$i] = stripslashes($value[$i]);
      }
    }
    else
    {
      $value = stripslashes($value);
    }

    return $value;
  }

  /**
   * Schalter, um die Speicherfunktion dieses Feldes zu aktivieren/deaktvieren.
   * In der Grundeinstellung zeigt das readOnlyField die Werte nur an,
   * speichert diese beim save() aber nicht in die DB.
   *
   * @param $activate_save boolean true/false Speicherfunktion aktiviert/deaktiviert
   */
  function activateSave($activateSave)
  {
    rex_valid_type($activateSave, array( 'boolean'), __FILE__, __LINE__);

    $this->activateSave = $activateSave;
  }

  function _getInsertValue()
  {
    // null zurückgeben, damit der Wert nicht im SQL auftaucht
    $value = null;

    // Werte auf den Insert vorbereiten
    // Aktuell nur den Wert zurückgeben, da die Werte via magic_quotes escaped werden!
    if ($this->activateSave === true)
      $value = $this->_getValue();

    return $value;
  }

  function getInsertValue()
  {
    return $this->valueManager->getInsertValue($this->_getInsertValue());
  }

  function getUserValue($default = '')
  {
    // Werte vom User gesetzt?
    if ($this->value != '')
      return $this->value;

    return $default;
  }

  function getDataSetValue($default = '')
  {
    // Werte aus der DB
    $section = & $this->getSection();
    $dataset = $section->_getDataSet();

    $name = $this->getRawName();
    if (isset ($dataset[$name]))
      return $dataset[$name];

    return $default;
  }

  function getPostValue($default = '')
  {
    $name = $this->getName();
    if (isset ($_POST[$name]))
      return $_POST[$name];

    return $default;
  }

  function _getValue()
  {
    $postValue = $this->getPostValue(null);

    // Da beim Redraw des Formulars via mquotes slashes hinzugefügt wurden, diese entfernen!
    if($postValue !== null)
      return $this->stripslashes($postValue);

    // Werte vom User gesetzt?
    $userValue = $this->getUserValue(null);
    if($userValue !== null)
      return $userValue;

    // Werte aus der DB
    return $this->getDataSetValue();
  }

  function getValue()
  {
    return $this->valueManager->getValue($this->_getValue());
  }

  function & getSection()
  {
    return $this->rexsection;
  }

  function getId()
  {
    $id = $this->id;

    if ($id == '')
    {
//      $section = & $this->getSection();
//      $id = strtolower($section->getId().'_'.$this->getName());
      $id = strtolower($this->getName());
    }

    return $id;
  }

  function addAttribute($tag_name, $tag_value, $overwrite = true)
  {
    if ($overwrite === false && $this->hasAttribute($tag_name))
      return;

    $this->attributes[$tag_name] = $tag_value;
  }

  function getAttribute($tag_name)
  {
    return $this->attributes[$tag_name];
  }

  function hasAttribute($tag_name)
  {
    return array_key_exists($tag_name, $this->attributes);
  }

  function & _getAttributes()
  {
    return $this->attributes;
  }

  function getAttributes()
  {
    $s = '';
    $attributes = $this->_getAttributes();

    $section =& $this->getSection();
    $form =& $section->getForm();

    $isInvalid = false;
    if(!empty($_POST))
    {
      // falls das Feld nicht gültig ist, CSS Klasse "invalid" zuweisen
      $failed_fields = rexValidateEngine::_failed_fields($_POST, $form->getName());
      if(is_array($failed_fields) && in_array($this->getName(), $failed_fields))
      {
        $isInvalid = true;
        if(empty($attributes['class']))
        {
          $attributes['class'] = 'invalid';
        }
        else
        {
          $attributes['class'] = $attributes['class'] .' invalid';
        }
      }
    }

    // Falls das Feld valide ist, Pflichtfelder markieren
    if(!$isInvalid && $this->isRequired())
    {
      if(empty($attributes['class']))
      {
        $attributes['class'] = 'required';
      }
      else
      {
        $attributes['class'] = $attributes['class'] .' required';
      }
    }

    // Attribute zu String umwandeln
    if (is_array($attributes))
    {
      foreach ($attributes as $attr_name => $attr_value)
      {
        $s .= ' '.$attr_name.'="'.$attr_value.'"';
      }
    }

    return $s;
  }

  function isValid($field)
  {
    return is_object($field) && is_a($field, 'rexformfield');
  }

  function needFullColumn($needFullColumn = null)
  {
    if($needFullColumn !== null)
    {
      $this->needFullColumn = $needFullColumn;
    }
    return $this->needFullColumn;
  }

  function registerValidators()
  {
    $section = & $this->getSection();
    $form = & $section->getForm();
    $validators = $this->getValidators();

    for ($i = 0; $i < count($validators); $i++)
    {
      $validator = & $validators[$i];

      if(($pos = strpos($validator['criteria'],':')) === false)
      {
        // Validierung ohne Parameter
        rexValidateEngine :: register_validator($this->_getValidatorId($validator['criteria']), $this->getName(), $validator['criteria'], $validator['empty'], $validator['halt'], $validator['transform'], $form->getName());
      }
      else
      {
        // validierung mit Parametern
        rexValidateEngine :: register_validator($this->_getValidatorId($validator['criteria']), $this->getName().substr($validator['criteria'],$pos), substr($validator['criteria'],0,$pos), $validator['empty'], $validator['halt'], $validator['transform'], $form->getName());
      }
    }
  }

  function activateValidators()
  {
    $section = & $this->getSection();
    $form = & $section->getForm();
    $formValidator = & $form->getValidator();

    $validators = $this->getValidators();
    for ($i = 0; $i < count($validators); $i++)
    {
      $validator = & $validators[$i];
//      var_dump('validation_errors_'.$form->getName().'_'.$section->getTableName().'::'.$section->getLabel());
      $params = array (
        'id' => $this->_getValidatorId($validator['criteria']),
        'message' => $validator['message'],
        'form' => $form->getName(),
        'append' => 'validation_errors_'.$form->getName().'_'.$section->getTableName().'::'.$section->getId(),
        'halt' => $validator['halt']
      );
      // validierung starten
      smarty_function_validate($params, $formValidator);
    }
  }

  function _getValidatorId($criteria)
  {
    $section = & $this->getSection();
    return $section->getId().'->'.$this->getName().'['.$criteria.']';
  }

  function getValidators()
  {
    return $this->validators;
  }

  /**
   * Fügt einen Validator hinzu
   *
   * @param $criteria
   * "criteria" determines the rule, which is used for validation.
   * It is possible to add paramters with colon separated:
   *  e.g. isDate:year:month:day with transform makeDate
   *
   * @param $empty
   * "empty" determines if the field is allowed to be empty or not. If
   * allowed, the validation will be skipped when the field is empty.
   * Note this is ignored with the "notEmpty" criteria.
   *
   * @param $halt
   * If the validator fails, "halt" determines if any remaining validators for
   * this form will be processed. If "halt" is yes, validation will stop at this point.
   *
   * @param $transform
   * "transform" is used to apply a transformation to a form value prior to
   * validation. For instance, you may want to trim off extra whitespace from
   * the form value before validating.
   */
  function addValidator($criteria, $message, $empty = false, $halt = false, $transform = null)
  {
    $this->validators[] = array (
      'criteria' => $criteria,
      'message' => $message,
      'empty' => $empty,
      'halt' => $halt,
      'transform' => $transform
    );
  }

  function hasValidator()
  {
    return count($this->getValidators()) > 0;
  }

  /**
   * Prüft, ob das Feld ein Pflichfeld ist
   */
  function isRequired()
  {
    foreach($this->getValidators() as $validator)
    {
      if($validator['criteria'] == 'notEmpty')
        return true;
      else if ($validator['empty'] === false)
        return true;
    }

    return false;
  }

  function get()
  {
    return '';
  }

  function show()
  {
    echo $this->get();
  }

  function toString()
  {
    $section_str = 'null';
    $section = & $this->getSection();
    if ($section !== null)
    {
      $section_str = $section->toString();
    }
    return 'rexFormField: type: "'.get_class($this).'", name: "'.$this->getName().'", rawName: "'.$this->getRawName().'", label: "'.$this->getLabel().'", section: "{'.$section_str.'}"';
  }
}

// Field Klassen einbinden
$FieldBasedir = dirname(__FILE__);

// 3rd Party Klassen
require_once $FieldBasedir.'/validate/SmartyValidate.class.php';
require_once $FieldBasedir.'/validate/internals/core.assemble_plugin_filepath.php';
require_once $FieldBasedir.'/validate/plugins/function.validate.php';

// Validierung
require_once $FieldBasedir.'/class.rex_validator.inc.php';
require_once $FieldBasedir.'/validate/rex_ValidateEngine.inc.class.php';

// Value Managers
require_once $FieldBasedir.'/values/class.rex_valueManager.inc.php';
require_once $FieldBasedir.'/values/class.rex_simpleValueManager.inc.php';
require_once $FieldBasedir.'/values/class.rex_multiValueManager.inc.php';
require_once $FieldBasedir.'/values/class.rex_internalMultiValueManager.inc.php';
require_once $FieldBasedir.'/values/class.rex_externalMultiValueManager.inc.php';

// Allgemeine Field-Klassen
require_once $FieldBasedir.'/fields/field.textField.inc.php';
require_once $FieldBasedir.'/fields/field.passwordField.inc.php';
require_once $FieldBasedir.'/fields/field.textAreaField.inc.php';
require_once $FieldBasedir.'/fields/field.buttonField.inc.php';
require_once $FieldBasedir.'/fields/field.saveField.inc.php';
require_once $FieldBasedir.'/fields/field.hiddenField.inc.php';
require_once $FieldBasedir.'/fields/field.readOnlyField.inc.php';
require_once $FieldBasedir.'/fields/field.readOnlyTextField.inc.php';
require_once $FieldBasedir.'/fields/field.foreignField.inc.php';
require_once $FieldBasedir.'/fields/field.popupButtonField.inc.php';
require_once $FieldBasedir.'/fields/field.fieldsetField.inc.php';
require_once $FieldBasedir.'/fields/field.captchaField.inc.php';
require_once $FieldBasedir.'/fields/field.dateField.inc.php';
require_once $FieldBasedir.'/fields/field.wysiwygDateField.inc.php';
require_once $FieldBasedir.'/fields/field.htmlField.inc.php';

// MultiValue Field-Klassen
require_once $FieldBasedir.'/class.rex_formMultiValueField.inc.php';
require_once $FieldBasedir.'/fields/field.checkboxField.inc.php';
require_once $FieldBasedir.'/fields/field.radioField.inc.php';
require_once $FieldBasedir.'/fields/field.selectField.inc.php';

// Redaxo Field-Klassen
require_once $FieldBasedir.'/fields/rex/field.rexSaveField.inc.php';
require_once $FieldBasedir.'/fields/rex/field.rexLinkButtonField.inc.php';
require_once $FieldBasedir.'/fields/rex/field.rexMediaButtonField.inc.php';
?>