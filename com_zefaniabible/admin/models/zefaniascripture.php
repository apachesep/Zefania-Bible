<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for zefaniascripture.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelZefaniascripture extends JModelList
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
				'a.bible_id', 'bible_id',
				'a.id', 'id',
				'a.verse', 'verse',				
				'a.ordering', 'ordering',
				'a.state', 'state',
				'a.bible_id', 'bible_id', 
				'a.book_id', 'book_id', 
				'a.chapter_id', 'chapter_id',
				'a.verse_id', 'verse_id'
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
	protected function populateState($ordering = 'bible_id', $direction = 'ASC')
	{
		// Get the Application
		$app = JFactory::getApplication();
		
		// Set filter state for search
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		
		// Set filter state for bible_id
		$bible_id = $this->getUserStateFromRequest($this->context.'.filter.bible_id', 'filter_bible_id', '');
		$this->setState('filter.bible_id', $bible_id);			
		
		// Set filter state for book_id
		$book_id = $this->getUserStateFromRequest($this->context.'.filter.book_id', 'filter_book_id', '');
		$this->setState('filter.book_id', $book_id);	

		// Set filter state for chapter_id
		$chapter_id = $this->getUserStateFromRequest($this->context.'.filter.chapter_id', 'filter_chapter_id', '');
		$this->setState('filter.chapter_id', $chapter_id);
		
		// Set filter state for verse_id
		$verse_id = $this->getUserStateFromRequest($this->context.'.filter.verse_id', 'filter_verse_id', '');
		$this->setState('filter.verse_id', $verse_id);

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
		$query->select('a.*')->from('#__zefaniabible_bible_text AS a');				
				
		// Filter by search
		$search = $this->getState('filter.search');
		$s = $db->quote('%'.$db->escape($search, true).'%');
		
		if (!empty($search))
		{
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/zefaniabible.php');
			$mdl_zefaniabibleHelper = new ZefaniabibleHelper;
			$item = new stdClass();
			$item = $mdl_zefaniabibleHelper->fncParseScritpure($search);
			
			$item->int_book_id_clean		= $db->quote($item->int_book_id);
			$item->int_begin_chapter_clean 	= $db->quote($item->int_begin_chapter);
			$item->int_end_chapter_clean	= $db->quote($item->int_end_chapter);
			$item->int_verse_clean			= $db->quote($item->int_verse);
			
			$query->where("a.book_id=".$item->int_book_id_clean);
			if($item->int_begin_chapter > '0')
			{
				if($item->int_end_chapter == '0')
				{
					$query->where("a.chapter_id=".$item->int_begin_chapter_clean);
					if($item->int_verse > 0)
					{
						$query->where("a.verse_id=".$item->int_verse_clean);
					}
				}
				else
				{
 					$query->where("a.chapter_id>=".$item->int_begin_chapter_clean." AND a.chapter_id<=".$item->int_end_chapter_clean);					
				}
			}
		}
		
		// Filter by book_id
		$bible_id = $this->getState('filter.bible_id');
		if ($bible_id != "")
		{
			$query->where('a.bible_id = ' . $db->quote($db->escape($bible_id)));
		}		
		// Filter by book_id
		$book_id = $this->getState('filter.book_id');
		if ($book_id != "")
		{
			$query->where('a.book_id = ' . $db->quote($db->escape($book_id)));
		}
		// Filter by chapter_id
		$chapter_id = $this->getState('filter.chapter_id');
		if ($chapter_id != "")
		{
			$query->where('a.chapter_id = ' . $db->quote($db->escape($chapter_id)));
		}			
		// Filter by verse_id
		$verse_id = $this->getState('filter.verse_id');
		if ($verse_id != "")
		{
			$query->where('a.verse_id = ' . $db->quote($db->escape($verse_id)));
		}


		// Add list oredring and list direction to SQL query
		$sort = $this->getState('list.ordering', 'bible_id');
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