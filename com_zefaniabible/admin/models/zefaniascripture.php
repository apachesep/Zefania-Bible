<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.missionarychurchofgrace.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

require_once(JPATH_ADMIN_ZEFANIABIBLE .DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'jmodel.list.php');


/**
 * Zefaniabible Component Zefaniabible Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniascripture extends ZefaniabibleModelList
{
	var $_name_sing = 'zefaniascriptureitem';



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'_bible_version_title', '_bible_version_.bible_name',
				'book_id', 'a.book_id',
				'chapter_id', 'a.chapter_id',
				'verse_id', 'a.verse_id',
				'verse', 'a.verse',
			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'select_bible_book' => 'string'
				));

		parent::__construct($config);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 *
	 * @return	string		A store id.
	 * @since	1.6
	 */
	 
	protected function getStoreId($id = '')
	{
		// Compile the store id.


		return parent::getStoreId($id);
	}



	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Load the filter state.
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        	//Omit double (white-)spaces and set state
		$this->setState('filter.search', preg_replace('/\s+/',' ', $search));
 
		//Filter (dropdown) Bible Version
        $state = $this->getUserStateFromRequest($this->context.'.filter.bible_name', 'filter_bible_name', '', 'string');
		$this->setState('filter.bible_name', $state);
 
		//Filter (dropdown) Bible Book
        $state = $this->getUserStateFromRequest($this->context.'.filter.book_id', 'filter_book_id', '', 'string');
        $this->setState('filter.book_id', $state);
		
		//Filter (dropdown) Bible Chapter
        $state = $this->getUserStateFromRequest($this->context.'filter.chapter_id', 'filter_chapter_id', '', 'string');
        $this->setState('filter.chapter_id', $state);	
		
		//Filter (dropdown) Bible Chapter
        $state = $this->getUserStateFromRequest($this->context.'filter.verse_id', 'filter_verse_id', '', 'string');
        $this->setState('filter.verse_id', $state);			
 
		//Takes care of states: list. limit / start / ordering / direction
        parent::populateState('_bible_version_.bible_name', 'asc');
	}


	/**
	 * Method to build a the query string for the Zefaniabibleitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery_bible_versions()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('b.bible_name');
			$query->from('`#__zefaniabible_bible_names` AS b');
			$db->setQuery($query);
			$data = $db->loadObjectList();				
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function  _buildQuery_max_chapters($int_Bible_Book_ID)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(a.chapter_id)');
			$query->from('`#__zefaniabible_bible_text` AS a');	
			if($int_Bible_Book_ID)
			{
				$query->where('a.book_id='.$int_Bible_Book_ID);
			}
			$db->setQuery($query);
			$data = $db->loadResult();			
		}		
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	function  _buildQuery_max_verse($int_Bible_Book_ID,$int_Bible_Chapter)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(a.verse_id)');
			$query->from('`#__zefaniabible_bible_text` AS a');		
			if($int_Bible_Book_ID)
			{
				$query->where('a.book_id='.$int_Bible_Book_ID);
			}
			if($int_Bible_Chapter)
			{
				$query->where('a.chapter_id='.$int_Bible_Chapter);
			}
			$db->setQuery($query);
			$data = $db->loadResult();			
		}		
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}	
	 
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'default': return $this->_buildQuery_default(); break;

		}
		$query = ' SELECT a.*'

			. $this->_buildQuerySelect()

			. ' FROM `#__zefaniabible_bible_text` AS a '

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()

			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;
		return $query;
	}	
	function _buildQuery_default()
	{

		$query = ' SELECT a.*'
			.	' , _bible_version_.bible_name AS `_bible_version_title`'
			. $this->_buildQuerySelect()

			. ' FROM `#__zefaniabible_bible_text` AS a '
				.	' LEFT JOIN `#__zefaniabible_bible_names` AS _bible_version_ ON _bible_version_.id = a.bible_id'
			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;	
		return $query;
	}
	function _buildQueryWhere($where = array())
	{
		$app = JFactory::getApplication();
		$db= JFactory::getDBO();
		$acl = ZefaniabibleHelper::getAcl();

		if (isset($this->_active['filter']) && $this->_active['filter'])
		{
			$filter_bible_name = $this->getState('filter.bible_name');
			if ($filter_bible_name != '')		$where[] = "_bible_version_.bible_name = " . $db->Quote($filter_bible_name);			
			
			$filter_book_id = $this->getState('filter.book_id');
			if ($filter_book_id != '')		$where[] = "a.book_id = " . $db->Quote($filter_book_id);

			$filter_chapter_id = $this->getState('filter.chapter_id');
			if ($filter_chapter_id != '')		$where[] = "a.chapter_id = " . $db->Quote($filter_chapter_id);
			
			$filter_verse_id = $this->getState('filter.verse_id');
			if ($filter_verse_id != '')		$where[] = "a.verse_id = " . $db->Quote($filter_verse_id);			
			//search_search : search on  + Chapter Number +  + Book Name > Bible Book Name
			$search_search = $this->getState('filter.search');

			$this->_addSearch('search', 'a.verse', 'like');
			if (($search_search != '') && ($search_search_val = $this->_buildSearch('search', $search_search)))
				$where[] = $search_search_val;


		}
		return parent::_buildQueryWhere($where);
	}	

	function _buildQueryOrderBy($order = array(), $pre_order = 'a.book_id, a.chapter_id, a.verse_id')
	{

		return parent::_buildQueryOrderBy($order, $pre_order);
	}
	/**
	 * Method to Convert the parameter fields into objects.
	 *
	 * @access public
	 * @return void
	 */
	protected function populateParams()
	{

		parent::populateParams();
		$acl = ZefaniabibleHelper::getAcl();
		if (!isset($this->_data))
			return;

		// Convert the parameter fields into objects.
		foreach ($this->_data as &$item)
		{

			if ($acl->get('core.edit.state')
				|| (bool)$item->publish)
				$item->params->set('access-view', true);

			if ($acl->get('core.edit'))
				$item->params->set('access-edit', true);

			if ($acl->get('core.delete'))
				$item->params->set('access-delete', true);


		}

	}

}
