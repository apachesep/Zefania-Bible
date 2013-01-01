<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Viewlevels
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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * Zefaniabible Component ZefaniabibleController Controller
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleController extends JController
{
	var $context;

	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();

	}

	function can($accesses, $taskString, $itemAccess = null)
	{
		if (!is_Array($accesses))
			$accesses = array($accesses);

		if (isset($itemAccess))
			$acl = $itemAccess;
		else
			$acl = ZefaniabibleHelper::getAcl();


		foreach($accesses as $access)
		{
			if ($acl->get($access))
				return true;
		}

		JError::raiseWarning(403, JText::sprintf( "ZEFANIABIBLE_ACL_UNAUTORIZED_TASK", $taskString) );
		$this->setRedirect(ZefaniabibleHelper::urlRequest());
		return false;
	}

	function _apply($data)
	{

		$model = $this->getModel($this->singular);
		$item = $model->getItem();

		if ((int)$item->id > 0)
		{
			//Check Item ACL
			if (!$this->can('access-edit', JText::_("ZEFANIABIBLE_JTOOLBAR_EDIT"), $item->params))
				return;

		}


        if ($model->save($data))
        {
			JRequest::setVar('cid', $model->getId());
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ZEFANIABIBLE_CONTROLLER_DONE'));
        	return array($model->_id);
		}

    	JError::raiseWarning( 1000, JText::_('ZEFANIABIBLE_CONTROLLER_ERROR') );

		return false;

	}

	function _save($data)
	{

		$model = $this->getModel($this->singular);
		$item = $model->getItem();

		if ((int)$item->id > 0)
		{
			//Check Item ACL
			if (!$this->can('access-edit', JText::_("ZEFANIABIBLE_JTOOLBAR_EDIT"), $item->params))
				return;

		}


        if ($model->save($data))
        {
        	JRequest::setVar('cid', $model->getId());
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ZEFANIABIBLE_CONTROLLER_DONE'));
        	return array($model->_id);
        }

		JError::raiseWarning( 1000, JText::_('ZEFANIABIBLE_CONTROLLER_ERROR') );

		return false;
	}

	function _delete($cid)
	{

		$model = $this->getModel($this->singular);

	    if ($model->delete($cid))
	    {
	    	JRequest::setVar('cid', 0);
        	$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('ZEFANIABIBLE_CONTROLLER_DONE'));
        	return true;
	    }

        JError::raiseWarning( 1000, JText::_('ZEFANIABIBLE_CONTROLLER_ERROR') );

		return false;
	}






}
