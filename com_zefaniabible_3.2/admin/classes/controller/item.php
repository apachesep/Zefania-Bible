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

jimport('joomla.application.component.controllerform');


/**
* Zefaniabible  Controller
*
* @package	Zefaniabible
* @subpackage	
*/
class ZefaniabibleCkClassControllerItem extends JControllerForm
{
	/**
	* Result of the task execution.
	*
	* @var mixed
	*/
	protected $_result;

	/**
	* The returned model after save() (proposed by Cook Self Service).
	*
	* @var JModel
	*/
	protected $model;

	/**
	* The redirector (proposed by Cook Self Service).
	*
	* @var string
	*/
	protected $redirector;

	/**
	* The prefix to use with controller messages.
	*
	* @var string
	*/
	protected $text_prefix = 'COM_ZEFANIABIBLE';

	/**
	* Constructor
	*
	* @access	public
	* @return	void
	*/
	public function __construct()
	{
		parent::__construct();

	}

	/**
	* Method to toggle a field value.
	*
	* @access	protected
	* @param	array	$authorizatedTasks	An associative array receiving the possible tasks and associated fields to toggle.
	*
	* @return	boolean	True if success.
	*/
	protected function _toggle($authorizatedTasks = array())
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		$result = false;
		if (!empty($authorizatedTasks))
		{
			$jinput = JFactory::getApplication()->input;
			$task = $jinput->get('task', null, 'CMD');
			$model = $this->getModel();
	
			//Check the ACLs
			$item = $model->getItem();
			$result = false;
			if ($model->canEdit($item) && isset($authorizatedTasks[$task]))
			{
				$fieldName = $authorizatedTasks[$task];
				$result = $model->toggle($fieldName);				
			}
		}

