<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: languages.php 251 2011-04-13 17:57:05Z chdemko $
 * @copyright   Copyright (C) 2009 - today Localise Team. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.modellist');

/**
 * Languages Model class for the Localise component
 *
 * @package		Extensions.Components
 * @subpackage	Localise
 */
class LocaliseModelLanguages extends JModelList
{
	protected $filter_fields = array('tag', 'client');
	protected $context = 'com_localise.languages';
	protected $items;
	protected $languages;

	/**
	 * Autopopulate the model
	 */
	protected function populateState() 
	{
		$app = JFactory::getApplication('administrator');
		$data = JRequest::getVar('filters');
		if (empty($data)) 
		{
			$data = array();
			$data['select'] = $app->getUserState('com_localise.select');
			$data['search'] = $app->getUserState('com_localise.languages.search');
		}
		else
		{
			$app->setUserState('com_localise.select', $data['select']);
			$app->setUserState('com_localise.languages.search', $data['search']);
		}
		$this->setState('filter.search', isset($data['search']['expr']) ? $data['search']['expr'] : '');
		$this->setState('filter.client', isset($data['select']['client']) ? $data['select']['client'] : '');
		$this->setState('filter.tag', isset($data['select']['tag']) ? $data['select']['tag'] : '');

		// Call auto-populate parent method
		parent::populateState('name', 'asc');
	}

	/**
	 * Method to get the row form.
	 *
	 * @return	mixed	JForm object on success, false on failure.
	 */
	public function getForm() 
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		jimport('joomla.form.form');
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		$form = JForm::getInstance('com_localise.languages', 'languages', array('control' => 'filters', 'event' => 'onPrepareForm'));

		// Check for an error.
		if (JError::isError($form)) 
		{
			$this->setError($form->getMessage());
			return false;
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_localise.select', array());

		// Bind the form data if present.
		if (!empty($data)) 
		{
			$form->bind(array('select' => $data));
		}

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_localise.languages.search', array());

		// Bind the form data if present.
		if (!empty($data)) 
		{
			$form->bind(array('search' => $data));
		}
		return $form;
	}

	/**
	 * Get the items (according the filters and the pagination)
	 *
	 * @return	array	array of object items
	 */
	public function getItems() 
	{
		if (!isset($this->items)) 
		{
			$languages = $this->getLanguages();
			$count = count($languages);
			$start = $this->getState('list.start');
			$limit = $this->getState('list.limit');
			if ($start > $count) 
			{
				$start = 0;
			}
			if ($limit == 0) 
			{
				$start = 0;
				$limit = null;
			}
			$this->items = array_slice($languages, $start, $limit);
			foreach ($this->items as $i => $item) 
			{
				$model = JModel::getInstance('Translations', 'LocaliseModel', array('ignore_request' => true));
				$model->setState('filter.tag', $item->tag);
				$model->setState('filter.client', $item->client);
				$this->items[$i]->nbfiles = $model->getTotalExist();
			}
		}
		return $this->items;
	}

	/**
	 * Get total number of languages (according to filters)
	 *
	 * @return	int	number of languages
	 */
	public function getTotal() 
	{
		return count($this->getLanguages());
	}

	/**
	 * Get all languages (according to filters)
	 *
	 * @return 	array	array of object items
	 */
	protected function getLanguages() 
	{
		if (!isset($this->languages)) 
		{
			$this->languages = array();
			$client = $this->getState('filter.client');
			$tag = $this->getState('filter.tag');
			$search = $this->getState('filter.search');
			if (empty($client)) 
			{
				$clients = array('site', 'administrator');
				if (LocaliseHelper::hasInstallation()) 
				{
					$clients[] = 'installation';
				}
			}
			else
			{
				$clients = array($client);
			}
			foreach ($clients as $client) 
			{
				if (empty($tag)) 
				{
					$folders = JFolder::folders(constant('LOCALISEPATH_' . strtoupper($client)) . '/language', '.', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'pdf_fonts', 'overrides'));
				}
				else
				{
					$folders = JFolder::folders(constant('LOCALISEPATH_' . strtoupper($client)) . '/language', '^' . $tag . '$', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'pdf_fonts', 'overrides'));
				}
				foreach ($folders as $folder) 
				{
					$model = JModel::getInstance('Language', 'LocaliseModel', array('ignore_request' => true));
					$id = LocaliseHelper::getFileId(constant('LOCALISEPATH_' . strtoupper($client)) . "/language/$folder/$folder.xml");
					$model->setState('language.tag', $folder);
					$model->setState('language.client', $client);
					$model->setState('language.id', $id);
					$language = $model->getItem();
					if (empty($search) || preg_match("/$search/i", $language->name)) 
					{
						$this->languages[] = $language;
					}
				}
			}
			$ordering = $this->getState('list.ordering') ? $this->getState('list.ordering') : 'name';
			JArrayHelper::sortObjects($this->languages, $ordering, $this->getState('list.direction') == 'desc' ? -1 : 1);
		}
		return $this->languages;
	}
}
