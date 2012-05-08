<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: view.html.php 189 2010-02-21 05:11:04Z chdemko $
 * @copyright   Copyright (C) 2009 - today Localise Team. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
jimport('joomla.application.component.view');

/**
 * Package View class for the Localise component
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class LocaliseViewPackage extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null) 
	{
		jimport('joomla.client.helper');

		// Get the data
		$state = $this->get('State');
		$item = $this->get('Item');
		$form = $this->get('Form');
		$formftp = $this->get('FormFtp');
		$ftp = JClientHelper::setCredentialsFromRequest('ftp');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Assign the data
		$this->state = $state;
		$this->item = $item;
		$this->form = $form;
		$this->formftp = $formftp;
		$this->ftp = $ftp;

		// Set the toolbar
		$this->addToolbar();

		// Prepare the document
		$this->prepareDocument();

		// Display the view
		parent::display($tpl);
	}
	protected function addToolbar() 
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$isNew = empty($this->item->id);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		JToolbarHelper::title(JText::sprintf('COM_LOCALISE_HEADER_MANAGER', $isNew ? JText::_('COM_LOCALISE_HEADER_PACKAGE_NEW') : JText::_('COM_LOCALISE_HEADER_PACKAGE_EDIT')), 'langmanager.png');

		// If not checked out, can save the item.
		if (!$checkedOut /*&& $this->item->standalone*/

		) 
		{
			JToolbarHelper::apply('package.apply', 'JToolbar_Apply');
			JToolbarHelper::save('package.save', 'JToolbar_Save');
		}
		JToolBarHelper::cancel("package.cancel", $isNew ? 'JToolbar_Cancel' : 'JToolbar_Close');
		JToolBarHelper::divider();
		JToolBarHelper::help('screen.package', true);
	}
	protected function prepareDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::sprintf('COM_LOCALISE_TITLE', JText::_('COM_LOCALISE_TITLE_PACKAGE')));
	}
}
