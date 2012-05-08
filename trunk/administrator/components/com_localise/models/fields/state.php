<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: localise.php 151 2009-12-30 12:56:12Z chdemko $
 * @copyright   Copyright (C) 2009 - today Localise Team. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
jimport('joomla.form.formfield');

/**
 * Form Field State class.
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class JFormFieldState extends JFormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'State';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput() 
	{
		$attributes = '';
		if ($v = (string)$this->element['onchange']) 
		{
			$attributes.= ' onchange="' . $v . '"';
		}
		$attributes.= ' class="localise-icon icon-16-' . $this->value . ' ' . $this->value . '"';
		$options = array();
		foreach ($this->element->children() as $option) 
		{
			$options[] = JHtml::_('select.option', $option->attributes('value'), JText::_(trim($option->data())), array('option.attr' => 'attributes', 'attr' => 'class="localise-icon inlanguage"'));
		}
		$options[] = JHtml::_('select.option', 'inlanguage', JText::sprintf('COM_LOCALISE_OPTION_TRANSLATIONS_STATE_INLANGUAGE'), array('option.attr' => 'attributes', 'attr' => 'class="localise-icon icon-16-inlanguage inlanguage"'));
		$options[] = JHtml::_('select.option', 'unexisting', JText::sprintf('COM_LOCALISE_OPTION_TRANSLATIONS_STATE_UNEXISTING'), array('option.attr' => 'attributes', 'attr' => 'class="localise-icon icon-16-unexisting unexisting"'));
		$options[] = JHtml::_('select.option', 'notinreference', JText::sprintf('COM_LOCALISE_OPTION_TRANSLATIONS_STATE_NOTINREFERENCE'), array('option.attr' => 'attributes', 'attr' => 'class="localise-icon icon-16-notinreference notinreference"'));
		$options[] = JHtml::_('select.option', 'error', JText::sprintf('COM_LOCALISE_OPTION_TRANSLATIONS_STATE_ERROR'), array('option.attr' => 'attributes', 'attr' => 'class="localise-icon icon-16-error error"'));
		$return = JHtml::_('select.genericlist', $options, $this->name, array('id' => $this->id, 'list.select' => $this->value, 'option.attr' => 'attributes', 'list.attr' => $attributes));
		return $return;
	}
}
