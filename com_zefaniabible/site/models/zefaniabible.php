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
class ZefaniabibleModelZefaniabible extends ZefaniabibleModelList
{
	var $_name_sing = 'zefaniabibleitem';
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
	function _buildQuery_scripture($alias)
	{
		try 
		{
			$db = $this->getDbo();
			$query =  "SELECT a.* FROM `#__zefaniabible` AS a WHERE publish = 1 and a.alias='".$alias."'" ;
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}	 
	function _get_pagination_verseofday()
	{
		try 
		{
			$db =& JFactory::getDBO();
			$mainframe = JFactory::getApplication();			
			$lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$lim0	= JRequest::getVar('limitstart', 0, '', 'int');			
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniaverseofday` AS a INNER JOIN `#__zefaniabible_zefaniabiblebooknames` AS b ON a.book_name = b.id WHERE b.publish = 1 AND a.publish = 1 ORDER BY a.book_name, a.chapter_number, a.begin_verse';
			$db->setQuery($query);
			$data = new JPagination( $db->loadResult(), $lim0, $lim );
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_verseofday($pagination )
	{
		try 
		{
			$db =& JFactory::getDBO();	
			$query =  'SELECT a.* FROM `#__zefaniabible_zefaniaverseofday` AS a INNER JOIN `#__zefaniabible_zefaniabiblebooknames` AS b ON a.book_name = b.id WHERE b.publish = 1 AND a.publish = 1 ORDER BY a.book_name, a.chapter_number, a.begin_verse';
			$db->setQuery($query, $pagination->limitstart, $pagination->limit);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}	
		return $data;
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
	function _buildQuery_compare()
	{
		try 
		{
			$db = $this->getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible` AS a WHERE publish = 1 ORDER BY a.title';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _buildQuery_readingplan_overview($alias, $pagination)
	{
		try 
		{
			$db = $this->getDbo();
			$query_plan = "SELECT * FROM `#__zefaniabible_zefaniareading` AS c WHERE c.alias='".$alias."'";	
			$db->setQuery($query_plan);
			$id = $db->loadResult();
						
			$db = $this->getDbo();
			$query = "SELECT a.* FROM `#__zefaniabible_zefaniareadingdetails` AS a WHERE a.plan='".$id."' AND a.day_number > ".$pagination->limitstart." AND a.day_number <=".($pagination->limitstart+$pagination->limit)." ORDER BY a.plan, a.day_number";			
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	function _get_pagination_readingplan_overview($alias)
	{
		try 
		{
			$db =& JFactory::getDBO();
			$mainframe = JFactory::getApplication();			
			$lim = $mainframe->getUserStateFromRequest('$option.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			$lim0	= JRequest::getVar('limitstart', 0, '', 'int');			
			$db = $this->getDbo();
			$query_plan = "SELECT * FROM `#__zefaniabible_zefaniareading` AS c WHERE c.alias='".$alias."'";	
			$db->setQuery($query_plan);
			$id = $db->loadResult();
						
			$db = $this->getDbo();
			$query = "SELECT MAX(a.day_number)  FROM `#__zefaniabible_zefaniareadingdetails` AS a WHERE a.plan='".$id."' ORDER BY a.plan, a.day_number" ;						
			$db->setQuery($query);
			$data = new JPagination( $db->loadResult(), $lim0, $lim );
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
	
	function _buildQuery_comment()
	{
		try 
		{
			$db = $this->getDbo();
			$query = 'SELECT a.* FROM `#__zefaniabible_zefaniacomment` AS a WHERE publish = 1 ORDER BY a.ordering, a.title';			
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
	
	function _buildQuery_bookNames()
	{
		try 
		{
			$db = $this->getDbo();
			$query = 'SELECT a.* FROM `#__zefaniabible_zefaniabiblebooknames` AS a WHERE publish = 1 ORDER BY a.ordering ';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;
	}
		
	function _buildQuery_standard()
	{
		try 
		{
			$db = $this->getDbo();
			$query =  'SELECT a.* FROM `#__zefaniabible` AS a WHERE publish = 1 ORDER BY a.title';
			$db->setQuery($query);
			$data = $db->loadObjectList();	
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;		
	}
	function _buildQuery_player($id)
	{
		try 
		{
			$db = $this->getDbo();
			$query = "SELECT a.* FROM `#__zefaniabible` AS a WHERE a.alias='".$id."'";
			$db->setQuery($query);
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
