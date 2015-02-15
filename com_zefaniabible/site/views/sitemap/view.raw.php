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

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewSitemap extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display( $tpl = null )
	{
		$this->document->setMimeEncoding('text/xml');
		/*
			a = plan
			b = bible
			c = day
		*/		
		$app = JFactory::getApplication();
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;		
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();	
		
		// menu item overwrites
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}

		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->flg_only_primary_bible 			= $params->get('flg_only_primary_bible', '1');
		$item->str_priority 					= $params->get('prio', '0.1');
		$item->str_frequency 					= $params->get('freq', 'weekly');	
				
		$item->str_Bible_Version 				= $jinput->get('bible', $item->str_primary_bible, 'CMD');					
		$item->arr_english_book_names 			= $mdl_common->fnc_load_languages();
				
		$menuitemid = $params->get('rp_mo_menuitem');
		
		if($item->flg_only_primary_bible)
		{
			$item->arr_chapter_list = $mdl_default->_buildQuery_Chapter_List($item->str_Bible_Version);
		}
		else
		{
			$item->arr_chapter_list = $mdl_default->_buildQuery_Chapter_List('');

		}		
		$item->str_view_plan			=	$mdl_default->_buildQuery_get_menu_id('standard');
		//Filters
		$this->assignRef('item',	$item);
		parent::display($tpl);
	}
}