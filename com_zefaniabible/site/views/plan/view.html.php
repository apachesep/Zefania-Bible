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
class ZefaniabibleViewPlan extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();
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
			a = plan
			b = bible
			c = day
		*/		
		$app = JFactory::getApplication();
	
		// menu item overwrites
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		// create pagination
		jimport('joomla.html.pagination');
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->str_start_reading_date 			= 	$params->get('reading_start_date', '1-1-2012');
		$item->flg_email_button 				= 	$params->get('flg_email_button', '1');
		$item->flg_reading_rss_button 			= 	$params->get('flg_plan_rssfeed_button', '1');
		$item->flg_use_bible_selection 			= 	$params->get('flg_use_bible_selection', '1');	
		$item->flg_show_page_top 				= 	$params->get('show_pagination_top', '1');
		$item->flg_show_page_bot 				= 	$params->get('show_pagination_bot', '1');	
		$item->flg_show_credit 					= 	$params->get('show_credit','0');
		$item->str_default_image 				= 	$params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		$item->flg_enable_debug					= 	$params->get('flg_enable_debug','0');	
		$item->flg_show_ical 					= 	$params->get('flg_show_ical', '1');

		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		$item->int_menu_item_id 				= 	$jinput->get('Itemid', null, 'INT');
		$item->str_option						= 	$jinput->get('option', null, 'CMD');
		$item->str_com 							= 	$jinput->get('com', null, 'CMD'); 		
		$item->str_tmpl 						= 	$jinput->get('tmpl',null,'CMD');
		$item->str_view 						= 	$jinput->get('view', 'standard', 'CMD');
		
		$item->arr_pagination 					= 	$mdl_default->_get_pagination_readingplan_overview($item->str_reading_plan);
		$item->arr_reading 						= 	$mdl_default->_buildQuery_readingplan_overview($item->str_reading_plan,$item->arr_pagination);
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->arr_meta							= 	$mdl_default->_buildQuery_meta($item->str_Bible_Version, "bible");
		$item->obj_reading_plan_dropdown		=	$mdl_common->fnc_reading_plan_drop_down($item);
		$item->obj_bible_Bible_dropdown			= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);
		$item->str_reading_plan_name			= 	$mdl_common->fnc_find_reading_name($item->arr_reading_plan_list, $item->str_reading_plan);

		$item->str_view_plan					=	$mdl_default->_buildQuery_get_menu_id('reading');
		$item->str_description					=	$mdl_common->fnc_create_reading_desc($item->arr_reading_plan_list,$item->str_reading_plan);
		$item->chapter_output					=	$mdl_common->fnc_create_plan_list_output($item);
		$item->str_meta_desc					= 	$mdl_common->fnc_make_meta_desc($item->arr_meta);
		$item->str_meta_key						= 	$mdl_common->fnc_make_meta_key($item->arr_meta);	
		$mdl_common->fnc_meta_data($item); 

		//Filters
		$this->assignRef('item',		$item);
		$this->assignRef('pagination',	$item->arr_pagination);
		parent::display($tpl);		
	}
}