<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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

jimport('joomla.utilities.date');

/**
 * Zefaniabible Zefaniauser Controller
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleControllerZefaniauser extends ZefaniabibleController
{
	var $ctrl = 'zefaniauser';
	var $singular = 'zefaniauseritem';

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
		//Send Reading Plan Email
		$filter_send_reading_plan_email = $app->getUserState( $this->context . ".filter.send_reading_plan_email");
		if ($filter_send_reading_plan_email) $vars["filter_send_reading_plan_email"] = $filter_send_reading_plan_email;

		//Reading Start Date
		$filter_reading_start_date = $app->getUserState( $this->context . ".filter.reading_start_date");
		if ($filter_reading_start_date) $vars["filter_reading_start_date"] = $filter_reading_start_date;

		//User Name
		$filter_user_name = $app->getUserState( $this->context . ".filter.user_name");
		if ($filter_user_name) $vars["filter_user_name"] = $filter_user_name;



		JRequest::setVar( 'cid', 0 );
		$layout = JRequest::getVar( 'layout');
		switch($layout)
		{
			case 'default':
				JRequest::setVar( 'view'  , 'zefaniauseritem');
				JRequest::setVar( 'layout', 'adduser' );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniauseritem');
				JRequest::setVar( 'layout', 'adduser' );
				break;

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function edit()
	{
		//Check Component ACL
		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;

		$model = $this->getModel('zefaniauseritem');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-edit', JText::_("JTOOLBAR_EDIT"), $item->params))
			return;

		$vars = array();
		$layout = JRequest::getVar( 'layout');
		switch($layout)
		{
			case 'default':
				JRequest::setVar( 'view'  , 'zefaniauseritem');
				JRequest::setVar( 'layout', 'adduser' );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniauseritem');
				JRequest::setVar( 'layout', 'adduser' );
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


		$model = $this->getModel('zefaniauseritem');
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
					JRequest::setVar( 'view'  , 'zefaniauser');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;
				case 'adduser':
					JRequest::setVar( 'view'  , 'zefaniauser');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniauser');
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

		$model = $this->getModel('zefaniauseritem');
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



		if ($cid = parent::_save($post))
		{
			$vars = array();
			$layout = JRequest::getVar( 'layout');
			switch($layout)
			{
				case 'adduser':
					JRequest::setVar( 'view'  , 'zefaniauser');
					JRequest::setVar( 'layout', 'default' );
					JRequest::setVar( 'cid', null );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniauser');
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

		$model = $this->getModel('zefaniauseritem');
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



		if ($cid = parent::_apply($post))
		{
			$vars = array();
			$layout = JRequest::getVar( 'layout');
			switch($layout)
			{
				case 'adduser':
					JRequest::setVar( 'view'  , 'zefaniauseritem');
					JRequest::setVar( 'layout', 'adduser' );
					break;


				default:
					JRequest::setVar( 'view'  , 'zefaniauseritem');
					JRequest::setVar( 'layout', 'adduser' );
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
			case 'adduser':
				JRequest::setVar( 'view'  , 'zefaniauser');
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'cid', null );
				break;


			default:
				JRequest::setVar( 'view'  , 'zefaniauser');
				JRequest::setVar( 'layout', 'default' );
				JRequest::setVar( 'cid', null );
				break;

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function toggle_send_reading_plan_email()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;


		$model = $this->getModel('Zefaniauseritem');
		$zefaniauseritem = $model->getItem();


		if ($zefaniauseritem->id == 0)
		{
			$msg = JText::_( 'ERROR' );
			$this->setRedirect(ZefaniabibleHelper::urlRequest(), $msg);
			return;
		}

		$data = array("send_reading_plan_email" => is_null($zefaniauseritem->send_reading_plan_email)?1:!$zefaniauseritem->send_reading_plan_email);
        $this->_save($data);

		$this->setRedirect(ZefaniabibleHelper::urlRequest());

	}

	function toggle_send_verse_of_day_email()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		if (!$this->can(array('core.edit', 'core.edit.own'), JText::_("JTOOLBAR_EDIT")))
			return;


		$model = $this->getModel('Zefaniauseritem');
		$zefaniauseritem = $model->getItem();


		if ($zefaniauseritem->id == 0)
		{
			$msg = JText::_( 'ERROR' );
			$this->setRedirect(ZefaniabibleHelper::urlRequest(), $msg);
			return;
		}

		$data = array("send_verse_of_day_email" => is_null($zefaniauseritem->send_verse_of_day_email)?1:!$zefaniauseritem->send_verse_of_day_email);
        $this->_save($data);

		$this->setRedirect(ZefaniabibleHelper::urlRequest());

	}







}