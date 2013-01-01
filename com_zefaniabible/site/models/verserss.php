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
class ZefaniabibleModelVerserss extends ZefaniabibleModelList
{
	var $_name_sing = 'verserssitem';
	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(

			);
		}
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
	function _buildQuery_get_verse_of_the_day($arr_verse_info,$int_verse_remainder,$arr_db_call_info)
	{
		try
		{
		$db = JFactory::getDBO();
		$query = "SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a".
				" INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id".
				" WHERE a.bible_id=".$arr_db_call_info['int_id']." AND a.book_id=".
				$arr_verse_info['book_name'][$int_verse_remainder] ." AND a.chapter_id=".
				$arr_verse_info['chapter_number'][$int_verse_remainder];
					if($arr_verse_info['end_verse'][$int_verse_remainder] != 0)
					{
						$query = $query ." AND a.verse_id>=".$arr_verse_info['begin_verse'][$int_verse_remainder]." AND a.verse_id<=".$arr_verse_info['end_verse'][$int_verse_remainder];
					}
					else
					{
						$query = $query ." AND a.verse_id=".$arr_verse_info['begin_verse'][$int_verse_remainder];
					}
					$query = $query .' AND b.publish=1';
					$query = $query ." ORDER BY a.book_id, a.chapter_id, a.verse_id";
			$db->setQuery($query);
			$data = $db->loadObjectList(); 
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_get_verses()
	{
		try
		{
			$db = JFactory::getDBO();
			$query 	= "SELECT * FROM #__zefaniabible_zefaniaverseofday WHERE publish=1";
			$db->setQuery($query);
			$arr_rows = $db->loadObjectList();	
			$x = 1;
			foreach($arr_rows as $arr_row)
			{
				$arr_verse_info['book_name'][$x] = $arr_row->book_name;
				$arr_verse_info['chapter_number'][$x] = $arr_row->chapter_number;
				$arr_verse_info['begin_verse'][$x] = $arr_row->begin_verse;
				$arr_verse_info['end_verse'][$x] = $arr_row->end_verse;
				$x++;
			}
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $arr_verse_info;
	}		 
	function _buildQuery_bible_name($str_bible_alias)
	{
		try 
		{
			$db = JFactory::getDBO();
			$query 	= "SELECT * FROM #__zefaniabible_bible_names WHERE alias='".$str_bible_alias."'";
			$db->setQuery($query);
			$arr_rows = $db->loadObjectList();
			foreach($arr_rows as $arr_row)
			{
				$arr_temp['int_id'] = $arr_row->id;
				$arr_temp['str_nativeTitle'] = $arr_row->bible_name;
				$arr_temp['str_nativeAlias'] = $arr_row->alias;
			}	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $arr_temp;		
	}
}
