<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
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
 * Zefaniabible Component Zefaniareadingdetails Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniareadingdetails extends ZefaniabibleModelList
{
	var $_name_sing = 'zefaniareadingdetailsitem';



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'_plan_name', '_plan_.name',
				'_book_id_bible_book_name', '_book_id_.bible_book_name',
				'begin_chapter', 'a.begin_chapter',
				'begin_verse', 'a.begin_verse',
				'end_chapter', 'a.end_chapter',
				'end_verse', 'a.end_verse',
				'day_number', 'a.day_number',
				'ordering', 'a.ordering',

			);
		}

		//Define the filterable fields
		$this->set('search_vars', array(
			'search' => 'varchar'
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


		//Filter (dropdown) Bible Book
        $state = $this->getUserStateFromRequest($this->context.'.filter.book_id', 'filter_book_id', '', 'string');
        $this->setState('filter.book_id', $state);
		//Filter (dropdown) Bible Plan
        $state = $this->getUserStateFromRequest($this->context.'.filter.plan_name', 'filter_plan_name', '', 'string');
        $this->setState('filter.plan_name', $state);
		//Filter (dropdown) Day Number
        $state = $this->getUserStateFromRequest($this->context.'.filter.day_number', 'filter_day_number', '', 'string');
        $this->setState('filter.day_number', $state);

		parent::populateState();
	}

	/**
	 * Method to build a the query string for the Zefaniareadingdetailsitem
	 *
	 * @access public
	 * @return integer
	 */
	function _buildQuery()
	{

		if (isset($this->_active['predefined']))
		switch($this->_active['predefined'])
		{
			case 'default': return $this->_buildQuery_default(); break;

		}



		$query = ' SELECT a.*'

			. $this->_buildQuerySelect()

			. ' FROM `#__zefaniabible_zefaniareadingdetails` AS a '

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
	}
	function _buildQuery_max_day($str_plan_name, $int_bible_book_id)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Max(a.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS a');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS b ON a.plan = b.id');
			if($str_plan_name)
			{
				$query->where('b.name="'.$str_plan_name.'"');
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
	function _buildQuery_min_day($str_plan_name, $int_bible_book_id)
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('Min(a.day_number)');
			$query->from('`#__zefaniabible_zefaniareadingdetails` AS a');	
			$query->innerJoin('`#__zefaniabible_zefaniareading` AS b ON a.plan = b.id');
			if($str_plan_name)
			{
				$query->where('b.name="'.$str_plan_name.'"');
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
	function _buildQuery_plans()
	{
		try 
		{
			$db = $this->getDbo();
			$query  = $db->getQuery(true);
			$query->select('b.name');
			$query->from('`#__zefaniabible_zefaniareading` AS b');
			$query->where('b.publish=1');
			$db->setQuery($query);
			$data = $db->loadObjectList();				
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;	
	}
	function _buildQuery_default()
	{

		$query = ' SELECT a.*'
					.	' , _plan_.name AS `_plan_name`'
					.	' , _book_id_.bible_book_name AS `_book_id_bible_book_name`'

			. $this->_buildQuerySelect()

			. ' FROM `#__zefaniabible_zefaniareadingdetails` AS a '
					.	' LEFT JOIN `#__zefaniabible_zefaniareading` AS _plan_ ON _plan_.id = a.plan'
					.	' LEFT JOIN `#__zefaniabible_zefaniabiblebooknames` AS _book_id_ ON _book_id_.id = a.book_id'

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
			$filter_book_id = $this->getState('filter.book_id');
			if ($filter_book_id != '')		$where[] = "a.book_id = " . $db->Quote($filter_book_id);

			$filter_plan_name = $this->getState('filter.plan_name');
			if ($filter_plan_name != '')		$where[] = "_plan_.name = " . $db->Quote($filter_plan_name);		
			
			$filter_day_number = $this->getState('filter.day_number');
			if ($filter_day_number != '')		$where[] = "a.day_number = " . $db->Quote($filter_day_number);							
		}

		return parent::_buildQueryWhere($where);
	}

	function _buildQueryOrderBy($order = array(), $pre_order = 'a.ordering, a.plan')
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

			$item->params->set('access-view', true);

			if ($acl->get('core.edit'))
				$item->params->set('access-edit', true);

			if ($acl->get('core.delete'))
				$item->params->set('access-delete', true);


		}

	}

}
