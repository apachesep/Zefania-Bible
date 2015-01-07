<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for dictionary.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelDictionary extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.dict_id', 'dict_id','ordering', 'state', 'item'
			);
		}
		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 'dict_id', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		// Set filter state for dict_id
		$dict_id = $this->getUserStateFromRequest($this->context.'.filter.dict_id', 'filter_dict_id', '');
		$this->setState('filter.dict_id', $dict_id);
				// Set filter state for item
		$item = $this->getUserStateFromRequest($this->context.'.filter.item', 'filter_item', '');
		$this->setState('filter.item', $item);
		

		// Load the parameters.
		$params = JComponentHelper::getParams('com_zefaniabible');
		$this->setState('params', $params);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Get database object
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('a.*')->from('#__zefaniabible_dictionary_detail AS a');				
		

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'dict_id:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('dict_id:')), true) . '%');
				$query->where('(a.dict_id LIKE ' . $search);
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('a.item LIKE' . $s );
			}
		}
		// Filter by dict_id
		$dict_id = $this->getState('filter.dict_id');
		if ($dict_id != "")
		{
			$query->where('a.dict_id = ' . $db->quote($db->escape($dict_id)));
		}

		// Filter by item
		$item = $this->getState('filter.item');
		if ($item != "")
		{
			$query->where('a.item = ' . $db->quote($db->escape($item)));
		}


		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'dict_id');
		$order = $this->getState('list.direction', 'ASC');
		$query->order($db->escape($sort).' '.$db->escape($order));
		
		return $query;
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		if ($items = parent::getItems()) {
			//Do any procesing on fields here if needed
		}

		return $items;
	}
}
?>