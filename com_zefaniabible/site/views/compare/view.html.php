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
class ZefaniabibleViewCompare extends JViewLegacy
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
			b = bible2
			c = book
			d = chapter
			com = commentary
			dict = Dictionary
			strong = Show/Hide Strong Numgers flag		
		*/		
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
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
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$jinput = JFactory::getApplication()->input;

		$item = new stdClass();		
		$item->str_first_bible_record 			= $mdl_default->_buildQuery_first_record();
		$item->str_primary_bible 				= $params->get('primaryBible', 		$item->str_first_bible_record);	
		$item->str_secondary_bible 				= $params->get('secondaryBible', 	$item->str_first_bible_record);
		$item->flg_show_audio_player 			= $params->get('show_audioPlayer', '0');
		$item->flg_show_second_player 			= $params->get('show_second_player','0');
		$item->flg_show_references				= $params->get('show_references', '0');
		$item->flg_show_commentary 				= $params->get('show_commentary', '0');
		$item->flg_use_bible_selection 			= $params->get('flg_use_bible_selection', '1');	
		$item->int_primary_book_front_end 		= $params->get('primary_book_frontend');
		$item->int_primary_chapter_front_end 	= $params->get('int_front_start_chapter',1);
		$item->flg_show_dictionary 				= $params->get('flg_show_dictionary', 0);
		$item->flg_email_button 				= $params->get('flg_email_button', '1');	
		$item->flg_use_bible_selection 			= $params->get('flg_use_bible_selection', '1');	
		$item->flg_reading_rss_button 			= $params->get('flg_plan_rssfeed_button', '1');
		$item->str_commentary_width 			= $params->get('commentaryWidth','800');
		$item->str_commentary_height 			= $params->get('commentaryHeight','500');
		$item->str_dictionary_height 			= $params->get('str_dictionary_height','500');
		$item->str_dictionary_width 			= $params->get('str_dictionary_width','800');	
		$item->str_primary_dictionary  			= $params->get('str_primary_dictionary','');
		$item->str_primary_commentary 			= $params->get('primaryCommentary');		 
		$item->flg_show_credit 					= $params->get('show_credit','0');
		$item->flg_show_audio_player 			= $params->get('show_audioPlayer','0');
		$item->int_player_popup_height 			= $params->get('player_popup_height','300');
		$item->int_player_popup_width 			= $params->get('player_popup_width','300');			
		$item->flg_show_page_top 				= $params->get('show_pagination_top', '1');
		$item->flg_show_page_bot 				= $params->get('show_pagination_bot', '1');	
		$item->flg_show_pagination_type 		= $params->get('show_pagination_type','0');		
		$item->str_default_image 				= $params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		$item->flg_enable_debug					= $params->get('flg_enable_debug','0');	
		$item->flg_show_strong			 		= $params->get('flg_show_strong','0');
		
		$item->str_Main_Bible_Version 		= $jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->str_Second_Bible_Version 	= $jinput->get('bible2', $item->str_secondary_bible, 'CMD');
		$item->str_Bible_Version 			= $item->str_Main_Bible_Version;
		$item->int_Bible_Book_ID 			= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
		$item->int_Bible_Chapter 			= $jinput->get('chapter', $item->int_primary_chapter_front_end, 'INT');
		$item->str_tmpl 					= $jinput->get('tmpl',null,'CMD');
		$item->str_option					= $jinput->get('option', null, 'CMD');
		$item->int_menu_item_id 			= $jinput->get('Itemid', null, 'INT');	
		$item->flg_use_strong				= $jinput->get('strong', $item->flg_show_strong, 'INT');
		$item->str_view 					= $jinput->get('view', 'standard', 'CMD');
		$item->str_commentary 				= $jinput->get('com', $item->str_primary_commentary, 'CMD');
		$item->str_curr_dict 				= $jinput->get('dict', $item->str_primary_dictionary, 'CMD');
		
		$item->arr_english_book_names 		=	$mdl_common->fnc_load_languages();
		$item->int_max_chapter				= 	$mdl_default->_buildQuery_Max_Chapter($item->int_Bible_Book_ID);
		$item->int_max_verse				= 	$mdl_default->_buildQuery_Max_Verse($item->int_Bible_Book_ID,$item->int_Bible_Chapter);
		$item->arr_Bibles 					= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->arr_Chapter_1				= 	$mdl_default->_buildQuery_Chapter($item->int_Bible_Chapter,$item->int_Bible_Book_ID,$item->str_Main_Bible_Version);
		$item->arr_Chapter_2				= 	$mdl_default->_buildQuery_Chapter($item->int_Bible_Chapter,$item->int_Bible_Book_ID,$item->str_Second_Bible_Version);		
		$item->arr_meta						= 	$mdl_default->_buildQuery_meta($item->str_Bible_Version, "bible");
		
		$item->obj_bible_Bible_dropdown_1	= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Main_Bible_Version);
		$item->obj_bible_Bible_dropdown_2	= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Second_Bible_Version);		
		$item->obj_bible_book_dropdown 		= 	$mdl_common->fnc_bible_book_dropdown($item);
		$item->obj_bible_chap_dropdown 		= 	$mdl_common->fnc_bible_chapter_dropdown($item);		
		$item->str_bible_name_1				= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Main_Bible_Version);
		$item->str_bible_name_2				= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Second_Bible_Version);

		if($item->flg_show_references)
		{
			$item->arr_references = $mdl_default->_buildQuery_References($item->int_Bible_Book_ID,$item->int_Bible_Chapter);
		}
		
		if($item->flg_show_audio_player)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/audioplayer.php');
			$mdl_audio = new ZefaniaAudioPlayer;
			$obj_player_one = $mdl_audio->fnc_audio_player($item->str_Main_Bible_Version,$item->int_Bible_Book_ID,$item->int_Bible_Chapter, 1);
			$this->assignRef('obj_player_one',		$obj_player_one);				
			if($item->flg_show_second_player == 1)
			{
				$obj_player_two = $mdl_audio->fnc_audio_player($item->str_Second_Bible_Version,$item->int_Bible_Book_ID,$item->int_Bible_Chapter, 2);
				$this->assignRef('obj_player_two',		$obj_player_two);
			}
		}
		// commentary code
		if($item->flg_show_commentary)
		{
			$item->arr_commentary 			=	$mdl_default->_buildQuery_commentary_chapter($item->str_commentary,$item->int_Bible_Book_ID,$item->int_Bible_Chapter);
			$item->arr_commentary_list		=	$mdl_default->_buildQuery_commentary_list();
			$item->obj_commentary_dropdown 	= 	$mdl_common->fnc_commentary_drop_down($item);
		}
		if($item->flg_show_dictionary)
		{
			$item->arr_dictionary_list 		= 	$mdl_default->_buildQuery_dictionary_list();
			$item->obj_dictionary_dropdown 	= 	$mdl_common->fnc_dictionary_dropdown($item);
			$item->flg_strong_dict			= 	$mdl_common->fnc_check_strong_bible($item->arr_Chapter_1);
			// check 2nd bible
			if($item->flg_strong_dict == 0)
			{
				$item->flg_strong_dict			= 	$mdl_common->fnc_check_strong_bible($item->arr_Chapter_2);
			}
		}
		$item->str_description 			= 	$mdl_common->fnc_make_description($item->arr_Chapter_1);
		$item->str_description 			.= 	$mdl_common->fnc_make_description($item->arr_Chapter_2);
		$item->chapter_output			=	$mdl_common->fnc_output_dual_chapter($item);
		$item->str_meta_desc				= $mdl_common->fnc_make_meta_desc($item->arr_meta);
		$item->str_meta_key					= $mdl_common->fnc_make_meta_key($item->arr_meta);				
		$mdl_common->fnc_redirect_last_chapter($item);
		$mdl_common->fnc_meta_data($item); 

		//Filters
		$this->assignRef('item',				$item);
		parent::display($tpl);
	}
}