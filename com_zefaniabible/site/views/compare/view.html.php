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
			e = verse			
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
		
		$str_primary_bible = $params->get('primaryBible', 'kjv');
		$str_secondary_bible = $params->get('secondaryBible', 'kjv');
		$flg_show_audio_player = $params->get('show_audioPlayer', '0');
		$flg_show_second_player = $params->get('show_second_player','1');
		
		$str_Main_Bible_Version = JRequest::getCmd('a',$str_primary_bible);
		$str_Second_Bible_Version = JRequest::getCmd('b',$str_secondary_bible);	
		$int_Bible_Book_ID = JRequest::getInt('c', '1');	
		$int_Bible_Chapter = JRequest::getInt('d', '1');	
	
		require_once(JPATH_COMPONENT_SITE.'/models/compare.php');
		$biblemodel = new ZefaniabibleModelCompare;
		$arr_Bibles 		= 		$biblemodel-> _buildQuery_Bibles();
		$arr_Chapter 		= 		$biblemodel-> _buildQuery_Chapter($str_Main_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter);
		$arr_Chapter2		= 		$biblemodel-> _buildQuery_Chapter($str_Second_Bible_Version,$int_Bible_Book_ID,$int_Bible_Chapter);
		$int_max_chapter 	= 		$biblemodel-> _buildQuery_Max_Chapter($int_Bible_Book_ID);
		$int_max_verse 		= 		$biblemodel-> _buildQuery_Max_Verse($int_Bible_Book_ID,$int_Bible_Chapter);
		
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
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$this->assignRef('user',		JFactory::getUser());
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

		parent::display($tpl);
	}
}