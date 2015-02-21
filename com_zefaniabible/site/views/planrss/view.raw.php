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
class ZefaniabibleViewPlanrss extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display( $tpl = null )
	{
		/*
			a = Plan Alias
			b = Bible Alias
			c = start day filter
			d = number of items
			e = feed type atom/rss
		*/			
		$app = JFactory::getApplication();
		$mainframe = JFactory::getApplication();
				
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;		
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();		
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->str_default_image 				= 	$params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->str_layout		 				= 	$jinput->get('layout', 'default', 'CMD');			
		
		$item->str_limit_start 					=	$jinput->get('limitstart', 0, 'INT');		
		$item->int_start_item 					= 	$jinput->get('start', $item->str_limit_start, 'INT');	
		$item->int_number_of_items				= 	$jinput->get('items', $mainframe->getCfg('feed_limit'), 'INT');	
		$item->str_feed_type		 			= 	$jinput->get('type', 'rss', 'CMD');
		$item->str_variant		 				= 	$jinput->get('variant', 'default', 'CMD');
		
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_reading_days($item->str_reading_plan);
		if($item->str_variant == "ical")
		{
			$item->int_number_of_items = $item->int_max_days;
		}
		
		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->str_bible_name					= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->arr_pagination 					= 	$mdl_default->_get_pagination_readingplan_overview($item->str_reading_plan);
		$item->arr_pagination->limitstart 		= 	$item->int_start_item;
		$item->arr_pagination->limit			= 	$item->int_number_of_items;
		$item->arr_reading 						= 	$mdl_default->_buildQuery_readingplan_overview($item->str_reading_plan,$item->arr_pagination);
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->str_reading_plan_name			= 	$mdl_common->fnc_find_reading_name($item->arr_reading_plan_list, $item->str_reading_plan);
		$item->str_description					=	$mdl_common->fnc_create_reading_desc($item->arr_reading_plan_list,$item->str_reading_plan);
		$item->str_view_plan					=	$mdl_default->_buildQuery_get_menu_id('reading');
		$item->str_today 						=	$mdl_common->fnc_todays_date();	
		$item->arr_english_book_names 			= 	$mdl_common->fnc_load_languages();
		
		switch($item->str_variant)
		{
			case "atom":
				$this->document->setMimeEncoding('text/xml');
				break;
				
			case "ical":
				$this->document->setMimeEncoding('text/calendar');
				JResponse::setHeader('Content-Disposition','attachment;filename=calendar.ics');
				break;
				
			case "json":
			case "json2":
				$this->document->setMimeEncoding('application/json');	
				JResponse::setHeader('Content-Disposition','attachment;filename=calendar.json');		
				break;
											
			default:
				$this->document->setMimeEncoding('text/xml');			
				break;	
		}				
				
		//Filters
		$this->assignRef('item',	$item);		
		parent::display($tpl);
	}
}