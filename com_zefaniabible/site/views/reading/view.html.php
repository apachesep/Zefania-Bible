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
class ZefaniabibleViewReading extends JViewLegacy
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
			com = commentary
			dict = Dictionary
			strong = Show/Hide Strong Numgers flag
		*/		
		$app = JFactory::getApplication();
		$document	= JFactory::getDocument();
		$user = JFactory::getUser();
		
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
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_start_reading_date 			= 	$params->get('reading_start_date', '1-1-2012');
		$item->flg_import_user_data 			= 	$params->get('flg_import_user_data', '0');
		$item->flg_show_dictionary 				= 	$params->get('flg_show_dictionary', 0);
		$item->flg_show_references				= 	$params->get('show_references', '0');
		$item->flg_show_commentary 				= 	$params->get('show_commentary', '0');
		$item->flg_show_dictionary 				= 	$params->get('flg_show_dictionary', 0);
		$item->str_primary_commentary 			= 	$params->get('primaryCommentary');
		$item->flg_reading_rss_button			=	$params->get('flg_plan_rssfeed_button', '1');
		$item->flg_email_button 				= 	$params->get('flg_email_button', '1');	
		$item->flg_use_bible_selection 			= 	$params->get('flg_use_bible_selection', '1');	
		$item->flg_show_credit 					= 	$params->get('show_credit','0');
		$item->flg_show_page_bot 				= 	$params->get('show_pagination_bot', '1');	
		$item->flg_show_page_top 				= 	$params->get('show_pagination_top', '1');
		$item->flg_show_pagination_type 		= 	$params->get('show_pagination_type','0');
		$item->str_primary_dictionary  			= 	$params->get('str_primary_dictionary','');
		$item->str_default_image 				= 	$params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		$item->flg_show_audio_player 			= 	$params->get('show_audioPlayer', '0');
		$item->str_commentary_width 			= 	$params->get('commentaryWidth','800');
		$item->str_commentary_height 			= 	$params->get('commentaryHeight','500');
		$item->flg_enable_debug					= 	$params->get('flg_enable_debug','0');	
		$item->str_dictionary_height 			= 	$params->get('str_dictionary_height','500');
		$item->str_dictionary_width 			= 	$params->get('str_dictionary_width','800');
		$item->flg_show_strong			 		= 	$params->get('flg_show_strong','0');
		$item->flg_show_ical 					= 	$params->get('flg_show_ical', '1');
		
		$item->flg_use_strong					= 	$jinput->get('strong', $item->flg_show_strong, 'INT');
		$item->str_com 							= 	$jinput->get('com', null, 'CMD'); 		
		$item->str_tmpl 						= 	$jinput->get('tmpl',null,'CMD');
		$item->str_option						= 	$jinput->get('option', null, 'CMD');
		$item->int_menu_item_id 				= 	$jinput->get('Itemid', null, 'INT');
		$item->str_view 						= 	$jinput->get('view', 'standard', 'CMD');		
		

		if(($user->id > 0)and($item->flg_import_user_data))
		{
			$item->arr_user_data = $mdl_default->_buildQuery_getUserData($user->id);
			foreach($item->arr_user_data as $obj_user_data)
			{
				$item->str_start_reading_date 	= $obj_user_data->reading_start_date;
				$item->str_primary_bible 		= $obj_user_data->bible_alias;
				$item->str_primary_reading 		= $obj_user_data->plan_alias;
			}
		}

		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');
				
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_reading_days($item->str_reading_plan);
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days);		
		$item->int_day_number 					= 	$jinput->get('day', $item->int_day_diff, 'INT');
		$item->str_commentary 					= 	$jinput->get('com', $item->str_primary_commentary, 'CMD');
		$item->str_curr_dict 					= 	$jinput->get('dict', $item->str_primary_dictionary, 'CMD');

		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->arr_english_book_names 			= 	$mdl_common->fnc_load_languages();
		$item->str_bible_name					= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->arr_reading						=	$mdl_default->_buildQuery_reading_plan($item->str_reading_plan,$item->int_day_number);
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->obj_reading_plan_dropdown		=	$mdl_common->fnc_reading_plan_drop_down($item);
		$item->obj_bible_Bible_dropdown			= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);
		$item->str_reading_plan_name			= 	$mdl_common->fnc_find_reading_name($item->arr_reading,$item->str_reading_plan);
		$item->arr_plan							= 	$mdl_default->_buildQuery_current_reading($item->arr_reading, $item->str_Bible_Version);
		$item->cnt_chapters						=	$mdl_common->fnc_count_chapters($item->arr_reading);
		$item->str_description 					= 	$mdl_common->fnc_make_description($item->arr_plan[0]);
		$item->arr_meta							= 	$mdl_default->_buildQuery_meta($item->str_Bible_Version, "bible");
		$mdl_common->fnc_redirect_last_day($item);
		
		if($item->flg_show_audio_player)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/audioplayer.php');
			$mdl_audio = new ZefaniaAudioPlayer;			
		}
		// commentary code
		if($item->flg_show_commentary)
		{
			$item->arr_commentary_list		=	$mdl_default->_buildQuery_commentary_list();	
			$item->obj_commentary_dropdown 	= 	$mdl_common->fnc_commentary_drop_down($item);	
		}
		if($item->flg_show_dictionary)
		{
			$item->arr_dictionary_list 		= 	$mdl_default->_buildQuery_dictionary_list();
			$item->obj_dictionary_dropdown 	= 	$mdl_common->fnc_dictionary_dropdown($item);
			foreach($item->arr_plan as $ojb_plan)
			{
				$item->flg_strong_dict			= 	$mdl_common->fnc_check_strong_bible($ojb_plan);
				break; 
			}
		}
		$z=0;

		foreach ($item->arr_reading as $obj_reading_day)
		{
			for($y = $obj_reading_day->begin_chapter; $y <= $obj_reading_day->end_chapter; $y++)
			{
				if($item->flg_show_references)
				{
					$item->arr_references[$z] 	= $mdl_default->_buildQuery_References($obj_reading_day->book_id,$y);
				}
				if($item->flg_show_commentary)
				{
					$item->arr_commentary[$z] 	= $mdl_default->_buildQuery_commentary_chapter($item->str_commentary,$obj_reading_day->book_id,$y);
				}
				if($item->flg_show_audio_player)
				{
					$obj_player[$z] 			= $mdl_audio->fnc_audio_player($item->str_Bible_Version,$obj_reading_day->book_id,$y, 1);
				}
				$z++;
			}
		}
		$item->str_meta_desc				= $mdl_common->fnc_make_meta_desc($item->arr_meta);
		$item->str_meta_key					= $mdl_common->fnc_make_meta_key($item->arr_meta);		
		$mdl_common->fnc_meta_data($item); 
		//Filters
		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}