		//Set default redirection
		$this->setRedirect(
		JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(), false
			)
		);

		return $result;
	}

	/**
	* Method to send some filter values to the item.
	*
	* @access	public
	* @return	void
	*/
	public function add()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		if (!$this->allowAdd())
		{
			$result = false;
		}
		else
			$result = parent::add();

		//This redirection is also applying the filters followers used when a new item is created
		$this->applyRedirection($result, array(
			'com_zefaniabible.' . $this->view_list . '.default',
			'com_zefaniabible.' . $this->view_item . '.' . $this->getLayout('edit')
		));
	}

	/**
	* Check if the user can insert a new item
	*
	* @access	protected
	* @param	array	$data	An array of input data.
	* @param	string	$key	The name of the key for the primary key; default is id..
	*
	* @return	boolean	True on success
	*/
	protected function allowAdd($data = array(), $key = 'id')
	{
		$model = $this->getModel();
		return $model->canCreate();
	}

	/**
	* Check if the user can edit this item
	*
	* @access	protected
	* @param	array	$data	An array of input data.
	* @param	string	$key	The name of the key for the primary key; default is id..
	*
	* @return	boolean	True on success
	*/
	protected function allowEdit($data = array(), $key = 'id')
	{
		$model = $this->getModel();
		$model->setState($this->view_item . ".id", $data[$key]);
		$item = $model->getItem();
		
		return $model->canEdit($item);
	}

	/**
	* Customize the redirection depending on result.
	* (proposed by Cook Self Service).
	*
	* @access	protected
	* @param	mixed	$result	bool or integer. The result from  the task operation
	* @param	array	$redirections	The redirections (option.view.layout) ordered by task result [0,1,...]
	* @param	array	$vars	Eventual added vars to the redirection.
	*
	* @return	void	
	* @return	void
	*/
	protected function applyRedirection($result, $redirections, $vars = array())
	{
		if ($result === null)
			$result = 1;
		else
			$result = (int)$result;

		if (!$this->_result)
			$this->_result = $result;

		if (!isset($redirections[$result]))
			return;		//Keep the default redirection

		//Get the selected redirection depending on result
		$redirection = $redirections[$result];
		switch($redirection)
		{
			//Stay on the same page
			case 'stay':
				$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
				return;
				break;
		
			//Return to the previous page in navigation history
			case 'previous':
				//TODO
				break;
		}
		$url = explode(".", $redirection);

		//Get from given url parts (empty string will keep the current value)
		if (isset($url[0]))
			$values['option']	= (!empty($url[0])?$url[0]:$this->option);

		if (isset($url[1]))
			$values['view'] 	= (!empty($url[1])?$url[1]:$this->view_list);

		if (isset($url[2]))
			$values['layout']	= (!empty($url[2])?$url[2]:$this->getLayout(true));

		$jinput = JFactory::getApplication()->input;


		//Followers : If value is defined in the current form, it will be added in the request
		$followers = array(	'cid' => 'ARRAY',
							'tmpl' => 'CMD',
							'Itemid' => 'CMD',
							'lang' => 'CMD');


		//Filters followers
		$model = CkJModel::getInstance($this->view_list, 'ZefaniabibleModel');
		if ($model)
		{
			$filters = $model->get('filter_vars');
			foreach($filters as $filterName => $type)
			{
				$type = 'STRING'; //When filter is empty, don't follow, so FILTER is not used.
				$filterVar = 'filter_' . $filterName;
				//Adds a filter follower
				$followers[$filterVar] = $type;
			}
		}

		//Apply the followers values
		foreach($followers as $varName => $varType)
		{
			if($pos = strpos($varType, ":"))
				$varType = substr($varType, 0, $pos);

			$value = $jinput->get($varName, '', strtoupper($varType));
			if (($varType == 'ARRAY') && !empty($value))
			{
				$value = implode(",", $value);
				$varName .= "[]";
			}

			if ($value != '')
				$values[$varName] = $value;
		}

		//Override with vars in params
		foreach($vars as $key => $value)
			$values[$key] = $value;

		//Prepare the url
		foreach($values as $key => $value)
			if ($value !== null)
				$parts[] = $key . '=' . $value;

		//Apply redirection
		$this->setRedirect(
			JRoute::_("index.php?" . implode("&", $parts), false)
		);
	}

	/**
	* Method to cancel an edit. HACK to fix a Joomla issue
	*
	* @access	public
	* @param	string	$key	The name of the primary key of the URL variable.
	*
	* @return	boolean	True if access level checks pass, false otherwise.
	*/
	public function cancel($key = null)
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		$model = $this->getModel();
		$table = $model->getTable();
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";

		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		$recordId = $jinput->get($key, null, 'INT');

		// Attempt to check-in the current record.
		if ($recordId)
		{
			// Check we are holding the id in the edit list.
			if (!$this->checkEditId($context, $recordId))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
				$this->setMessage($this->getError(), 'error');

				$this->_result = false;
		
				//This redirection is also applying the followers vars
				$this->applyRedirection(null, array(
					'com_zefaniabible.' . $this->view_list . '.default'
				));

				return false;
			}

			if ($checkin)
			{
				if ($model->checkin($recordId) === false)
				{

		// JOOMLA FIX : When an item cannot be checked in because the user is not allowed, do not show any error
		// Used for the Fly layouts that can be opened, even when checked out
					/*
					// Check-in failed, go back to the record and display a notice.
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
					$this->setMessage($this->getError(), 'error');


					//Redirect to item
					$this->applyRedirection(null, array(
						'com_zefaniabible.' . $this->view_item . '.' . $this->getLayout('edit')
					));

					*/
	
					$this->_result = false;
					//Redirect to list
					$this->applyRedirection(null, array(
						'com_zefaniabible.' . $this->view_list . '.default'
					));

					return false;
				}
			}
		}

		// Clean the session data and redirect.
		$this->releaseEditId($context, $recordId);
		$app->setUserState($context . '.data', null);

		$this->_result = true;
		//Redirect to list
		$this->applyRedirection(null, array(
			'com_zefaniabible.' . $this->view_list . '.default'
		));
		
		return true;
	}

	/**
	* Method to check whether an ID is in the edit list.
	*
	* @access	public
	* @param	string	$context	ACL(s) ex: 'core.edit', 'core.edit.own', 'access-edit'
	* @param	integer	$id	Name of the task to process (Used for alerts)
	*
	* @return	boolean	Always true in order to disable this functionality
	*/
	public function checkEditId($context, $id)
	{
		return true;

		//		Disabled feature
		//		return parent::checkEditId($context, $id);
	}

	/**
	* Method to delete an item
	*
	* @access	public
	*
	* @return	boolean	True on success.
	*/
	public function delete()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		$result = false;
		$jinput = JFactory::getApplication()->input;
		$cid = $jinput->get('cid', array(), 'ARRAY');
		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		else
		{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->delete($cid))
			{
				$result = true;
				$this->setMessage(JText::plural('ZEFANIABIBLE_ITEMS_SUCCESSFULLY_DELETED', count($cid)));
			}
			else
			{
				$this->setMessage($model->getError());
			}
		}

		$this->_result = $result;
		//Redirect to list
		$this->applyRedirection(null, array(
			'com_zefaniabible.' . $this->view_list . '.default'
		));

		return $result;
	}

	/**
	* Method to edit an existing record. HACK to fix a Joomla issue
	*
	* @access	public
	* @param	string	$key	The name of the primary key of the URL variable.
	* @param	string	$urlVar	The name of the URL variable if different from the primary key.
	*
	* @return	boolean	True if access level check and checkout passes, false otherwise.
	*/
	public function edit($key = null, $urlVar = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();

		$jinput = JFactory::getApplication()->input;
		$cid = $jinput->get('cid', array(), 'ARRAY');
		$context = "$this->option.edit.$this->context";

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		// Get the previous record id (if any) and the current record id.
		$recordId = (int) (count($cid) ? $cid[0] : $jinput->get($urlVar, null, 'INT'));
		$checkin = property_exists($table, 'checked_out');

		// Access check.
		if (!$this->allowEdit(array($key => $recordId), $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->_result = false;
	
			//Redirect to list
			$this->applyRedirection(null, array(
				'com_zefaniabible.' . $this->view_list . '.default'
			));


			return false;
		}

		// Attempt to check-out the new record for editing and redirect.
		if ($checkin && !$model->checkout($recordId))
		{
			// Check-out failed, display a notice but allow the user to see the record.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->_result = false;
	
			//Redirect to list
			$this->applyRedirection(null, array(
				'com_zefaniabible.' . $this->view_list . '.default'
			));


			return false;
		}
		else
		{
			// Check-out succeeded, push the new record id into the session.
			$this->holdEditId($context, $recordId);
			$app->setUserState($context . '.data', null);

			$this->_result = true;
	
			//Redirect to item
			$this->applyRedirection(null, array(
				'com_zefaniabible.' . $this->view_item . '.' . $this->getLayout('edit')
			));


			return true;
		}
	}

	/**
	* Method to get the requested id.
	*
	* @access	public
	*
	* @return	int	zero on failure.
	*/
	public function getId()
	{
		$jinput = JFactory::getApplication()->input;
		$cid	= $jinput->get('cid', array(), 'ARRAY');
		if (count($cid))
			return $cid[0];

		return 0;
	}

	/**
	* Gets the URL arguments to append to an item redirect.
	*
	* @access	public
	* @param	integer	$recordId	The primary key id for the item.
	* @param	string	$urlVar	The name of the URL variable for the id.
	*
	* @return	string	The arguments to append to the redirect URL.
	*
	* @since	11.1
	*/
	public function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		return '&layout=' . $this->getLayout('edit') . '&cid=' . $recordId;
	}

	/**
	* Gets the URL arguments to append to a list redirect.
	*
	* @access	public
	*
	* @return	string	The arguments to append to the redirect URL.
	*
	* @since	11.1
	*/
	public function getRedirectToListAppend()
	{
		return '&layout=default';
	}

	/**
	* Function that allows child controller access to model data after the data
	* has been saved.
	*
	* @access	protected
	* @param	JModel	$model	The data model object.
	* @param	array	$validData	The validated data.
	* @return	void
	*/
	protected function postSaveHook($model, $validData = array())
	{
		$this->model = $model;
	}

	/**
	* the browser or returns false if no redirect is set.
	*
	* @access	public
	*
	* @return	boolean	False if no redirect exists.
	*
	* @since	11.1
	*/
	public function redirect()
	{
		if ($this->redirect)
		{
			$jinput = new JInput;

			//Return JSON response
			if ($jinput->get('return') == 'json')
			{
				ZefaniabibleClassAjax::responseJson(array(
					'result' => (isset($this->_result)?$this->_result:1),
					'redirect' => $this->redirect,
				));				
			}

			$app = JFactory::getApplication();
			$app->redirect($this->redirect, $this->message, $this->messageType);
		}

		return false;
	}

	/**
	* Method to save a record.
	*
	* @access	public
	* @param	string	$key	The name of the primary key of the URL variable.
	* @param	string	$urlVar	The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	*
	* @return	boolean	True if successful, false otherwise.
	*
	* @since	Cook 2.5
	*/
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$data  = $app->input->post->get('jform', array(), 'array');
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		$recordId = $app->input->getInt($urlVar);

		if (!$this->checkEditId($context, $recordId))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if ($checkin && $model->checkin($data[$key]) === false)
			{
				// Check-in failed. Go back to the item and display a notice.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');

				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data[$key] = 0;
	
			//Cook override
			$model->setState($model->getName() . '.id', 0);

			$task = 'apply';
		}

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item
						. $this->getRedirectToItemAppend(null, $urlVar), false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
					)
				);
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassControllerItem')){ class ZefaniabibleClassControllerItem extends ZefaniabibleCkClassControllerItem{} }

