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
* Zefaniabible Zefaniabibleitem Controller
*
* @package	Zefaniabible
* @subpackage	Zefaniabibleitem
*/
class ZefaniabibleCkControllerZefaniabibleitem extends ZefaniabibleClassControllerItem
{
	/**
	* The context for storing internal data, e.g. record.
	*
	* @var string
	*/
	protected $context = 'zefaniabibleitem';

	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniabibleitem';

	/**
	* The URL view list variable.
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
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'scripture.add':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'modal.add':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
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
			case 'bibleadd.cancel':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
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
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'bibleadd.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'scripture.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'modal.delete':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
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
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'default.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'scripture.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			case 'modal.edit':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
			
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
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
			return 'bibleadd';

		if ($default)
			return 'bibleadd';

		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', 'bibleadd', 'CMD');
	}

	/**
	* Function that allows child controller access to model data after the data
	* has been saved.
	*
	* @access	protected
	* @param	JModel	&$model	The data model object.
	* @param	array	$validData	The validated data.
	* @return	void
	*/
	protected function postSaveHook(&$model, $validData = array())
	{
		parent::postSaveHook($model, $validData);
		//UPLOAD FILE : XML Bible File Location
		$model->_upload('xml_bible_file_location', array(
										'application/xml' => 'xml'));

		//UPLOAD FILE : XML Audio File Location
		$model->_upload('xml_audio_file_location', array(
										'application/xml' => 'xml'));
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
			case 'bibleadd.save':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				), array(
					'cid[]' => null
				));
				break;

			case 'bibleadd.apply':
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.zefaniabibleitem.bibleadd'
				), array(
					'cid[]' => $model->getState('zefaniabibleitem.id')
				));
				break;

			default:
				$this->applyRedirection($result, array(
					'stay',
					'com_zefaniabible.biblenames.default'
				));
				break;
		}
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleControllerZefaniabibleitem')){ class ZefaniabibleControllerZefaniabibleitem extends ZefaniabibleCkControllerZefaniabibleitem{} }

