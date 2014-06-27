<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniabibledictionaryinfo
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
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
class ZefaniabibleCkModelZefaniabibledictionaryinfo extends ZefaniabibleClassModelList
{
	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniabibledictionaryinfoitem';

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
				'name', 'a.name',
				'alias', 'a.alias',
				'published', 'a.published',
				'ordering', 'a.ordering',

			);
		}

		//Define the filterable fields
		$this->set('filter_vars', array(
			'published' => 'cmd',
			'sortTable' => 'cmd',
			'directionTable' => 'cmd',
			'limit' => 'cmd'
				));

		//Define the searchable fields
		$this->set('search_vars', array(
			'search' => 'string',
			'search_1' => 'string',
			'search_2' => 'string',
			'search_3' => 'string'
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

		parent::populateState('a.ordering', 'asc');

		//Only show the published items
		if (!$acl->get('core.admin') && !$acl->get('core.edit.state'))
			$this->setState('filter.published', 1);
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
		$query->from('#__zefaniabible_zefaniabibledictionaryinfo AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id,'
						.	'a.access,'
						.	'a.published');

		switch($this->getState('context', 'all'))
		{
			case 'zefaniabibledictionaryinfo.default':

				//BASE FIELDS
				$this->addSelect(	'a.alias,'
								.	'a.name,'
								.	'a.ordering');

				break;

			case 'zefaniabibledictionaryinfo.modal':

				//BASE FIELDS
				$this->addSelect(	'a.name');


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
		$whereAccess = $wherePublished = true;
		$allowAuthor = false;
		$this->prepareQueryAccess('a', $whereAccess, $wherePublished, $allowAuthor);
		$query->where("$whereAccess AND $wherePublished");

		//WHERE - SEARCH : search_search : search on Search
		$search_search = $this->getState('search.search');
		$this->addSearch('search', 'a.published', 'like');
		$this->addSearch('search', 'a.name', 'like');
		$this->addSearch('search', 'a.alias', 'like');
		if (($search_search != '') && ($search_search_val = $this->buildSearch('search', $search_search)))
			$this->addWhere($search_search_val);

		//WHERE - SEARCH : search_search_1 : search on name + alias
		$search_search_1 = $this->getState('search.search_1');
		$this->addSearch('search_1', 'a.name', 'like');
		$this->addSearch('search_1', 'a.alias', 'like');
		if (($search_search_1 != '') && ($search_search_1_val = $this->buildSearch('search_1', $search_search_1)))
			$this->addWhere($search_search_1_val);

		//WHERE - FILTER : Publish state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
			$query->where('a.published = ' . (int) $published);
		elseif (!$published)
			$query->where('(a.published = 0 OR a.published = 1 OR a.published IS NULL)');

		//WHERE - SEARCH : search_search_2 : search on Search
		$search_search_2 = $this->getState('search.search_2');
		$this->addSearch('search_2', 'a.published', 'like');
		$this->addSearch('search_2', 'a.name', 'like');
		$this->addSearch('search_2', 'a.alias', 'like');
		if (($search_search_2 != '') && ($search_search_2_val = $this->buildSearch('search_2', $search_search_2)))
			$this->addWhere($search_search_2_val);

		//WHERE - SEARCH : search_search_3 : search on name + alias
		$search_search_3 = $this->getState('search.search_3');
		$this->addSearch('search_3', 'a.name', 'like');
		$this->addSearch('search_3', 'a.alias', 'like');
		if (($search_search_3 != '') && ($search_search_3_val = $this->buildSearch('search_3', $search_search_3)))
			$this->addWhere($search_search_3_val);

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
if (!class_exists('ZefaniabibleModelZefaniabibledictionaryinfo')){ class ZefaniabibleModelZefaniabibledictionaryinfo extends ZefaniabibleCkModelZefaniabibledictionaryinfo{} }

