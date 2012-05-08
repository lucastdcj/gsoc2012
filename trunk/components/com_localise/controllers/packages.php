<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: packages.php 251 2011-04-13 17:57:05Z chdemko $
 * @copyright   Copyright (C) 2009 - today Localise Team. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');

/**
 * Packages Controller class for the Localise component
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class LocaliseControllerPackages extends JController
{
	/**
	 * Proxy for getModel.
	 */
	public function getModel($name = 'Packages', $prefix = 'LocaliseModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function display($cachable = false) 
	{
		JRequest::setVar('view', 'packages');
		parent::display($cachable);
	}
	public function delete() 
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialise variables.
		$user = JFactory::getUser();
		$ids = JRequest::getVar('cid', array(), '', 'array');

		// Access checks.
		foreach ($ids as $i => $package) 
		{
			$id = LocaliseHelper::getFileId(JPATH_ROOT . "/media/com_localise/packages/$package.xml");
			$model = $this->getModel('Package');
			$model->setState('package.id', $id);
			$item = $model->getItem();
			if (!$item->standalone) 
			{
				// Prune items that you can't delete.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('COM_LOCALISE_ERROR_PACKAGES_DELETE'));
			}
			if (!$user->authorise('core.delete', 'com_localise.' . (int)$id)) 
			{
				// Prune items that you can't delete.
				unset($ids[$i]);
				JError::raiseNotice(403, JText::_('JError_Core_Delete_not_permitted'));
			}
		}
		if (empty($ids)) 
		{
			$msg = JText::_('JError_No_items_selected');
			$type = 'error';
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Remove the items.
			if (!$model->delete($ids)) 
			{
				$msg = implode("<br />", $model->getErrors());
				$type = 'error';
			}
			else
			{
				$msg = JText::sprintf('JController_N_Items_deleted', count($ids));
				$type = 'message';
			}
		}
		$this->setRedirect(JRoute::_('index.php?option=com_localise&view=packages', false), $msg, $type);
	}
}
