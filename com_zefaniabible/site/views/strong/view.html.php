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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( '0');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewStrong extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();
		$option	= JRequest::getCmd('option');
		$view	= JRequest::getCmd('view');
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'default':
				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}
	}
	function display_default($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$mdl_access = new ZefaniabibleHelper;
		$access = $mdl_access->getACL();
		$document	= JFactory::getDocument();
		/*
			a = Dictionary alias
			b = Strong ID
		*/
		$str_stong_alias = JRequest::getCmd('a');
		$str_strong_id = JRequest::getCmd('b', '1');	
		
		require_once(JPATH_COMPONENT_SITE.'/models/strong.php');
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');
		$mdl_strong = new ZefaniabibleModelStrong;	
		$arr_passage = $mdl_strong->_buildQuery_strong($str_stong_alias,$str_strong_id);	 
		$str_dict_name = $mdl_strong->_buildQuery_dict_name($str_stong_alias);
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$user = JFactory::getUser();
		$this->assignRef('user',					$user);
		$this->assignRef('access',					$access);
		$this->assignRef('arr_passage',				$arr_passage);	
		$this->assignRef('str_dict_name',			$str_dict_name);
		$this->assignRef('lists',					$lists);
		$this->assignRef('config',					$config);
		parent::display($tpl);
	}
}