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
class ZefaniabibleModelReading extends ZefaniabibleModelList
{
	var $_name_sing = 'readingitem';
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
	function _buildQuery_current_reading($arr_reading, $str_Bible_Version)
	{
		$data = '';
		$x = 0;
		$arr_data = array();
		try 
		{
			$db = $this->getDbo();
			foreach($arr_reading as $reading)
			{
				$query = '';
				$int_book_id = $reading->book_id;
				$int_begin_chapter =  $reading->begin_chapter;
				$int_begin_verse =  $reading->begin_verse;
				$int_end_chapter =  $reading->end_chapter;
				$int_end_verse =  $reading->end_verse;
									
				$query = 'SELECT a.book_id, a.chapter_id, a.verse_id, a.verse FROM `#__zefaniabible_bible_text` AS a ';
				$query = $query. ' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id';
				$query = $query. " WHERE a.book_id=".$int_book_id." AND a.chapter_id>=".$int_begin_chapter." AND a.chapter_id<=".$int_end_chapter;
				if($int_begin_verse != 0)
				{
					$query = $query. " AND a.verse_id>=".$int_begin_verse." AND a.verse_id<=".$int_end_verse;
				}
				$query = $query. " AND b.alias='".$str_Bible_Version."'";
				$query = $query." ORDER BY a.book_id ASC, a.chapter_id ASC, a.verse_id ASC";
				
				$db->setQuery($query);
				$data = $db->loadObjectList(); 		
				$arr_data[$x] = $data;
				$x++;
			}
		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $arr_data;		
	}
	function _buildQuery_readingplan()
	{
		try 
		{
			$db = $this->getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniareading` AS a WHERE publish = 1 ORDER BY a.name';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}		 
	function _buildQuery_plan($alias, $str_start_reading_date) 
	{
		$arr_start_date = new DateTime($str_start_reading_date);	
		$arr_today = new DateTime(date('Y-m-d'));
		
		if (version_compare(PHP_VERSION, '5.3.0') >= 0) 
		{
			$interval = $arr_start_date->diff($arr_today);
			$int_day_diff = $interval->format('%a')+1;		
		}
		else
		{
			$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;	
		}
		
		try 
		{
			$db = $this->getDbo();
			$query_plan = "SELECT * FROM `#__zefaniabible_zefaniareading` AS c WHERE c.alias='".$alias."'";	
			$db->setQuery($query_plan);
			$id = $db->loadResult();			
			
			$db = $this->getDbo();
			$query_max = "SELECT Max(b.day_number) FROM `#__zefaniabible_zefaniareadingdetails` AS b WHERE b.plan='".$id."'";	
			$db->setQuery($query_max);
			$int_max_rows = $db->loadResult();
			$int_verse_remainder = $int_day_diff % $int_max_rows;
			$str_reading_day = JRequest::getCmd('c', $int_day_diff);

			if($str_reading_day > $int_max_rows )
			{
				$str_reading_day = $str_reading_day % $int_max_rows;
			}
			if($str_reading_day == 0)
			{
					$str_reading_day = $int_max_rows;
			}			
			$query = "SELECT a.* FROM `#__zefaniabible_zefaniareadingdetails` AS a WHERE a.plan='".$id."' AND a.day_number = ".$str_reading_day." ORDER BY a.plan, a.book_id" ;			
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_max_days($str_alias)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(a.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS a');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS b ON a.plan = b.id');
			$query->where("b.alias='".$str_alias."'");
			$db->setQuery($query);
			$data = $db->loadResult();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	function _buildQuery_Bibles()
	{
		try 
		{
			$db = $this->getDbo();
			$query =  'SELECT a.alias, a.bible_name FROM `#__zefaniabible_bible_names` AS a WHERE publish = 1 ORDER BY a.bible_name';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_getUserData($int_id)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('user.reading_start_date,bible.alias as bible_alias,plan.alias as plan_alias');
			$query->from('`#__zefaniabible_zefaniauser` AS user');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS plan ON user.plan = plan.id');
			$query->innerJoin('`#__zefaniabible_bible_names` AS bible ON user.bible_version = bible.id');			
			$query->where("user.user_id='".$int_id."'");
			$db->setQuery($query,0, 1);
			$data = $db->loadObjectList();		
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}	
}
