<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
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
* Zefaniabible Zefaniareadingdetailsitem Controller
*
* @package	Zefaniabible
* @subpackage	Zefaniareadingdetailsitem
*/
class ZefaniabibleCkControllerZefaniareadingdetailsitem extends ZefaniabibleClassControllerItem
{
	/**
	* The context for storing internal data, e.g. record.
	*
	* @var string
	*/
	protected $context = 'zefaniareadingdetailsitem';

	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniareadingdetailsitem';

	/**
	* The URL view list variable.
	*
	* @var string
	*/
	protected $view_list = 'zefaniareadingdetails';

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
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			case 'modal.add':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
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
			case 'addreadingdetails.cancel':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
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
					'com_zefaniabible.zefaniareadingdetails.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'addreadingdetails.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'modal.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
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
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			case 'modal.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
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
			return 'addreadingdetails';

		if ($default)
			return 'addreadingdetails';

		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'addreadingdetails', 'CMD');
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
			case 'addreadingdetails.save':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'addreadingdetails.apply':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetailsitem.addreadingdetails'
				), array(
					'cid[]' => $model->getState('zefaniareadingdetailsitem.id')
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniareadingdetails.default'
				));
				break;
		}
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleControllerZefaniareadingdetailsitem')){ class ZefaniabibleControllerZefaniareadingdetailsitem extends ZefaniabibleCkControllerZefaniareadingdetailsitem{} }

