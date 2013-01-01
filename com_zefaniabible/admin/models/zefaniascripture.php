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

require_once(JPATH_ADMIN_ZEFANIABIBLE .DS.'classes'.DS.'jmodel.list.php');


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
				'title', 'a.bible_name',
				'full_name', 'a.desc',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'select_bible_book' => 'string'
				));

		parent::__construct($config);
		$this->_modes = array_merge($this->_modes, array('publish'));


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
        $state = $this->getUserStateFromRequest($this->context.'.filter.bibleversion', 'filter_bibleversion', '', 'string');
		$this->setState('filter.bibleversion', $state);
 
		//Filter (dropdown) Bible Book
        $state = $this->getUserStateFromRequest($this->context.'.filter.biblebook', 'filter_biblebook', '', 'string');
        $this->setState('filter.biblebook', $state);
		
		//Filter (dropdown) Bible Chapter
        $state = $this->getUserStateFromRequest($this->context.'filter.biblechapter', 'filter_biblechapter', '', 'string');
        $this->setState('filter.biblechapter', $state);	
		
		//Filter (dropdown) Bible Chapter
        $state = $this->getUserStateFromRequest($this->context.'filter.bibleverse', 'filter_bibleverse', '', 'string');
        $this->setState('filter.bibleverse', $state);			
 
		//Takes care of states: list. limit / start / ordering / direction
        parent::populateState('a.name', 'asc');
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
			$query->select('b.alias, b.bible_name');
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
	function _buildQuery_default($arr_pagination, $int_Bible_Chapter, $int_Bible_Book_ID, $int_Bible_Verse_ID, $str_Bible_Version )
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
            $query->select('a.id, b.bible_name, a.book_id, a.chapter_id, a.verse_id, a.verse');
            $query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$state = $db->escape($this->getState('filter.bibleversion'));
            if($str_Bible_Version)
			{
            	$query->where('b.alias="'.$str_Bible_Version.'"');
			}
			if($int_Bible_Book_ID)
			{
				$query->where('a.book_id='.$int_Bible_Book_ID);
			}
			if($int_Bible_Chapter)
			{
				$query->where('a.chapter_id='.$int_Bible_Chapter);
			}
			if($int_Bible_Verse_ID)
			{
				$query->where('a.verse_id='.$int_Bible_Verse_ID);	
			}			
			$query->order('a.book_id, a.chapter_id, a.verse_id ASC');

			$db->setQuery($query, $arr_pagination->limitstart, $arr_pagination->limit);
			$data = $db->loadObjectList();			
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
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
