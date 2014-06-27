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
class ZefaniabibleModelPlan extends ZefaniabibleModelList
{
	var $_name_sing = 'planitem';
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
			$db = JFactory::getDBO();
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
}
