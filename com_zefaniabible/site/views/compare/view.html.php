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
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$document	= JFactory::getDocument();
	
		// menu item overwrites
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		
		// make english strings
		$jlang = JFactory::getLanguage();
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, 'en-GB', true);
		for($i = 1; $i <=66; $i++)
		{
			$arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
		}
		$jlang->load('com_zefaniabible', JPATH_COMPONENT, null, true);
		
		require_once(JPATH_COMPONENT_SITE.'/models/compare.php');
		$biblemodel = new ZefaniabibleModelCompare;
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;		
		$str_first_record = $mdl_default->_buildQuery_first_record();
		
		$str_primary_bible = $params->get('primaryBible', $str_first_record);
		$str_secondary_bible = $params->get('secondaryBible', $str_first_record);
		$flg_show_audio_player = $params->get('show_audioPlayer', '0');
		$flg_show_second_player = $params->get('show_second_player','1');
		$flg_show_references = $params->get('show_references', '0');
		$flg_show_commentary = $params->get('show_commentary', '0');
		$int_primary_book_front_end = $params->get('primary_book_frontend');
		$int_primary_chapter_front_end = $params->get('int_front_start_chapter',1);
		$flg_show_dictionary = $params->get('flg_show_dictionary', 0);
		
		
		$str_Main_Bible_Version = JRequest::getWord('a',$str_primary_bible);
		$str_Second_Bible_Version = JRequest::getWord('b',$str_secondary_bible);	
		$int_Bible_Book_ID = JRequest::getInt('c', $int_primary_book_front_end);	
		$int_Bible_Chapter = JRequest::getInt('d', $int_primary_chapter_front_end);	
		$str_tmpl = JRequest::getCmd('tmpl');
		
		$int_max_chapter 	= 		$biblemodel-> _buildQuery_Max_Chapter($int_Bible_Book_ID);
				
		$arr_Bibles 		= 		$biblemodel-> _buildQuery_Bibles();
		$arr_Chapter 		= 		$biblemodel-> _buildQuery_Chapter($str_Main_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter);
		$arr_Chapter2		= 		$biblemodel-> _buildQuery_Chapter($str_Second_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter);
		$int_max_verse 		= 		$biblemodel-> _buildQuery_Max_Verse($int_Bible_Book_ID,$int_Bible_Chapter);
		
		if($flg_show_references)
		{
			$obj_references = $biblemodel->_buildQuery_References($int_Bible_Book_ID,$int_Bible_Chapter);
		}
		
		if($flg_show_audio_player)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/audioplayer.php');
			$mdl_audio = new ZefaniaAudioPlayer;
			$obj_player_one = $mdl_audio->fnc_audio_player($str_Main_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter, 1);
			if($flg_show_second_player == 1)
			{
				$obj_player_two = $mdl_audio->fnc_audio_player($str_Second_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter, 2);
				$this->assignRef('obj_player_two',		$obj_player_two);
			}
		}
		// commentary code

		$obj_commentary_dropdown = '';
		if($flg_show_commentary)
		{
			require_once(JPATH_COMPONENT_SITE.'/models/commentary.php');
			$mdl_commentary = new ZefaniabibleModelCommentary;			
			$str_primary_commentary = $params->get('primaryCommentary');
			$str_commentary = JRequest::getWord('com', $str_primary_commentary);
			$arr_commentary =	$mdl_commentary-> _buildQuery_commentary_chapter($str_commentary,$int_Bible_Book_ID,$int_Bible_Chapter);
			$arr_commentary_list =	$mdl_commentary-> _buildQuery_commentary_list();	
			foreach($arr_commentary_list as $obj_comm_list)
			{
				if($str_commentary == $obj_comm_list->alias)
				{
					$obj_commentary_dropdown = $obj_commentary_dropdown.'<option value="'.$obj_comm_list->alias.'" selected>'.$obj_comm_list->title.'</option>';
				}
				else
				{
					$obj_commentary_dropdown = $obj_commentary_dropdown.'<option value="'.$obj_comm_list->alias.'">'.$obj_comm_list->title.'</option>';
				}
			}
		}
		if($flg_show_dictionary)
		{
			$str_primary_dictionary  = $params->get('str_primary_dictionary','');
			$arr_dictionary_list = $mdl_default->_buildQuery_dictionary_list();
		}
		
		// redirect to last chapter
		if($int_Bible_Chapter > $int_max_chapter)
		{
			$str_redirect_url = "index.php?option=com_zefaniabible&view=".JRequest::getCmd('view')."&a=".$str_Main_Bible_Version."&b=".$str_Second_Bible_Version."&c=".$int_Bible_Book_ID.'-'.strtolower(str_replace(" ","-",$arr_english_book_names[$int_Bible_Book_ID]))."&d=".$int_max_chapter.'-chapter';
			if(($flg_show_commentary)and(count($arr_commentary_list) > 1))
			{
				$str_redirect_url = $str_redirect_url."&com=".$str_primary_commentary;
			}
			if($str_tmpl == "component")
			{
				$str_redirect_url = $str_redirect_url ."&tmpl=component";
			}
			if(($flg_show_dictionary)and(count($arr_dictionary_list) > 1))
			{
				$str_redirect_url = $str_redirect_url . "&dict=".$str_primary_dictionary;
			}
			if(JRequest::getInt('strong') == 1)
			{
				$str_redirect_url = $str_redirect_url ."&strong=".JRequest::getInt('strong');
			}				
			$str_redirect_url = JRoute::_($str_redirect_url);
			header('HTTP/1.1 301 Moved Permanently');
			//header('Location: '.$str_redirect_url); 			
		}	
		
		
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('access',				$access);
		$this->assignRef('arr_Chapter',			$arr_Chapter);
		$this->assignRef('arr_Chapter2',		$arr_Chapter2);		
		$this->assignRef('int_max_chapter',		$int_max_chapter);
		$this->assignRef('int_max_verse',		$int_max_verse);
		$this->assignRef('lists',				$lists);
		$this->assignRef('arr_Bibles',			$arr_Bibles);
		$this->assignRef('int_Bible_Book_ID',	$int_Bible_Book_ID);
		$this->assignRef('int_Bible_Chapter',	$int_Bible_Chapter);
		$this->assignRef('str_Bible_Version',	$str_Main_Bible_Version);
		$this->assignRef('str_Bible_Version2',	$str_Second_Bible_Version);		
		$this->assignRef('obj_player_one',		$obj_player_one);	
		$this->assignRef('config',				$config);
		$this->assignRef('arr_commentary',		$arr_commentary);
		$this->assignRef('obj_commentary_dropdown',	$obj_commentary_dropdown);
		$this->assignRef('obj_references',		$obj_references);
		$this->assignRef('arr_dictionary_list',		$arr_dictionary_list);
		$this->assignRef('arr_commentary_list',		$arr_commentary_list);
		parent::display($tpl);
	}
}