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
* Zefaniabible Item Model
*
* @package	Zefaniabible
* @subpackage	Classes
*/
class ZefaniabibleCkModelZefaniauseritem extends ZefaniabibleClassModelItem
{
	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_item = 'zefaniauseritem';

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_list = 'zefaniauser';

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		parent::__construct();
	}

	/**
	* Method to delete item(s).
	*
	* @access	public
	* @param	array	&$pks	Ids of the items to delete.
	*
	* @return	boolean	True on success.
	*/
	public function delete(&$pks)
	{
		if (!count( $pks ))
			return true;


		if (!parent::delete($pks))
			return false;



		return true;
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
		return $jinput->get('layout', 'adduser', 'STRING');
	}

	/**
	* Returns a Table object, always creating it.
	*
	* @access	public
	* @param	string	$type	The table type to instantiate.
	* @param	string	$prefix	A prefix for the table class name. Optional.
	* @param	array	$config	Configuration array for model. Optional.
	*
	* @return	JTable	A database object
	*
	* @since	1.6
	*/
	public function getTable($type = 'zefaniauseritem', $prefix = 'ZefaniabibleTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	* Method to get the data that should be injected in the form.
	*
	* @access	protected
	*
	* @return	mixed	The data for the form.
	*/
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_zefaniabible.edit.zefaniauseritem.data', array());

		if (empty($data)) {
			//Default values shown in the form for new item creation
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('zefaniauseritem.id') == 0)
			{
				$jinput = JFactory::getApplication()->input;

				$data->id = 0;
				$data->user_name = null;
				$data->plan = $jinput->get('filter_plan', $this->getState('filter.plan'), 'INT');
				$data->bible_version = $jinput->get('filter_bible_version', $this->getState('filter.bible_version'), 'INT');
				$data->user_id = $jinput->get('filter_user_id', $this->getState('filter.user_id'), 'INT');
				$data->email = null;
				$data->send_reading_plan_email = null;
				$data->send_verse_of_day_email = null;
				$data->reading_start_date = null;

			}
		}
		return $data;
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
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$acl = ZefaniabibleHelper::getActions();



		parent::populateState($ordering, $direction);
	}

	/**
	* Preparation of the query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @param	integer	$pk	The primary id key of the zefaniauseritem
	* @return	void
	*/
	protected function prepareQuery(&$query, $pk)
	{

		$acl = ZefaniabibleHelper::getActions();

		//FROM : Main table
		$query->from('#__zefaniabible_zefaniauser AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id');


		switch($this->getState('context', 'all'))
		{
			case 'zefaniauseritem.adduser':

				//BASE FIELDS
				$this->addSelect(	'a.bible_version,'
								.	'a.email,'
								.	'a.plan,'
								.	'a.reading_start_date,'
								.	'a.send_reading_plan_email,'
								.	'a.send_verse_of_day_email,'
								.	'a.user_id,'
								.	'a.user_name');

				//SELECT
				$this->addSelect('_bible_version_.bible_name AS `_bible_version_bible_name`');
				$this->addSelect('_plan_.name AS `_plan_name`');
				$this->addSelect('_user_id_.name AS `_user_id_name`');

				//JOIN
				$this->addJoin('`#__zefaniabible_biblenames` AS _bible_version_ ON _bible_version_.id = a.bible_version', 'LEFT');
				$this->addJoin('`#__zefaniabible_zefaniareading` AS _plan_ ON _plan_.id = a.plan', 'LEFT');
				$this->addJoin('`#__users` AS _user_id_ ON _user_id_.id = a.user_id', 'LEFT');

				break;
			case 'all':
				//SELECT : raw complete query without joins
				$query->select('a.*');
				break;
		}

		//WHERE : Item layout (based on $pk)
		$query->where('a.id = ' . (int) $pk);		//TABLE KEY

		//FILTER - Access for : Root table


		//SELECT : Instance Add-ons
		foreach($this->getState('query.select', array()) as $select)
			$query->select($select);

		//JOIN : Instance Add-ons
		foreach($this->getState('query.join.left', array()) as $join)
			$query->join('LEFT', $join);
	}

	/**
	* Save an item.
	*
	* @access	public
	* @param	array	$data	The post values.
	*
	* @return	boolean	True on success.
	*/
	public function save($data)
	{
		//Convert from a non-SQL formated date (reading_start_date)
		$data['reading_start_date'] = ZefaniabibleHelperDates::getSqlDate($data['reading_start_date'], array('Y-d-m'), true);

		if (parent::save($data)) {
			return true;
		}
		return false;


	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleModelZefaniauseritem')){ class ZefaniabibleModelZefaniauseritem extends ZefaniabibleCkModelZefaniauseritem{} }

