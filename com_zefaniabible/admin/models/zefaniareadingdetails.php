<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for zefaniareadingdetails.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelZefaniareadingdetails extends JModelList
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
				'a.plan', 'plan',
				'a.plan','plan_name',
				'a.book_id','book_id',
				'a.day_number','day_number',
				'a.begin_chapter','begin_chapter',
				'a.end_chapter','end_chapter',
				'a.ordering', 'ordering','ordering', 'state', 'begin_chapter'
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
	protected function populateState($ordering = 'plan', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		
		// Set filter state for day_number
		$day_number = $this->getUserStateFromRequest($this->context.'.filter.day_number', 'filter_day_number', '');
		$this->setState('filter.day_number', $day_number);

		// Set filter state for plan_name
		$plan_name = $this->getUserStateFromRequest($this->context.'.filter.plan_name', 'filter_plan_name', '');
		$this->setState('filter.plan_name', $plan_name);	

		// Set filter state for book_id
		$book_id = $this->getUserStateFromRequest($this->context.'.filter.book_id', 'filter_book_id', '');
		$this->setState('filter.book_id', $book_id);			
	
		// Set filter state for begin_chapter
		$begin_chapter = $this->getUserStateFromRequest($this->context.'.filter.begin_chapter', 'filter_begin_chapter', '');
		$this->setState('filter.begin_chapter', $begin_chapter);

		// Set filter state for end_chapter
		$end_chapter = $this->getUserStateFromRequest($this->context.'.filter.end_chapter', 'filter_end_chapter', '');
		$this->setState('filter.end_chapter', $end_chapter);	
						
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
		$query->select('a.*')->from('#__zefaniabible_zefaniareadingdetails AS a');				
		

		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, strlen('id:')));
			}
			elseif (stripos($search, 'plan:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, strlen('plan:')), true) . '%');
				$query->where('(a.plan LIKE ' . $search);
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('a.day_number LIKE' . $s );
			}
		}
		// Filter by day_number
		$day_number = $this->getState('filter.day_number');
		if ($day_number != "")
		{
			$query->where('a.day_number = ' . $db->quote($db->escape($day_number)));
		}

		// Filter by plan_name
		$plan_name = $this->getState('filter.plan_name');
		if ($plan_name != "")
		{
			$query->where('a.plan = ' . $db->quote($db->escape($plan_name)));
		}	
				
		// Filter by book_id
		$book_id = $this->getState('filter.book_id');
		if ($book_id != "")
		{
			$query->where('a.book_id = ' . $db->quote($db->escape($book_id)));
		}		
		
		// Filter by begin_chapter
		$begin_chapter = $this->getState('filter.begin_chapter');
		if ($begin_chapter != "")
		{
			$query->where('a.begin_chapter = ' . $db->quote($db->escape($begin_chapter)));
		}		
		
		// Filter by end_chapter
		$end_chapter = $this->getState('filter.end_chapter');
		if ($end_chapter != "")
		{
			$query->where('a.end_chapter = ' . $db->quote($db->escape($end_chapter)));
		}

		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'plan');
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