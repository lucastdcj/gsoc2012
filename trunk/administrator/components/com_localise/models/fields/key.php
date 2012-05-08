<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: localise.php 151 2009-12-30 12:56:12Z chdemko $
 * @copyright   Copyright (C) 2009 - today Localise Team. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
jimport('joomla.form.formfield');

/**
 * Form Field Key class.
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class JFormFieldKey extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Key';

	/**
	 * Method to get the field label.
	 *
	 * @return	string		The field label.
	 */

	/**
	 * Method to get the field label markup.
	 *
	 * @return	string	The field label markup.
	 * @since	1.6
	 */
	protected function getLabel() 
	{
		return '<label id="' . $this->id . '-lbl" for="' . $this->id . '">' . $this->element['label'] . '</label>';
	}

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput() 
	{
		// Set the class for the label.
		$class = !empty($this->descText) ? 'key-label hasTip fltrt' : 'key-label fltrt';

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->descText)) 
		{
			$label = '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '" title="' . htmlspecialchars(htmlspecialchars('::' . str_replace("\n", "\\n", $this->descText), ENT_QUOTES, 'UTF-8')) . '">';
		}
		else
		{
			$label = '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '">';
		}
		JText::script('COM_LOCALISE_LABEL_TRANSLATION_GOOGLE_ERROR');
		$label.= $this->element['label'] . 'br />' . $this->element['description'];
		$label.= '</label>';
		$status = (string)$this->element['status'];
		if ($status == 'extra') 
		{
			$onclick = '';
			$button = '<span style="width:5%;">' . JHtml::_('image', 'com_localise/icon-16-arrow-gray.png', '', array(), true) . '</span>';
			$onclick2 = '';
			$button2 = '<span style="width:5%;">' . JHtml::_('image', 'com_localise/icon-16-google-gray.png', '', array(), true) . '</span>';
		}
		else
		{
			$onclick = "javascript:document.id('" . $this->id . "').set('value','" . addslashes(htmlspecialchars($this->element['description'], ENT_COMPAT, 'UTF-8')) . "');if (document.id('" . $this->id . "').get('value')=='') {document.id('" . $this->id . "').set('class','width-45 untranslated');} else {document.id('" . $this->id . "').set('class','width-45 " . ($status=='untranslated' ? 'unchanged' : $status) . "');}";
			$button = '<span style="width:5%;">' . JHtml::_('image', 'com_localise/icon-16-arrow.png', '', array('title' => JText::_('COM_LOCALISE_TOOLTIP_TRANSLATION_INSERT'), 'class' => 'hasTip pointer', 'onclick' => $onclick), true) . '</span>';
			$onclick2 = "javascript:if (typeof(google) !== 'undefined') {
	var translation='" . addslashes(htmlspecialchars($this->element['description'], ENT_COMPAT, 'UTF-8')) . "';translation=translation.replace('%s','___s');translation=translation.replace('%d','___d');translation=translation.replace(/%([0-9]+)\\\$s/,'___\$1');google.language.translate(translation, Localise.language_src, Localise.language_dest, function(result) {if (result.translation) {
			translation = result.translation;
			translation = translation.replace('___s','%s');
			translation = translation.replace('___d','%d');
			translation = translation.replace(/___([0-9]+)/,'%$1\$s');
			document.id('" . $this->id . "').set('value',translation);
			if (document.id('" . $this->id . "').get('value')=='" . addslashes(htmlspecialchars($this->element['description'], ENT_COMPAT, 'UTF-8')) . "') document.id('" . $this->id . "').set('class','width-45 unchanged'); else document.id('" . $this->id . "').set('class','width-45 translated');}else alert(Joomla.JText._('COM_LOCALISE_LABEL_TRANSLATION_GOOGLE_ERROR'));});}else alert(Joomla.JText._('COM_LOCALISE_LABEL_TRANSLATION_GOOGLE_ERROR'));";
			$button2 = '<span style="width:5%;">' . JHtml::_('image', 'com_localise/icon-16-google.png', '', array('title' => JText::_('COM_LOCALISE_TOOLTIP_TRANSLATION_GOOGLE'), 'class' => 'hasTip pointer', 'onclick' => $onclick2), true) . '</span>';
		}
		$onkeyup = "javascript:";
		$onkeyup.= "if (this.get('value')=='') {this.set('class','width-45 untranslated');} else {if (this.get('value')=='" . addslashes(htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8')) . "') this.set('class','width-45 " . $status . "'); " . ($status == 'extra' ? "else this.set('class','width-45 extra');}" : "else this.set('class','width-45 translated');}");
		$input = '<textarea name="' . $this->name . '" id="' . $this->id . '" class="width-45 ' . ($this->value == '' ? 'untranslated' : ($this->value == $this->element['description'] ? $status : 'translated')) . '" onkeyup="' . $onkeyup . '">' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		return $button . $button2 . $input; //		.$button.$label;
		$input = '<textarea name="' . $this->name . '" id="' . $this->id . '" class="width-45 ' . ($this->value == '' ? 'untranslated' : ($this->value == $this->element['description'] ? $status : 'translated')) . '">' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '</textarea>';
		return $input; //		.$button.$label;

		
	}
}
