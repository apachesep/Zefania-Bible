<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * Zefaniabible Zefaniareadingdetails Controller
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleControllerZefaniareadingdetails extends ZefaniabibleController
{
	var $ctrl = 'zefaniareadingdetails';
	var $singular = 'zefaniareadingdetailsitem';

	function __construct($config = array())
	{

		parent::__construct($config);

		$layout = JRequest::getCmd('layout');
		$render	= JRequest::getCmd('render');

		$this->context = strtolower('com_' . $this->getName() . '.' . $this->ctrl
					. ($layout?'.' . $layout:'')
					. ($render?'.' . $render:'')
					);

		$app = JFactory::getApplication();
		$this->registerTask( 'new',  'new_' );
		$this->registerTask( 'apply',  'apply' );






	}

	function display($cachable = false, $urlparams = false) 
	{



		parent::display();

		if (!JRequest::getCmd('option',null, 'get'))
		{
			//Kill the post and rebuild the url
			$this->setRedirect(ZefaniabibleHelper::urlRequest());
			return;
		}

	}

	function new_()
	{
		if (!$this->can('core.create', JText::_("JTOOLBAR_NEW")))
			return;

		$vars = array();
		//Predefine fields depending on filters values
		$app = JFactory::getApplication();
		//Begin Chapter
		$filter_begin_chapter = $app->getUserState( $this->context . ".filter.begin_chapter");
		if ($filter_begin_chapter) $vars["filter_begin_chapter"] = $filter_begin_chapter;



		JRequest::setVar( 'cid', 0 );
		$layout = JRequest::getVar( 'layout');
		switch($layout)
		{
			case 'default':
				JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
				JRequest::setVar( 'layout', 'addreadingdetails' );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
				JRequest::setVar( 'layout', 'addreadingdetails' );
				break;

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function edit()
	{
		//Check Component ACL
		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;

		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-edit', JText::_("JTOOLBAR_EDIT"), $item->params))
			return;

		$vars = array();
		$layout = JRequest::getVar( 'layout');
		switch($layout)
		{
			case 'default':
				JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
				JRequest::setVar( 'layout', 'addreadingdetails' );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
				JRequest::setVar( 'layout', 'addreadingdetails' );
				break;

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function delete()
	{
		if (!$this->can(array('core.delete', 'core.delete.own'), JText::_("JTOOLBAR_DELETE")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-delete', JText::_("JTOOLBAR_DELETE"), $item->params))
			return;


        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( '_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO', strtolower(JText::_("DELETE")) ) );
			$this->setRedirect(ZefaniabibleHelper::urlRequest());
			return;
		}

		$vars = array();
		if (parent::_delete($cid))
		{
			$layout = JRequest::getVar( 'layout');
			switch($layout)
			{
				case 'default':
					JRequest::setVar( 'view'  , 'zefaniareadingdetails');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;
				case 'addreadingdetails':
					JRequest::setVar( 'view'  , 'zefaniareadingdetails');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniareadingdetails');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;

			}

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function save()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_SAVE")))
				return;

		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_SAVE")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_SAVE"), $item->params))
				return;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();

		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);


		if ($cid = parent::_save($post))
		{
			$vars = array();
			$layout = JRequest::getVar( 'layout');
			switch($layout)
			{
				case 'addreadingdetails':
					JRequest::setVar( 'view'  , 'zefaniareadingdetails');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniareadingdetails');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;

			}

			$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
		}
		else
			//Keep the post and stay on page
			parent::display();

	}

	function apply()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();

		if ($model->getId() == 0)
		{	//New item

			if (!$this->can('core.create', JText::_("JTOOLBAR_APPLY")))
				return;

		}
		else
		{	//Existing item
			if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_APPLY")))
				return;

			//Check Item ACL
			if (!$this->can('access-edit', JText::_("JTOOLBAR_APPLY"), $item->params))
				return;
		}


		$post	= JRequest::get('post');
		$post['id'] = $model->getId();

		$post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);


		if ($cid = parent::_apply($post))
		{
			$vars = array();
			$layout = JRequest::getVar( 'layout');
			switch($layout)
			{
				case 'addreadingdetails':
					JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
					JRequest::setVar( 'layout', 'addreadingdetails' );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniareadingdetailsitem');
					JRequest::setVar( 'layout', 'addreadingdetails' );
					break;

			}

			$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
		}
		else
			//Keep the post and stay on page
			parent::display();


	}



	function cancel()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$vars = array();
		$layout = JRequest::getVar( 'layout');
		switch($layout)
		{
			case 'addreadingdetails':
				JRequest::setVar( 'view'  , 'zefaniareadingdetails');
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'cid', null );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniareadingdetails');
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'cid', null );
				break;

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function orderup()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();	//Set the Id from request
		$model->move(-1);

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniareadingdetails');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function orderdown()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniareadingdetailsitem');
		$item = $model->getItem();	//Set the Id from request
		$model->move(1);

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniareadingdetails');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function saveorder()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$cid 	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order 	= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('zefaniareadingdetailsitem');
		$model->saveorder($cid, $order);


		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniareadingdetails');
		JRequest::setVar( 'layout', 'default' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}






}