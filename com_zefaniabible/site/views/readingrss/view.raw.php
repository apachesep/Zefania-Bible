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
class ZefaniabibleViewReadingrss extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display( $tpl = null )
	{
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
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());
		$item->str_start_reading_date 			= 	$params->get('reading_start_date', '1-1-2012');
		$item->str_default_image 				= 	$params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->str_layout		 				= 	$jinput->get('layout', 'default', 'CMD');
		$item->str_variant		 				= 	$jinput->get('variant', 'default', 'CMD');
		
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_verse_of_day_verse();
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days);
		$item->int_day_number 					= 	$jinput->get('day', $item->int_day_diff, 'INT');
		
		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->arr_reading						=	$mdl_default->_buildQuery_reading_plan($item->str_reading_plan,$item->int_day_number);	
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->arr_plan							= 	$mdl_default->_buildQuery_current_reading($item->arr_reading, $item->str_Bible_Version);	
		$item->str_bible_name					= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->str_description					=	$mdl_common->fnc_create_reading_desc($item->arr_reading_plan_list,$item->str_reading_plan);
		$item->arr_english_book_names 			= 	$mdl_common->fnc_load_languages();
		$item->str_reading_plan_name			= 	$mdl_common->fnc_find_reading_name($item->arr_reading,$item->str_reading_plan);
		$item->str_view_plan					=	$mdl_default->_buildQuery_get_menu_id('reading');
		$item->str_today 						=	$mdl_common->fnc_todays_date();	
		
		// code to turn off API
		$item->flg_use_api						= $params->get('flg_use_api', 0);
		$item->flg_use_key						= $params->get('flg_use_key', 0);
		$item->str_api_key						= $params->get('str_api_key');		
		$item->str_user_api_key 					= $jinput->get('apikey', '', 'CMD');	
		
		
		switch($item->str_variant)
		{
			case "atom":
			case "seperate":
			case "single":
				$this->document->setMimeEncoding('text/xml');
				//JResponse::setHeader('Content-Disposition','attachment;filename='.$item->str_reading_plan.'.xml');
				break;
				
			case "json":
			case "json2":
				if((	($item->flg_use_api == 0)and($item->flg_use_key == 0)) or (($item->flg_use_api == 1)and($item->flg_use_key == 1)and($item->str_user_api_key != $item->str_api_key))	)
				{
					$this->document->setMimeEncoding('application/json');
					$mdl_common->fnc_not_auth();
					return;
				}
				$this->document->setMimeEncoding('application/json');	
				//JResponse::setHeader('Content-Disposition','attachment;filename='.$item->str_reading_plan.'.json');		
				break;
								
			default:
				$this->document->setMimeEncoding('text/xml');			
				//JResponse::setHeader('Content-Disposition','attachment;filename='.$item->str_reading_plan.'.xml');	
				break;	
		}			
		//Filters
		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}
?>