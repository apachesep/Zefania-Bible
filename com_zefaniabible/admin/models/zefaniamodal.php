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
class ZefaniabibleModelZefaniamodal extends ZefaniabibleModelList
{
	var $_name_sing = 'zefaniamodalitem';



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
				'publish', 'a.publish',
				'ordering', 'a.ordering',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'publish' => 'bool'
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



		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Zefaniabibleitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery_Bibles()
	 {
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('bible_name, alias');
			$query->from('`#__zefaniabible_bible_names`');
			$query->where("publish=1");
			$query->order('ordering');	
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		 
	 }
	 function _buildQuery_Verses($str_bible_alias,$int_bible_book_id,$int_begin_chap,$int_begin_verse,$int_end_chap,$int_end_verse)
	 {
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('a.book_id, a.chapter_id,a.verse_id, a.verse, b.bible_name');
			$query->from('`#__zefaniabible_bible_text` AS a');
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->where("a.book_id=".$int_bible_book_id);
			$query->where("b.alias='".$str_bible_alias."'");
			// Genesis 1
				if(($int_begin_chap)and(!$int_end_chap)and(!$int_begin_verse)and(!$int_end_verse))
				{
					$query->where("a.chapter_id=".$int_begin_chap);
					$query->order("a.book_id, a.chapter_id, a.verse_id"); 
				}
				// Genesis 1-2
				else if(($int_begin_chap)and($int_end_chap)and(!$int_begin_verse)and(!$int_end_verse))
				{
					$query->where("a.chapter_id>=".$int_begin_chap." AND a.chapter_id<=".$int_end_chap);
					$query->order("a.book_id, a.chapter_id, a.verse_id"); 
				}
				// Genesis 1:1
				else if(($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and(!$int_end_verse))
				{
					$query->where("a.chapter_id=".$int_begin_chap." AND a.verse_id=".$int_begin_verse);
					$query->order("a.book_id, a.chapter_id, a.verse_id"); 
				}
				// Genesis 1:1-2
				else if(($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and($int_end_verse))
				{
					$query->where("a.chapter_id=".$int_begin_chap." AND a.verse_id>=".$int_begin_verse. " AND a.verse_id<=".$int_end_verse);
					$query->order("a.book_id, a.chapter_id, a.verse_id"); 
				}
				// Genesis 1:2-2:3
				else if(($int_begin_chap)and($int_end_chap)and($int_begin_verse)and($int_end_verse))
				{
					$str_tmp_old_query = $query;
					$query	= "SELECT * FROM( ".$query . " AND a.chapter_id=".$int_begin_chap." AND a.verse_id>=".$int_begin_verse. " ORDER BY a.verse_id ASC ) as c";
					$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id=".$int_end_chap." AND a.verse_id<=".$int_end_verse." ORDER BY a.verse_id ASC) as d";
					if(($int_end_chap - $int_begin_chap)>1)
					{
						$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id>=".($int_begin_chap+1)." AND a.chapter_id<=".($int_end_chap-1)." ORDER BY a.verse_id ASC) as e";
   					}
					$query  = $query. " ORDER BY chapter_id, verse_id";
				}
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;				 
	 }
}
