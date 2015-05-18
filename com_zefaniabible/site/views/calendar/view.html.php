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

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewCalendar extends JViewLegacy
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
			Standard Bible
			a = bible
			b = book
			c = chapter
			com = commentary
			dict = Dictionary
			strong = Show/Hide Strong Numgers flag
		*/		
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$int_new_reading_days = 0;
												
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		// menu item overwrites
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}		

		$jinput = JFactory::getApplication()->input;

		$item = new stdClass();				
		$item->int_current_year 				= date("Y");
		$item->int_current_month 				= date("n");
		$item->int_current_month_max_days 		= date("t");
		$item->int_current_week_day 			= date("w");
		$item->int_current_day 					= date("j");
		$item->int_current_month_begin_weekday 	= date("w", mktime(0, 0, 0, $item->int_current_month, 1, $item->int_current_year));

		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->flg_show_credit 					= 	$params->get('show_credit','0');
		$item->str_start_reading_date 			= 	$params->get('reading_start_date', '1-1-2012');
		$item->flg_use_bible_selection 			= 	$params->get('flg_use_bible_selection', '1');	
		$item->str_calendar_link_color			= 	$params->get('str_calendar_link_color', '#00FFCC');
		$item->str_calendar_emptyday_color		= 	$params->get('str_calendar_emptyday_color', '#CCCCCC');
		$item->str_calendar_border_color		= 	$params->get('str_calendar_border_color', '#000');
		$item->str_calendar_today_color			= 	$params->get('str_calendar_today_color', '#f2f2f2');
		$item->str_calendar_link_text_color		= 	$params->get('str_calendar_link_text_color', '#000');				

		$item->flg_show_pagination_type 		= 	$params->get('show_pagination_type','0');
		$item->flg_show_page_top 				= 	$params->get('show_pagination_top', '1');
		$item->flg_enable_debug					= 	$params->get('flg_enable_debug','0');	
						
		$item->int_menu_item_id 				= 	$jinput->get('Itemid', null, 'INT');
		$item->str_option						= 	$jinput->get('option', null, 'CMD');
		$item->str_com 							= 	$jinput->get('com', null, 'CMD'); 		
		$item->str_tmpl 						= 	$jinput->get('tmpl',null,'CMD');
		$item->str_view 						= 	$jinput->get('view', 'standard', 'CMD');
		
		$item->int_month 						= 	$jinput->get('month', $item->int_current_month, 'INT');
		$item->int_year 						= 	$jinput->get('year', $item->int_current_year, 'INT');
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_reading_days($item->str_reading_plan);
		
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days, $item->int_month, $item->int_year, 1);		
		$item->obj_reading_plan_dropdown		=	$mdl_common->fnc_reading_plan_drop_down($item);
		$item->obj_bible_Bible_dropdown			= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);				

		
		$item->int_month_begin_weekday			= 	date("w", mktime(0, 0, 0, $item->int_month, 1, $item->int_year));
		$item->int_month_max_days				= 	date("t", mktime(0, 0, 0, $item->int_month, 1, $item->int_year));
		
		$item->str_view_plan					=	$mdl_default->_buildQuery_get_menu_id('reading');
		$item->arr_reading						= 	$mdl_default->_buildQuery_readingplan_calendar($item->str_reading_plan, $item->int_day_diff, $item->int_month_max_days);
		
		if(($item->int_day_diff + $item->int_month_max_days) > $item->int_max_days )
		{
			$int_new_reading_days = ($item->int_day_diff + $item->int_month_max_days) - $item->int_max_days;
			$item->arr_reading2					=	$mdl_default->_buildQuery_readingplan_calendar($item->str_reading_plan, 0, $int_new_reading_days);
		}	
		$this->assignRef('item', 		$item);
		parent::display($tpl);
	}
}