<?php defined('_JEXEC') or die('Restricted access');
JRequest::setVar('hidemainmenu', 1);
if ($this->filename) 
{
	JToolbarHelper::title(JText::_('COM_LOCALISE_LOCALISATION_MANAGER') . ' - ' . JText::_('COM_LOCALISE_EDIT_PACKAGE'), 'langmanager.png');
}
else
{
	JToolbarHelper::title(JText::_('COM_LOCALISE_LOCALISATION_MANAGER') . ' - ' . JText::_('COM_LOCALISE_NEW_PACKAGE'), 'langmanager.png');
}
JToolbarHelper::save('package.save');
JToolbarHelper::apply('package.apply');
JToolbarHelper::cancel('package.cancel');
JToolBarHelper::divider();
JToolBarHelper::help('screen.package', true);
