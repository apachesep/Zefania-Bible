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
jimport( 'joomla.html.parameter' );
/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewPlayer extends JView
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
		/*
			a = alias
			b = book
		*/	
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$access = ZefaniabibleHelper::getACL();
		$document	= &JFactory::getDocument();
	
		// menu item overwrites
		$params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		
		$str_Bible_Alias = JRequest::getCmd('a','kjv');			
		$int_Bible_Book_ID = JRequest::getInt('b', '1');	
		
		require_once(JPATH_COMPONENT_SITE.'/models/player.php');
		$mdl_player = new ZefaniabibleModelPlayer;
		$arr_book_info = $mdl_player->_buildQuery_Bible($str_Bible_Alias,$int_Bible_Book_ID);
		
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',				$access);
		$this->assignRef('arr_book_info',		$arr_book_info);
		$this->assignRef('int_Bible_Book_ID',	$int_Bible_Book_ID);
		

		parent::display($tpl);
	}
}