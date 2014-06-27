<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Biblenames
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
* Zefaniabible Item Model
*
* @package	Zefaniabible
* @subpackage	Classes
*/
class ZefaniabibleCkModelZefaniabibleitem extends ZefaniabibleClassModelItem
{
	/**
	* List of all fields files indexes
	*
	* @var array
	*/
	protected $fileFields = array('xml_bible_file_location', 'xml_audio_file_location');

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_item = 'zefaniabibleitem';

	/**
	* View list alias
	*
	* @var string
	*/
	protected $view_list = 'biblenames';

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

		//Integrity : delete the files associated to this deleted item
		if (!$this->deleteFiles($pks, array(
												'xml_bible_file_location' => 'delete',
												'xml_audio_file_location' => 'delete'
											))){
			JError::raiseWarning( 1303, JText::_("DEMO120_ALERT_ERROR_ON_DELETE_FILES") );
			return false;
		}

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
		return $jinput->get('layout', '', 'STRING');
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
	public function getTable($type = 'zefaniabibleitem', $prefix = 'ZefaniabibleTable', $config = array())
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
		$data = JFactory::getApplication()->getUserState('com_zefaniabible.edit.zefaniabibleitem.data', array());

		if (empty($data)) {
			//Default values shown in the form for new item creation
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('zefaniabibleitem.id') == 0)
			{
				$jinput = JFactory::getApplication()->input;

				$data->id = 0;
				$data->bible_name = null;
				$data->alias = null;
				$data->published = null;
				$data->xml_bible_file_location = null;
				$data->xml_audio_file_location = null;
				$data->ordering = null;
				$data->access = $jinput->get('filter_access', $this->getState('filter.access',1), 'INT');

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

		//Only show the published items
		if (!$acl->get('core.admin') && !$acl->get('core.edit.state'))
			$this->setState('filter.published', 1);
	}

	/**
	* Preparation of the query.
	*
	* @access	protected
	* @param	object	&$query	returns a filled query object.
	* @param	integer	$pk	The primary id key of the zefaniabibleitem
	* @return	void
	*/
	protected function prepareQuery(&$query, $pk)
	{

		$acl = ZefaniabibleHelper::getActions();

		//FROM : Main table
		$query->from('#__zefaniabible_biblenames AS a');



		//IMPORTANT REQUIRED FIELDS
		$this->addSelect(	'a.id,'
						.	'a.access,'
						.	'a.published');

		switch($this->getState('context', 'all'))
		{

			case 'all':
				//SELECT : raw complete query without joins
				$query->select('a.*');
				break;
		}

		//WHERE : Item layout (based on $pk)
		$query->where('a.id = ' . (int) $pk);		//TABLE KEY

		//FILTER - Access for : Root table
		$whereAccess = $wherePublished = true;
		$allowAuthor = false;
		$this->prepareQueryAccess('a', $whereAccess, $wherePublished, $allowAuthor);
		$query->where("$whereAccess AND $wherePublished");

		//SELECT : Instance Add-ons
		foreach($this->getState('query.select', array()) as $select)
			$query->select($select);

		//JOIN : Instance Add-ons
		foreach($this->getState('query.join.left', array()) as $join)
			$query->join('LEFT', $join);
	}

	/**
	* Prepare and sanitise the table prior to saving.
	*
	* @access	protected
	* @param	JTable	$table	A JTable object.
	*
	* @return	void	
	* @return	void
	*
	* @since	1.6
	*/
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();


		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			$conditions = $this->getReorderConditions($table);
			$conditions = (count($conditions)?implode(" AND ", $conditions):'');
			$table->ordering = $table->getNextOrder($conditions);
		}
		else
		{

		}

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

		//Some security checks
		$acl = ZefaniabibleHelper::getActions();

		//Secure the access key if not allowed to change
		if (isset($data['access']) && !$acl->get('core.edit'))
			unset($data['access']);

		//Secure the published tag if not allowed to change
		if (isset($data['published']) && !$acl->get('core.edit.state'))
			unset($data['published']);

		if (parent::save($data)) {
			return true;
		}
		return false;


	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleModelZefaniabibleitem')){ class ZefaniabibleModelZefaniabibleitem extends ZefaniabibleCkModelZefaniabibleitem{} }

