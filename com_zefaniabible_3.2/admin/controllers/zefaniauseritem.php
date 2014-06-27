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
* Zefaniabible Zefaniauseritem Controller
*
* @package	Zefaniabible
* @subpackage	Zefaniauseritem
*/
class ZefaniabibleCkControllerZefaniauseritem extends ZefaniabibleClassControllerItem
{
	/**
	* The context for storing internal data, e.g. record.
	*
	* @var string
	*/
	protected $context = 'zefaniauseritem';

	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniauseritem';

	/**
	* The URL view list variable.
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
		parent::__construct($config);
		$app = JFactory::getApplication();
		$this->registerTask('toggle_send_reading_plan_email', 'toggle');
		$this->registerTask('toggle_send_verse_of_day_email', 'toggle');
	}

	/**
	* Method to add an element.
	*
	* @access	public
	* @return	void
	*/
	public function add()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		$this->_result = $result = parent::add();
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'default.add':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
			
				));
				break;

			case 'modal.add':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				));
				break;
		}
	}

	/**
	* Method to cancel an element.
	*
	* @access	public
	* @return	void
	*/
	public function cancel()
	{
		$this->_result = $result = parent::cancel();
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'adduser.cancel':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				));
				break;
		}
	}

	/**
	* Method to delete an element.
	*
	* @access	public
	* @return	void
	*/
	public function delete()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		$this->_result = $result = parent::delete();
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'default.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'adduser.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'modal.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				));
				break;
		}
	}

	/**
	* Method to edit an element.
	*
	* @access	public
	* @return	void
	*/
	public function edit()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		$this->_result = $result = parent::edit();
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
			
				));
				break;

			case 'modal.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				));
				break;
		}
	}

	/**
	* Return the current layout.
	*
	* @access	protected
	* @param	bool	$default	If true, return the default layout.
	*
	* @return	string	Requested layout or default layout
	*/
	protected function getLayout($default = null)
	{
		if ($default === 'edit')
			return 'adduser';

		if ($default)
			return 'adduser';

		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'adduser', 'CMD');
	}

	/**
	* Method to save an element.
	*
	* @access	public
	* @return	void
	*/
	public function save()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		//Check the ACLs
		$model = $this->getModel();
		$item = $model->getItem();
		$result = false;
		if ($model->canEdit($item, true))
		{
			$result = parent::save();
			//Get the model through postSaveHook()
			if ($this->model)
			{
				$model = $this->model;
				$item = $model->getItem();	
			}
		}
		else
			JError::raiseWarning( 403, JText::sprintf('ACL_UNAUTORIZED_TASK', JText::_('ZEFANIABIBLE_JTOOLBAR_SAVE')) );

		$this->_result = $result;

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'adduser.save':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'adduser.apply':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauseritem.adduser'
				), array(
					'cid[]' => $model->getState('zefaniauseritem.id')
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				));
				break;
		}
	}

	/**
	* Method to toggle a field value.
	*
	* @access	public
	* @return	void
	*/
	public function toggle()
	{
		CkJSession::checkToken() or CkJSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
		$this->_result = $result = $this->_toggle(array(
			'toggle_send_reading_plan_email' => 'send_reading_plan_email',
			'toggle_send_verse_of_day_email' => 'send_verse_of_day_email'
		));
		$model = $this->getModel();

		//Define the redirections
		switch($this->getLayout() .'.'. $this->getTask())
		{
			case 'default.toggle':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'default.toggle':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniauser.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'stay'
				));
				break;
		}
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleControllerZefaniauseritem')){ class ZefaniabibleControllerZefaniauseritem extends ZefaniabibleCkControllerZefaniauseritem{} }

