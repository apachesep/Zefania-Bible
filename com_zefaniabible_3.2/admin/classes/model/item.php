<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	
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

jimport('joomla.application.component.modeladmin');


/**
* Zefaniabible Item Model
*
* @package	Zefaniabible
* @subpackage	Classes
*/
class ZefaniabibleCkClassModelItem extends JModelAdmin
{
	/**
	* Data array
	*
	* @var array
	*/
	protected $_data = null;

	/**
	* Item id
	*
	* @var integer
	*/
	public $_id = null;

	/**
	* Item by id.
	*
	* @var array
	*/
	protected $_item = null;

	/**
	* Item params
	*
	* @var array
	*/
	protected $_params = null;

	/**
	* Context string for the model type.  This is used to handle uniqueness
	*
	* @var string
	*/
	protected $context = null;

	/**
	* List of all fields files indexes
	*
	* @var array
	*/
	protected $fileFields = array();

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);

		$layout = $this->getLayout();

		$jinput = JFactory::getApplication()->input;
		$render = $jinput->get('render', null, 'CMD');

		$this->context = strtolower($this->option . '.' . $this->getName()
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);
	}

	/**
	* Method to update a file and eventually upload.
	*
	* @access	public
	* @param	string	$fieldName	Field that store the file name.
	* @param	array	$extensions	Allowed extensions.
	* @param	array	$options	Specific options.
	* @param	string	$dir	Root folder (can be a pattern).
	*
	* @return	boolean	False on failure or error, true otherwise.
	*/
	public function _upload($fieldName, $extensions = null, $options = array(), $dir = null)
	{
		//Send the id for eventual name or path parsing in upload
		$options['id'] = $this->getId();

		$config	= JComponentHelper::getParams( 'com_zefaniabible' );

		if (!$dir)
			$dir = '[DIR_' . strtoupper($this->view_list . '_' . $fieldName) . ']';

		$jinput = JFactory::getApplication()->input;

		//Get the submited files if exists
		$fileInput = new JInput($_FILES);
		$files = $fileInput->get('jform', null, 'array');

		$uploadFile = array();
		//Process a conversion to get the right datas
		if (!empty($files))
			foreach($files as $key => $params)
				$uploadFile[$key] = $params[$fieldName];

		$post = $jinput->get('jform', null, 'array');

		// Remove parameter
		$removeVar = $fieldName . "-remove";
		$remove	= (isset($post[$removeVar])?$post[$removeVar]:null);

		// Previous value parameter
		$previousVar = $fieldName . '-current';
		$previous = (isset($post[$previousVar])?$post[$previousVar]:null);

		// Upload file name
		$upload = (isset($uploadFile['name'])?$uploadFile['name']:null);

		// New value
		$fileName = null;

		//Check method
		$method = '';
		$changed = false;
		if (!empty($upload))
		{
			$method = 'upload';
			$changed = ($upload != $previous);
		}

		//Check if needed to delete files
		if (in_array($remove, array('remove', 'delete', 'thumbs', 'trash')))
		{
			$fileName = "";		//Clear DB link (remove)
			$changed = true;

			//Process physical removing of the files (All, only thumbs, Move to trash)
			if (in_array($remove, array('delete', 'thumbs', 'trash')))
			{
				$f = (preg_match("/\[.+\]/", $previous)?"":$dir.DS) . $previous;
				if (!ZefaniabibleClassFile::deleteFile($f, $remove)){
					JError::raiseWarning( 4101, JText::_("ZEFANIABIBLE_TASK_RESULT_IMPOSSIBLE_TO_DELETE") );
				}
			}
		}

		switch($method)
		{
			case 'upload':

				// Process Upload
				$uploadClass = new ZefaniabibleClassFileUpload($dir);
				$uploadClass->setAllowed($extensions);

				if (!$result = $uploadClass->uploadFile($uploadFile, $options))
				{
					JError::raiseWarning( 4100, JText::sprintf("ZEFANIABIBLE_TASK_RESULT_IMPOSSIBLE_TO_UPLOAD_FILE", $uploadFile['name']) );
					return false;
				}

				$fileName = $result->filename;
				$changed = true;

				break;
		}

		if ($changed)
		{
			//Store the image file name with path pattern
			if (!$this->save(array($fieldName => $fileName)))
				return false;
		}
		return true;

	}

	/**
	* 
	*
	* @access	public
	* @param	string	$join	
	* @param	string	$type	
	* @return	void
	*/
	public function addJoin($join, $type = 'left')
	{
		$join = preg_replace("/^((LEFT)?(RIGHT)?(INNER)?(OUTER)?\sJOIN)/", "", $join);
		$this->addQuery('join.' . strtolower($type), $join);
	}

	/**
	* Concat SQL parts in query. (Suggested by Cook Self Service)
	*
	* @access	public
	* @param	string	$type	SQL command.
	* @param	string	$queryElement	Command content.
	* @return	void
	*/
	public function addQuery($type, $queryElement)
	{
		$queries = $this->getState('query.' . $type, array());
		if (!in_array($queryElement, $queries))
		{
			$queries[] = $queryElement;
			$this->setState('query.' . $type, $queries);
		}
	}

	/**
	* 
	*
	* @access	public
	* @param	string	$select	
	* @return	void
	*/
	public function addSelect($select)
	{
		$this->addQuery('select', $select);
	}

	/**
	* Check if the user can access this item.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed.
	*/
	public function canAccess($record)
	{
		if (!$this->canView($record))
			return false;


		return true;
	}

	/**
	* Check if the user is admin or manager.
	*
	* @access	public
	*
	* @return	boolean	True if user can admin all items.
	*/
	public function canAdmin()
	{
		$acl = ZefaniabibleHelper::getActions();

		if ($acl->get('core.admin'))
			return true;

		return false;
	}

	/**
	* Method to check if the item is free of checkout.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed. False if checkedout
	*/
	public function canCheck($record)
	{
		if ($this->isCheckedIn($record))
		{			
			$this->setError(JText::_("ZEFANIABIBLE_TASK_RESULT_THE_USER_CHECKING_OUT_DOES_NOT_MATCH_THE_USER_WHO_CHECKED_OUT_THE_ITEM"));
			return false;			
		}

		return true;
	}

	/**
	* Check if the user can create a new item.
	*
	* @access	public
	*
	* @return	boolean	True if allowed.
	*/
	public function canCreate()
	{
		//Facultative : Check Admin
		if ($this->canAdmin())
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Authorizated to create
		if ($acl->get('core.create'))
			return true;

		return false;
	}

	/**
	* Method to test whether a record can be deleted.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed to delete the record. Defaults to the permission for the component.
	*/
	public function canDelete($record)
	{
		//Check if already edited
		if ($this->isCheckedIn($record))
			return false;

		//Facultative : Check Admin
		if ($this->canAdmin())
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Authorizated to delete
		if ($acl->get('core.delete'))
			if ($this->isAccessible($record)) //Facultative : Check accesslevel
				return true;

		//Author can delete
		if ($acl->get('core.delete.own'))
			if ($this->isAuthor($record))
				return true;

		return false;
	}

	/**
	* Check if the user can edit the item.
	*
	* @access	public
	* @param	object	$record	A record object.
	* @param	boolean	$testNew	Check canCreate() in case of new element.
	* @param	string	$pk	Primary key name.
	*
	* @return	boolean	True if allowed.
	*/
	public function canEdit($record, $testNew = true, $pk = 'id')
	{
		//Create instead of Edit if new item
		if($testNew && empty($record->$pk))
			return self::canCreate();
		
		//Check if already edited
		if (!$this->canCheck($record))
			return false;

		//Facultative : Check Admin
		if ($this->canAdmin())
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Authorizated to edit
		if ($acl->get('core.edit'))
			if ($this->isAccessible($record)) //Facultative : Check accesslevel
				return true;

		//Author can edit
		if ($acl->get('core.edit.own'))
			if ($this->isAuthor($record))
				return true;

		return false;
	}

	/**
	* Check if the user can set default the item.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed.
	*/
	public function canEditDefault($record)
	{
		//Uses the same ACL than edit state
		return $this->canEditState();
	}

	/**
	* Check if the user can edit he published state of this item.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed.
	*/
	public function canEditState($record)
	{
		//Check if already edited
		if ($this->isCheckedIn($record))
			return false;

		//Facultative : Check Admin
		if ($this->canAdmin())
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Authorizated to change publish state
		if (!$acl->get('core.edit.state'))
			return false;

		//Facultative : Check accesslevel
		if (!$this->isAccessible($record))
			return false;

		return true;
	}

	/**
	* Check if the user can view the item.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed.
	*/
	public function canView($record)
	{
		//Check publish state
		if ($this->isVisible($record))
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Not allowed to access to own item
		if (	!$acl->get('core.view.own')
			&& 	!$acl->get('core.edit.own')
			&& 	!$acl->get('core.delete.own')){
			return false;
		}

		//Author can view
		if ($this->isAuthor($record))
			return true;

		return false;
	}

	/**
	* Clean the cache
	*
	* @access	protected
	* @param	string	$group	The cache group.
	* @param	integer	$client_id	The ID of the client.
	* @return	void
	*
	* @since	12.2
	*/
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache($group, $client_id);

		$pk = $this->getState($this->getName() . '.id');
		//Clean current item cache (Called when save succeed)
		$this->_item[$pk] = null;
	}

	/**
	* Delete the files assiciated to the items
	*
	* @access	public
	* @param	array	$pks	Ids of the items to delete the images
	* @param	array	$fileFields	Images indexes fields of the table where to find the images paths.
	*
	* @return	boolean	True on success
	*/
	public function deletefiles($pks, $fileFields)
	{
		if (!count($fileFields) || !count($pks))
			return;

		JArrayHelper::toInteger($pks);
		$db = JFactory::getDBO();

		$errors = false;
		$table = $this->getTable();

		//Get all indexes for all fields
		$query = "SELECT " . qn($db, implode(qn($db, ', '), array_keys($fileFields)))
			. " FROM " . qn($db, $table->getTableName())
			. ' WHERE id IN ( '.implode(', ', $pks) .' )';
		$db->setQuery($query);
		$files = $db->loadObjectList();

		$config	= JComponentHelper::getParams( 'com_zefaniabible' );

		foreach($fileFields as $fieldName => $op)
		{
			$dir = $config->get('upload_dir_' . $this->view_list . '_' . $fieldName, '[COM_SITE]' .DS. 'files' .DS. $this->view_list . '_' . $fieldName);


			foreach($files as $fileObj)
			{
				$imagePath = $fileObj->$fieldName;
				if (!preg_match("/\[.+\]/", $imagePath))
					$imagePath = $dir .DS. $imagePath;
				if (!ZefaniabibleClassFile::deleteFile($imagePath, $op))
					$errors = true;
			}
		}

		return !$errors;
	}

	/**
	* Temporary function, before FoF implementation. Return the table Foreign Key
	* name of a field.
	*
	* @access	public static
	* @param	string	$fieldname	FK field name
	*
	* @return	string	The table name. # is used as prefix to significate the component name table prefix.
	*
	* @since	Cook 2.6.3
	*/
	public static function fkTable($fieldname)
	{
		$tbl = '#__';
		$com = 'zefaniabible_';

		switch($fieldname)
		{
			case 'access': return $tbl. 'viewlevels';
			case 'plan': return $tbl.$com. 'zefaniareading';
			case 'bible_version': return $tbl.$com. 'biblenames';
			case 'user_id': return $tbl. 'users';	
		}
	}

	/**
	* Method to get the form.
	*
	* @access	public
	* @param	array	$data	An optional array of data for the form to interrogate.
	* @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	* @param	string	$control	The name of the control group.
	*
	* @return	JForm	A JForm object on success, false on failure
	*
	* @since	11.1
	*/
	public function getForm($data = array(), $loadData = true, $control = 'jform')
	{
		$form = $this->loadForm($this->context, $this->view_item, array('control' => $control,'load_data' => $loadData));
		if (empty($form))
			return false;

		$form->addRulePath(JPATH_ADMIN_ZEFANIABIBLE .DS. 'models' .DS . 'rules');

		$id = $this->getState($this->getName() . '.id');
		$item = $this->_item[(int)$id];

		$this->populateParams($item);
		$this->populateObjects($item);

		return $form;
	}

	/**
	* Method to get the id.
	*
	* @access	public
	*
	* @return	int	The item id. Null if no item loaded.
	*
	* @since	11.1
	*/
	public function getId()
	{
		if (isset($this->_item))
			return $this->getState($this->getName() . '.id');

		return 0;
	}

	/**
	* Method to get an item data.
	*
	* @access	public
	* @param	integer	$pk	The primary id key of the item
	*
	* @return	mixed	Item data object on success, false on failure.
	*/
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {

			try
			{
				if (empty($pk))
					$data = new stdClass();
				else
				{
					//Increment the hits if needed
					$this->hit();


					$db = $this->getDbo();
					$query = $db->getQuery(true);

					//Preparation of the query
					$this->prepareQuery($query, $pk);

					$db->setQuery($query);

					$data = $db->loadObject();

					if ($error = $db->getErrorMsg()) {
						throw new Exception($error);
					}
				}

				if (empty($data)) {
					$this->setError(JText::_('JGLOBAL_RESOURCE_NOT_FOUND'));
					return;
				}

				$this->populateParams($data);
				$this->populateObjects($data);

				$this->_item[$pk] = $data;

			}
			catch (JException $e)
			{
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else {
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	* Returns the alias of the list model.
	*
	* @access	public
	* @return	void
	*
	* @since	Cook 2.0
	*/
	public function getNameList()
	{
		return $this->viewList;
	}

	/**
	* A protected method to get a set of ordering conditions.
	*
	* @access	protected
	* @param	JTable	$table	A JTable object.
	*
	* @return	mixed	An array of conditions or a string to add to add to ordering queries.
	*
	* @since	12.2
	*/
	protected function getReorderConditions($table)
	{
		return array();
	}

	/**
	* Method to increment hits when necessary (check session and layout)
	*
	* @access	public
	* @param	array	$layouts	List of authorized layouts for hitting the object
	*
	* @return	boolean	Null if skipped. True when incremented. False if error.
	*/
	public function hit($layouts = null)
	{
		//Not been overrided in this model (no hit function)
		if (!$layouts)
			return;

		$name = $this->getName();
		$context = $this->getState('context');

		//Search if this item is requested from an item layout
		$found = false;
		foreach($layouts as $layout)
			if ($context == ($name . '.' . $layout))
				$found = true;

		//This layout is not an item layout context
		if (!$found)
			return;

		//Search if the user already loaded this item.
		$id = $this->getState($name . '.id');

		$app = JFactory::getApplication();
		$hits = $app->getUserState($this->context . '.hits', array());


		//This item has already been seen during this session
		if (in_array($id, $hits))
			return;

		$hits[] = $id;

		//Increment the hits
		$table = $this->getTable();
		if (!$table->hit($id))
			return false;

		$app->setUserState($this->context . '.hits', $hits);

		return true;
	}

	/**
	* Method to cascad delete items.
	*
	* @access	public
	* @param	string	$key	The foreign key which relate to the cids.
	* @param	array	$cid	The deleted ids of foreign table.
	*
	* @return	boolean	True on success
	*/
	public function integrityDelete($key, $cid = array())
	{
		if (count( $cid ))
		{
			$db = $this->_db;
			$table = $this->getTable();
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'SELECT id FROM ' . qn($db, $table->getTableName())
				. " WHERE `" . $key . "` IN ( " . $cids . " )";
			$db->setQuery($query);
			$list = $db->loadObjectList();

			$cidsDelete = array();
			if (count($list) > 0)
				foreach($list as $item)
					$cidsDelete[] = $item->id;

			//using the model, the integrities can be chained.
			return $this->delete($cidsDelete);

		}

		return true;
	}

	/**
	* Method to reset foreign keys.
	*
	* @access	public
	* @param	string	$key	The foreign key which relate to the cids.
	* @param	array	$cid	The deleted ids of foreign table.
	*
	* @return	boolean	True on success
	*/
	public function integrityReset($key, $cid = array())
	{
		if (count( $cid ))
		{
			$db = $this->_db;
			$table = $this->getTable();

			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'UPDATE ' . qn($db, $table->getTableName())
				.	' SET ' . qn($db, $key) . ' = 0'
				. ' WHERE ' . qn($db, $key) . ' IN ( ' . $cids . ' )';
			$db->setQuery( $query );

			if(!$db->query()) {
				JError::raiseWarning(1100, $db->getErrorMsg());
				return false;
			}
		}
		return true;
	}

	/**
	* Method to check accesslevel.
	*
	* @access	public
	* @param	object	$record	A record object.
	* @param	string	$accessKey	The access level field name.
	*
	* @return	boolean	True if allowed.
	*/
	public function isAccessible($record, $accessKey = 'access')
	{
		//Accesslevels are not instancied
		if (!property_exists($record, $accessKey))
			return true;

		//User group affiliations permits to access		
		if (in_array($record->$accessKey, JFactory::getUser()->getAuthorisedViewLevels()))
			return true;

		return false;
	}

	/**
	* Method to check is the current user is the author (or can be the author).
	*
	* @access	public
	* @param	object	$record	A record object.
	* @param	string	$authorKey	The authoring field name.
	*
	* @return	boolean	True if allowed.
	*/
	public function isAuthor($record, $authorKey = 'created_by')
	{
		//Authoring is not used
		if (!property_exists($record, $authorKey))
			return true;

		//Author is not defined
		if (empty($record->$authorKey))
			return false;

		//Current user is author
		if ($record->$authorKey == JFactory::getUser()->get('id'))
			return true;

		return false;
	}

	/**
	* Method to check if item has already been opened.
	*
	* @access	public
	* @param	object	$record	A record object.
	* @param	string	$checkedKey	The check out field name.
	*
	* @return	boolean	True if allowed.
	*/
	public function isCheckedIn($record, $checkedKey = 'checked_out')
	{
		if (	property_exists($record, $checkedKey)
			&& 	!empty($record->$checkedKey)
			&& 	$record->$checkedKey != JFactory::getUser()->get('id')){
			return true;
		}

		return false;
	}

	/**
	* Method to check if then item can be seen, basing on publish state.
	*
	* @access	public
	* @param	object	$record	A record object.
	* @param	string	$publishKey	The publish state field name.
	*
	* @return	boolean	True if allowed.
	*/
	public function isPublished($record, $publishKey = 'published')
	{
		//Published states are not instancied
		if (!property_exists($record, $publishKey))
			return true;

		$acl = ZefaniabibleHelper::getActions();

		//Who can change state can always see all.
		if ($acl->get('core.edit.state'))
			return true;

		//Published state is not defined
		if ($record->$publishKey === null)
			return true;

		//Published item
		if ($record->$publishKey == 1)
			return true;

		return false;
	}

	/**
	* Method to check the visibility of the item.
	*
	* @access	public
	* @param	object	$record	A record object.
	*
	* @return	boolean	True if allowed.
	*/
	public function isVisible($record)
	{
		if (!$this->isAccessible($record))
			return false;

		if (!$this->isPublished($record))
			return false;

		return true;
	}

	/**
	* Method to get a form object.
	*
	* @access	protected
	* @param	string	$name	The name of the form.
	* @param	string	$source	The form source. Can be XML string if file flag is set to false.
	* @param	array	$options	Optional array of options for the form creation.
	* @param	boolean	$clear	Optional argument to force load a new form.
	* @param	string	$xpath	An optional xpath to search for the fields.
	*
	* @return	mixed	returnDesc.
	*
	* @since	12.2
	*/
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}

	/**
	* Load a N:x relation list to objects array in the item.
	*
	* @access	public
	* @param	object	&$item	The item to populate.
	* @param	string	$objectField	The item property name used for this list.
	* @param	string	$xrefTable	Cross Reference (Xref) table handling this link.
	* @param	string	$on	The FK fieldname from Xref pointing to the origin
	* @param	string	$key	The ID fieldname from Origin.
	* @param	array	$states	Cascad states followers, for recursive objects.
	* @param	string	$context	SQL predefined query
	* @return	void
	*
	* @since	Cook 2.6.3
	*/
	public function loadXref(&$item, $objectField, $xrefTable, $on, $key, $states = array(), $context = 'object.default')
	{
		$db = JFactory::getDbo();

		if ($this->getState('xref.' . $objectField))
		{
			$model = CkJModel::getInstance($xrefTable, 'zefaniabibleModel');
	
			// Prepare the fields to load, trough a context profile
			$model->setState('context', $context);
	
			// Filter on the origin
			$model->addWhere(qn($db, $on) . '='. (int)$item->$key);

			// Cascad objects states
			// Apply the namespaced states to the relative base namespace
			if (count($states))
				foreach($states as $state)
				{
					if ($val = $this->getState('xref.' . $objectField . '.' . $state))
						$model->setState('xref.' . $state, $val);
				}
	
			// Set up the array in the item.
			$item->$objectField = $model->getItems();
		}
	}

	/**
	* Prepare some additional derivated objects.
	*
	* @access	public
	* @param	object	&$item	The object to populate.
	* @return	void
	*/
	public function populateObjects(&$item)
	{

	}

	/**
	* Prepare some additional important values.
	*
	* @access	public
	* @param	object	&$item	The object to populate.
	* @return	void
	*/
	public function populateParams(&$item)
	{
		if (!$item)
			return;

		$item->params = new JObject();

		if ($this->canView($item))
			$item->params->set('access-view', true);

		if ($this->canEdit($item))
			$item->params->set('access-edit', true);

		if ($this->canDelete($item))
			$item->params->set('access-delete', true);

	}

	/**
	* Method to auto-populate the model state.
	*
	* @access	public
	* @param	string	$ordering	
	* @param	string	$direction	
	* @return	void
	*/
	public function populateState($ordering = null, $direction = null)
	{
		// Load id from array from the request.
		$jinput = JFactory::getApplication()->input;

		//1. First read the state var
		//2. Then read from Request
		//3. Finally search if cid is an array var (in request)
		$id = $this->state->get($this->getName() . '.id', 
			$jinput->get('id', 
				$jinput->get('cid', null, 'ARRAY')
				, 'ARRAY'));

		if (is_array($id))
			$id = $id[0];

		//assure compatibility when cid is received instead of id
		$jinput->set('id', $id);

		parent::populateState($ordering, $direction);

		if (defined('JDEBUG'))
			$_SESSION["Zefaniabible"]["Model"][$this->getName()]["State"] = $this->state;

	}

	/**
	* Prepare the query for filtering accesses. Can be used on foreign keys.
	*
	* @access	protected
	* @param	varchar	$table	The table alias (_tablealias_).
	* @param	varchar	&$whereAccess	The returned SQL access filter. Set to true to activate it.
	* @param	varchar	&$wherePublished	The returned SQL published filter. Set to true to activate it.
	* @param	varchar	&$allowAuthor	The returned SQL to allow author to pass. Set to true to activate it.
	* @return	void
	*/
	protected function prepareQueryAccess($table = 'a', &$whereAccess = null, &$wherePublished = null, &$allowAuthor = null)
	{
		$acl = ZefaniabibleHelper::getActions();

		// Must be aliased ex : _tablename_
		if ($table != 'a')
			$table = '_' . trim($table, '_') . '_';


		// ACCESS - View Level Access
		if ($whereAccess)
		{
			// Select fields requirements
			if ($table != 'a')
				$this->addSelect($table . '.access AS `' . $table . 'access`');	

			$whereAccess = '1';
			if (!$this->canAdmin())
			{	
			    $groups	= implode(',', JFactory::getUser()->getAuthorisedViewLevels());
				$whereAccess = $table . '.access IN ('.$groups.')';
			}
		}

		// ACCESS - Author
		if ($allowAuthor)
		{
			// Select fields requirements
			if ($table != 'a')
				$this->addSelect($table . '.created_by AS `' . $table . 'created_by`');

			$allowAuthor = '0';
			//Allow the author to see its own unpublished/archived/trashed items
			if ($acl->get('core.edit.own') || $acl->get('core.view.own') || $acl->get('core.delete.own'))
				$allowAuthor = $table . '.created_by = ' . (int)JFactory::getUser()->get('id');
		
		}

		// ACCESS - Publish state
		if ($wherePublished)
		{
			// Select fields requirements
			if ($table != 'a')
				$this->addSelect($table . '.published AS `' . $table . 'published`');

			$wherePublished = '(' . $table . '.published = 1 OR ' . $table . '.published IS NULL)'; //Published or undefined state
			//Allow some users to access (core.edit.state)
			if ($acl->get('core.edit.state'))
				$wherePublished = '1'; //Do not filter
		}

		// Fallback values
		if (!$whereAccess)
			$whereAccess = '1';

		if (!$allowAuthor)
			$allowAuthor = '0';

		if (!$wherePublished)
			$wherePublished = '1';
	}

	/**
	* This feature is the blueprint of ORM-kind feature. It create the optimized
	* SQL query for mounting an object, including foreign links.
	*
	* @access	public
	* @param	array	$headers	The header structure. see:https://www.akeebabackup.com/documentation/fof/common-fields-for-all-types.html
	* @return	void
	*
	* @since	Cook 2.6.3
	*/
	public function prepareQueryHeaders($headers)
	{
		if (!count($headers))
			return;

		$db = JFactory::getDbo();

		foreach($headers as $namespace => $header)
		{
			// the namespace is used to localize the foreign key path
			$fieldAlias = $namespace = $header['name'];
			if (isset($header['namespace']))
				$namespace = $header['namespace'];

			$parts = explode('.' ,$namespace);
			$isFk = (count($parts) > 1);


			// Physical field name is always the last part
			$fieldname = $parts[count($parts)-1];
			$current = $parts[0];

			$parentTable = 'a';

			for($i = 0 ; $i < (count($parts)) ; $i++)
			{
				$isLast = ($i == (count($parts) - 1));
				$current = $parts[$i];

				// Select the field
				if ($isLast)
					break;

				$tableName = self::fkTable($current);
				$tableAlias = '_' . $current . '_';
		
				// Join the required tables
				$this->addJoin(qn($db, $tableName) 
					.	' AS ' . $tableAlias
					.	' ON ' . $tableAlias . '.id'
					.	' = ' . $parentTable . '.' . $current
	
					, 'LEFT');

				$parentTable = $tableAlias;
			}

			// Instance the field in query
			$this->addSelect($parentTable .'.'. $current . ' AS ' . qn($db, $fieldAlias));
		}
	}

	/**
	* Method to allow derived classes to preprocess the form.
	*
	* @access	protected
	* @param	JForm	$form	A JForm object.
	* @param	mixed	$data	The data expected for the form.
	* @param	string	$group	The name of the plugin group to import (defaults to "content").
	* @return	void
	*
	* @since	12.2
	*/
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		$baseFolder = JPATH_COMPONENT .DS. 'fork' .DS. 'models' .DS. 'forms';
		$formFile = $baseFolder .DS. $this->view_item .'.xml';
		if (file_exists($formFile))
		{		
			$xml = simplexml_load_file($formFile);
			$form->load($xml, true);			
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	* Saves the manually set order of records.
	*
	* @access	public
	* @param	array	$pks	An array of primary key ids.
	* @param	integer	$order	+1 or -1
	* @param	string	$where	The stringified condifions for ordering.
	*
	* @return	boolean	True on success.
	*
	* @since	12.2
	*/
	public function saveorder($pks = null, $order = null, $where = null)
	{
		$table = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		// Update ordering values
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			if (!$this->canEdit($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);
				JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			}

			elseif (isset($order[$i]) && $table->ordering != $order[$i])
			{
		
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				if ($where)
					$condition = array($where);
				else
					$condition = $this->getReorderConditions($table);


				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

	/**
	* Method to set model state variables. Update local vars.
	*
	* @access	public
	* @param	string	$property	The name of the property.
	* @param	mixed	$value	The value of the property to set or null.
	*
	* @return	mixed	The previous value of the property or null if not set.
	*
	* @since	11.1
	*/
	public function setState($property, $value = null)
	{
		return $this->state->set($property, $value);
	}

	/**
	* Method to toggle a value, including integer values
	*
	* @access	public
	* @param	string	$fieldName	The field to increment.
	* @param	integer	$pk	The id of the item.
	* @param	integer	$max	Max possible values (modulo). Reset to 0 when the value is superior to max.
	*
	* @return	boolean	True when changed. False if error.
	*/
	public function toggle($fieldName, $pk = null, $max = 1)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');

		$table = $this->getTable();
		if (!$table->toggle($fieldName, $pk, $max))
		{
			JError::raiseWarning(1106, JText::sprintf("ZEFANIABIBLE_MODEL_IMPOSSIBLE_TO_TOGGLE", $fieldName));
			return false;
		}

		return true;
	}

	/**
	* Method to validate the form data. 
	*  This override handle the inputs of files types, (Joomla issue when they
	* are required)
	*
	* @access	public
	* @param	object	$form	The form to validate against.
	* @param	array	$data	The data to validate.
	* @param	string	$group	The name of the field group to validate.
	*
	* @return	mixed	Array of filtered data if valid, false otherwise.
	*/
	public function validate($form, $data, $group = null)
	{
		//Get the posted files if this model is concerned by files submission
		// JOOMLA FIX : if missing fields in $_POST -> issue in partial update when required
		$currentData = $this->getItem();
		foreach($currentData as $fieldName => $value)
		{
			$field = $form->getField($fieldName, $group, $value);

			//Skip the ID data (and other fields not in the form)
			if (!$field)
				continue;

			//Missing in $_POST and required
			if (!in_array($fieldName, array_keys($data)) && $field->required)
				//Insert the current object value. (UPDATE)
				$data[$fieldName] = $currentData->$fieldName;
		}


		//JOOMLA FIX : Reformate some field types not handled properly
		foreach($form->getFieldset($group) as $field)
		{
			$value = null;
			if (isset($data[$field->fieldname]))
				$value = $data[$field->fieldname];

			switch($field->type)
			{
				//JOOMLA FIX : Reformate the date/time format comming from the post
				case 'ckcalendar':

					//cimport('helpers.dates');

					if ($value && (string)$field->format && !ZefaniabibleHelperDates::isNull((string)$value) )
					{
						$time = ZefaniabibleHelperDates::getSqlDate($value, array($field->format));
						if ($time === null){
							JError::raiseWarning(1203, JText::sprintf('ZEFANIABIBLE_VALIDATOR_WRONG_DATETIME_FORMAT_FOR_PLEASE_RETRY', $field->label));
							$valid = false;
						}
						else
							$data[$field->fieldname] = ZefaniabibleHelperDates::toSql($time);
					}
					break;


				//JOOMLA FIX : Apply a null value if the field is in the form
				case 'ckcheckbox':
					if (!$value)
						$data[$field->fieldname] = 0;
					break;
			}
		}


		// JOOMLA FIX : always missing file names in $_POST -> issue when required
		//Get the posted files if this model is concerned by files submission
		if (count($this->fileFields))
		{
			$fileInput = new JInput($_FILES);
			$files = $fileInput->get('jform', null, 'array');

			if (count($files['name']))
			foreach($files['name'] as $fieldName => $value)
			{
				//For required files, temporary insert the value comming from $_FILES
				if (!empty($value))
				{
					$field = $form->getField($fieldName, $group);
					if ($field->required)
						$data[$fieldName] = $value;
				}
			}
		}

		//Exec the native PHP validation (rules)
		$result = parent::validate($form, $data, $group);

		//check the result before to go further
		if ($result === false)
			return false;

		//ID key follower (in some case, ex : save2copy task)
		if (isset($data['id']))
			$result['id'] = $data['id'];

		return $result;
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassModelItem')){ class ZefaniabibleClassModelItem extends ZefaniabibleCkClassModelItem{} }

