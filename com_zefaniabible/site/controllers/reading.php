<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
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
 * Zefaniabible Zefaniabible Controller
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleControllerReading extends ZefaniabibleController
{
	var $ctrl = 'reading';
	var $singular = 'readingitem';

	function __construct($config = array())
	{

		parent::__construct($config);

		$app = JFactory::getApplication();
		$this->registerTask( 'unpublish',  'unpublish' );
		$this->registerTask( 'apply',  'apply' );






	}

	function display( )
	{



		parent::display();

		if (!JRequest::getCmd('option',null, 'get'))
		{
			//Kill the post and rebuild the url
			$this->setRedirect(ZefaniabibleHelper::urlRequest());
			return;
		}

	}

	function delete()
	{
		if (!$this->can(array('core.delete', 'core.delete.own'), JText::_("ZEFANIABIBLE_JTOOLBAR_DELETE")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniabibleitem');
		$item = $model->getItem();

		//Check Item ACL
		if (!$this->can('access-delete', JText::_("ZEFANIABIBLE_JTOOLBAR_DELETE"), $item->params))
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
			JRequest::setVar( 'view'  , 'zefaniabible');
			JRequest::setVar( 'layout', 'compare' );
			JRequest::setVar( 'cid', null );

		}

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function publish()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_PUBLISH")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );

        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

        JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( "ZEFANIABIBLE_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO", strtolower(JText::_("PUBLISH")) ) );
		}
		else
		{
			$model = $this->getModel('zefaniabibleitem');
	        if ($model->publish($cid)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_( 'DONE' ));

			} else
				JError::raiseWarning( 1000, JText::_("ERROR") );
		}

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniabible');
		JRequest::setVar( 'layout', 'compare' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function unpublish()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_UNPUBLISH")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );

        $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
        if (empty($cid))
			$cid = JRequest::getVar( 'cid', array(), 'get', 'array' );

        JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseWarning(500, JText::sprintf( "ZEFANIABIBLE_ALERT_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST_TO", strtolower(JText::_("UNPUBLISH")) ) );
		}
		else
		{
			$model = $this->getModel('zefaniabibleitem');
			if ($model->publish($cid, 0)){
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_( 'DONE' ));

			} else
				JError::raiseWarning( 1000, JText::_("ERROR") );

		}

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniabible');
		JRequest::setVar( 'layout', 'compare' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));

	}

	function orderup()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or JRequest::checkToken('get') or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniabibleitem');
		$item = $model->getItem();	//Set the Id from request
		$model->move(-1);

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniabible');
		JRequest::setVar( 'layout', 'compare' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}

	function orderdown()
	{
		if (!$this->can('core.edit.state', JText::_("ZEFANIABIBLE_JTOOLBAR_CHANGE_ORDER")))
			return;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		$model = $this->getModel('zefaniabibleitem');
		$item = $model->getItem();	//Set the Id from request
		$model->move(1);

		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniabible');
		JRequest::setVar( 'layout', 'compare' );
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

		$model = $this->getModel('zefaniabibleitem');
		$model->saveorder($cid, $order);


		$vars = array();
		JRequest::setVar( 'view'  , 'zefaniabible');
		JRequest::setVar( 'layout', 'compare' );
		JRequest::setVar( 'cid', null );

		$this->setRedirect(ZefaniabibleHelper::urlRequest($vars));
	}






}