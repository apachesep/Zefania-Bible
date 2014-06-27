<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniauser
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.propoved.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



/**
* Zefaniabible List Model
*
* @package	Zefaniabible
* @subpackage	Classes
*/
class ZefaniabibleCkModelZefaniauser extends ZefaniabibleClassModelList
{
	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniauseritem';

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		//Define the sortables fields (in lists)
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'user_name', 'a.user_name',
				'_bible_version_bible_name', '_bible_version_.bible_name',
				'_plan_name', '_plan_.name',
				'send_reading_plan_email', 'a.send_reading_plan_email',
				'send_verse_of_day_email', 'a.send_verse_of_day_email',
				'reading_start_date', 'a.reading_start_date',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'send_reading_plan_email' => 'cmd',
			'sortTable' => 'cmd',
			'directionTable' => 'cmd',
			'limit' => 'cmd'
				));

		//Define the searchable fields
		$this->set('search_vars', array(
			'search' => 'string',
			'search_1' => 'string'
				));


		parent::__construct($config);
		
	}

	/**
	* Method to get a list of items.
	*
	* @access	public
	*
	* @return	mixed	An array of data items on success, false on failure.
	*
	* @since	11.1
	*/
	public function getItems()
	{

		$items	= parent::getItems();
		$app	= JFactory::getApplication();


		$this->populateParams($items);

		//Create linked objects
		$this->populateObjects($items);

		return $items;
	}

	/**
	* Method to get the layout (including default).
	*
	* @access	public
	*
	* @return	string	The layout alias.
	*/
	public function getLayout()
	{
		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'default', 'STRING');
	}

	/**
	* Method to get a store id based on model configuration state.
	* 
	* This is necessary because the model is used by the component and different
	* modules that might need different sets of data or differen ordering
	* requirements.
	*
	* @access	protected
	* @param	string	$id	A prefix for the store id.
	* @return	void
	*
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
	* This method should only be called once per instantiation and is designed to
	* be called on the first call to the getState() method unless the model
	* configuration flag to ignore the request is set.
	* 
	* Note. Calling getState in this method will result in recursion.
	*
	* @access	public
	* @param	string	$ordering	
	* @param	string	$direction	
	* @return	void
	*
	* @since	11.1
	*/
	public function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$acl = ZefaniabibleHelper::getActions();

		parent::populateState('a.user_name', 'asc');
	}

	/**
	* Preparation of the list query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @return	void
	*/
	protected function prepareQuery(&$query)
	{

		$acl = ZefaniabibleHelper::getActions();

		//FROM : Main table
		$query->from('#__zefaniabible_zefaniauser AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id');


		switch($this->getState('context', 'all'))
		{
			case 'zefaniauser.default':

				//BASE FIELDS
				$this->addSelect(	'a.bible_version,'
								.	'a.email,'
								.	'a.plan,'
								.	'a.reading_start_date,'
								.	'a.send_reading_plan_email,'
								.	'a.send_verse_of_day_email,'
								.	'a.user_name');

				//SELECT
				$this->addSelect('_bible_version_.bible_name AS `_bible_version_bible_name`');
				$this->addSelect('_plan_.name AS `_plan_name`');

				//JOIN
				$this->addJoin('`#__zefaniabible_biblenames` AS _bible_version_ ON _bible_version_.id = a.bible_version', 'LEFT');
				$this->addJoin('`#__zefaniabible_zefaniareading` AS _plan_ ON _plan_.id = a.plan', 'LEFT');

				break;

			case 'zefaniauser.modal':

				//BASE FIELDS
				$this->addSelect(	'a.user_name');


				break;
			case 'all':
				//SELECT : raw complete query without joins
				$this->addSelect('a.*');

				// Disable the pagination
				$this->setState('list.limit', null);
				$this->setState('list.start', null);
				break;
		}

		//FILTER - Access for : Root table


		//WHERE - FILTER : Send Reading Plan Email
		if($this->getState('filter.send_reading_plan_email') !== null)
			$this->addWhere("a.send_reading_plan_email = " . (int)$this->getState('filter.send_reading_plan_email'));

		//WHERE - SEARCH : search_search : search on User Name + Plan + Bible Version
		$search_search = $this->getState('search.search');
		$this->addSearch('search', 'a.user_name', 'like');
		$this->addSearch('search', 'a.plan', 'like');
		$this->addSearch('search', 'a.bible_version', 'like');
		if (($search_search != '') && ($search_search_val = $this->buildSearch('search', $search_search)))
			$this->addWhere($search_search_val);

		//WHERE - SEARCH : search_search_1 : search on User Name + Plan + Bible Version
		$search_search_1 = $this->getState('search.search_1');
		$this->addSearch('search_1', 'a.user_name', 'like');
		$this->addSearch('search_1', 'a.plan', 'like');
		$this->addSearch('search_1', 'a.bible_version', 'like');
		if (($search_search_1 != '') && ($search_search_1_val = $this->buildSearch('search_1', $search_search_1)))
			$this->addWhere($search_search_1_val);

		//Populate only uniques strings to the query
		//SELECT
		foreach($this->getState('query.select', array()) as $select)
			$query->select($select);

		//JOIN
		foreach($this->getState('query.join.left', array()) as $join)
			$query->join('LEFT', $join);

		//WHERE
		foreach($this->getState('query.where', array()) as $where)
			$query->where($where);

		//GROUP ORDER : Prioritary order for groups in lists
		foreach($this->getState('query.groupOrder', array()) as $groupOrder)
			$query->order($groupOrder);

		//ORDER
		foreach($this->getState('query.order', array()) as $order)
			$query->order($order);

		//ORDER
		$orderCol = $this->getState('list.ordering');
		$orderDir = $this->getState('list.direction', 'asc');

		if ($orderCol)
			$query->order($orderCol . ' ' . $orderDir);
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleModelZefaniauser')){ class ZefaniabibleModelZefaniauser extends ZefaniabibleCkModelZefaniauser{} }

