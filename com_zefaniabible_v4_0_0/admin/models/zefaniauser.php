<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for zefaniauser.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelZefaniauser extends JModelList
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
				'a.user_name', 'user_name','ordering', 'state', 'user_name', 'user_id', 'email', 'send_reading_plan_email', 'send_verse_of_day_email', 'reading_start_date'
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
	protected function populateState($ordering = 'user_name', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		// Set filter state for send_reading_plan_email
		$send_reading_plan_email = $this->getUserStateFromRequest($this->context.'.filter.send_reading_plan_email', 'filter_send_reading_plan_email', '');
		$this->setState('filter.send_reading_plan_email', $send_reading_plan_email);
				// Set filter state for send_verse_of_day_email
		$send_verse_of_day_email = $this->getUserStateFromRequest($this->context.'.filter.send_verse_of_day_email', 'filter_send_verse_of_day_email', '');
		$this->setState('filter.send_verse_of_day_email', $send_verse_of_day_email);
				// Set filter state for reading_start_date
		$reading_start_date = $this->getUserStateFromRequest($this->context.'.filter.reading_start_date', 'filter_reading_start_date', '');
		$this->setState('filter.reading_start_date', $reading_start_date);
		

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
		$query->select('a.*')->from('#__zefaniabible_zefaniauser AS a');				
		

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'user_name:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('user_name:')), true) . '%');
				$query->where('(a.user_name LIKE ' . $search);
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('a.user_id LIKE' . $s . ' OR a.email LIKE' . $s . ' OR a.send_reading_plan_email LIKE' . $s . ' OR a.send_verse_of_day_email LIKE' . $s . ' OR a.reading_start_date LIKE' . $s );
			}
		}
		// Filter by send_reading_plan_email
		$send_reading_plan_email = $this->getState('filter.send_reading_plan_email');
		if ($send_reading_plan_email != "")
		{
			$query->where('a.send_reading_plan_email = ' . $db->quote($db->escape($send_reading_plan_email)));
		}

		// Filter by send_verse_of_day_email
		$send_verse_of_day_email = $this->getState('filter.send_verse_of_day_email');
		if ($send_verse_of_day_email != "")
		{
			$query->where('a.send_verse_of_day_email = ' . $db->quote($db->escape($send_verse_of_day_email)));
		}

		// Filter by reading_start_date
		$reading_start_date = $this->getState('filter.reading_start_date');
		if ($reading_start_date != "")
		{
			$query->where('a.reading_start_date = ' . $db->quote($db->escape($reading_start_date)));
		}


		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'user_name');
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