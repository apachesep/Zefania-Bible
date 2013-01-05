<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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
 * Zefaniabible Component Zefaniauser Model
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleModelZefaniauser extends ZefaniabibleModelList
{
	var $_name_sing = 'zefaniauseritem';



	/**
	 * Constructor
	 *
	 */
	function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'user_name', 'a.user_name',
				'_bible_version_title', '_bible_version_.bible_name',
				'_plan_name', '_plan_.name',
				'send_reading_plan_email', 'a.send_reading_plan_email',
				'send_verse_of_day_email', 'a.send_verse_of_day_email',
				'email', 'a.email',				
				'reading_start_date', 'a.reading_start_date',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'send_reading_plan_email' => 'bool',
			'reading_start_date' => 'string'
				));

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
		// Filter Dropdown Bible Plans
        $state = $this->getUserStateFromRequest($this->context.'.filter.plan_name', 'filter_plan_name', '', 'string');
        $this->setState('filter.plan_name', $state);
		
		//Filter (dropdown) Bible Version
        $state = $this->getUserStateFromRequest($this->context.'.filter.bible_name', 'filter_bible_name', '', 'string');
		$this->setState('filter.bible_name', $state);
		// Filter (dropdown) Send Reading Plan
        $state = $this->getUserStateFromRequest($this->context.'.filter.send_reading_plan_email', 'filter_send_reading_plan_email', '', 'string');
		$this->setState('filter.send_reading_plan_email', $state);		
		
		// Filter (dropdown) Send Reading Plan
        $state = $this->getUserStateFromRequest($this->context.'.filter.send_verse_of_day_email', 'filter_send_verse_of_day_email', '', 'string');
		$this->setState('filter.send_verse_of_day_email', $state);	
				
		parent::populateState();
	}


	/**
	 * Method to build a the query string for the Zefaniauseritem
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

			. ' FROM `#__zefaniabible_zefaniauser` AS a '

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
					.	' , _plan_.name AS `_plan_name`'

			. $this->_buildQuerySelect()

			. ' FROM `#__zefaniabible_zefaniauser` AS a '
					.	' LEFT JOIN `#__zefaniabible_bible_names` AS _bible_version_ ON _bible_version_.id = a.bible_version'
					.	' LEFT JOIN `#__zefaniabible_zefaniareading` AS _plan_ ON _plan_.id = a.plan'

			. $this->_buildQueryJoin() . ' '

			. $this->_buildQueryWhere()


			. $this->_buildQueryOrderBy()
			. $this->_buildQueryExtra()
		;

		return $query;
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

	function _buildQueryWhere($where = array())
	{
		$app = JFactory::getApplication();
		$db= JFactory::getDBO();
		$acl = ZefaniabibleHelper::getAcl();


		if (isset($this->_active['filter']) && $this->_active['filter'])
		{
			$filter_bible_name = $this->getState('filter.bible_name');
			if ($filter_bible_name != '')		$where[] = "_bible_version_.bible_name = " . $db->Quote($filter_bible_name);
						
			$filter_send_reading_plan_email = $this->getState('filter.send_reading_plan_email');
			if ($filter_send_reading_plan_email != null)		$where[] = "a.send_reading_plan_email = " . $db->Quote($filter_send_reading_plan_email);

			$filter_reading_start_date = $this->getState('filter.reading_start_date');
			if ($filter_reading_start_date != '')		$where[] = "a.reading_start_date = " . $db->Quote($filter_reading_start_date);

			$filter_plan_name = $this->getState('filter.plan_name');
			if ($filter_plan_name != '')		$where[] = "_plan_.name = " . $db->Quote($filter_plan_name);	

			$filter_send_verse_of_day_email = $this->getState('filter.send_verse_of_day_email');
			if ($filter_send_verse_of_day_email != null)		$where[] = "a.send_verse_of_day_email = " . $db->Quote($filter_send_verse_of_day_email);
		
			//search_search : search on User Name + Plan + Bible Version + 
			$search_search = $this->getState('search.search');
			$this->_addSearch('search', 'a.user_name', 'like');
			$this->_addSearch('search', 'a.email', 'like');

			if (($search_search != '') && ($search_search_val = $this->_buildSearch('search', $search_search)))
				$where[] = $search_search_val;


		}


		return parent::_buildQueryWhere($where);
	}

	function _buildQueryOrderBy($order = array(), $pre_order = 'a.user_name')
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
