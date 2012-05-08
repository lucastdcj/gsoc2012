<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: view.html.php 251 2011-04-13 17:57:05Z chdemko $
 * @copyright	Copyright (C) 2009 - today Localise Team, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.application.component.view');

/**
 * Packages View class for the Localise component
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class LocaliseViewPackages extends JView
{
	protected $items;
	protected $pagination;
	protected $form;
	protected $state;
	function display($tpl = null) 
	{
		// Get the data
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		$state = $this->get('State');
		$form = $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign the data
		$this->items = $items;
		$this->state = $state;
		$this->pagination = $pagination;
		$this->form = $form;

		// Set the toolbar
		$this->addToolbar();

		// Prepare the document
		$this->prepareDocument();

		// Display the view
		parent::display($tpl);

		// Set the document
		$this->prepareDocument();
	}
	protected function addToolbar() 
	{
		$canDo = LocaliseHelper::getActions();
		JToolBarHelper::title(JText::sprintf('COM_LOCALISE_HEADER_MANAGER', JText::_('COM_LOCALISE_HEADER_PACKAGES')), 'install');
		if ($canDo->get('localise.create')) 
		{
			JToolbarHelper::addNew('package.add');
		}
		if ($canDo->get('localise.edit')) 
		{
			JToolbarHelper::editListX('package.edit');
		}
		if ($canDo->get('localise.create') || $canDo->get('localise.edit')) 
		{
			JToolbarHelper::divider();
		}
		if ($canDo->get('localise.delete')) 
		{
			JToolbarHelper::deleteListX('COM_LOCALISE_MSG_PACKAGES_VALID_DELETE', 'packages.delete');
			JToolBarHelper::divider();
		}
		JToolBarHelper::custom('package.download', 'export.png', 'export.png', 'JTOOLBAR_EXPORT', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('package.language', 'archive.png', 'archive.png', 'COM_LOCALISE_TOOLBAR_PACKAGES_LANGUAGE', true);
		JToolbarHelper::divider();
		if ($canDo->get('package.batch')) 
		{
			JToolBarHelper::custom('package.batch', 'refresh.png', 'refresh.png', 'COM_LOCALISE_TOOLBAR_PACKAGES_BATCH', true);
			JToolbarHelper::divider();
		}
		if ($canDo->get('core.admin')) 
		{
			JToolbarHelper::preferences('com_localise');
			JToolbarHelper::divider();
		}
		JToolBarHelper::help('screen.packages', true);
	}
	protected function prepareDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::sprintf('COM_LOCALISE_TITLE', JText::_('COM_LOCALISE_TITLE_PACKAGES')));

		// " onclick="javascript:if (document.adminForm.boxchecked.value==0){alert(\'Please make a selection from the list to export\');alert(\'coucou\'); alert(this.get(\'href\'));this.set(\'href\',\'essai\')}else{this.set(\'href\',\'essai\');}
		
	}

	/*	protected function prepareDocument()
	{
	$document = & JFactory::getDocument();
	$document->setTitle(JText::sprintf('COM_LOCALISE_TITLE', JText::_('COM_LOCALISE_TITLE_PACKAGES')));
	$document->addStyleDeclaration(".icon-32-language { background-image: url(components/com_localise/assets/images/icon-32-language.png); }");
	$document->addScriptDeclaration("
	window.addEvent('domready', function () {
	$('packages_form_core').onchange=
	function () {
	submitbutton('packages.display');
	}
	$('packages_form_home').onchange=
	function () {
	submitbutton('packages.display');
	}
	$('packages_form_thirdparty').onchange=
	function () {
	submitbutton('packages.display');
	}
	});
	");
	}*/
}
