<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabiblebooknames
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
defined('_JEXEC') or die;



if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
{
	JError::raiseWarning('5', 'ZefaniaBible - Verse of the Day Module - ZefaniaBible component is not installed or not enabled.');
	return;
}
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
JHTML::stylesheet('verse.css', 'modules/mod_verseoftheday/css/'); 

	// load core component functions
	require_once('components/com_zefaniabible/models/default.php');
	require_once('components/com_zefaniabible/helpers/common.php');
	$mdl_default 	= new ZefaniabibleModelDefault;
	$mdl_common 	= new ZefaniabibleCommonHelper;

	$item = new stdClass();
	$item->str_Bible_Version		= $params->get('bibleAlias', $mdl_default->_buildQuery_first_record());
	$item->int_link_type 			= $params->get('link_type', 0);
	$item->int_display_order 		= $params->get('display_order', 0);
	$item->str_menuItem 			= $params->get('vd_mo_menuitem', 0);
	$item->flg_import_user_data 	= $params->get('flg_import_user_data', '0');
	$item->flg_use_biblegateway 	= $params->get('flg_use_biblegateway', '0');
	$item->str_biblegateway_version = $params->get('str_biblegateway_version', 'KJV');
	$item->str_custom_html 			= $params->get('str_custom_html');
	$item->str_start_date 			= $params->get('start_date');	
	$user 							= JFactory::getUser();
	
	// don't call this code for Biblegateway
	if($item->flg_use_biblegateway == 0)
	{		
		// load langauges
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();
		$jlang = JFactory::getLanguage();
		$jlang->load('mod_verseoftheday', JPATH_BASE."/modules/mod_verseoftheday", 'en-GB', true);
		$jlang->load('mod_verseoftheday', JPATH_BASE."/modules/mod_verseoftheday", null, true);
					 
		// get Bible version for user
		if(($user->id > 0)and($item->flg_import_user_data))
		{
			$item->arr_user_data = $mdl_default->_buildQuery_getUserData($user->id);
			foreach($item->arr_user_data as $obj_user_data)
			{
				$item->str_Bible_Version = $obj_user_data->bible_alias;
			}
		}
		
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_verse_of_day_verse();

		switch($item->int_display_order)
		{
			// random
			case 1:
				$item->int_day_number = mt_rand(1, $item->int_max_days);
				break;
			// day of the year
			case 2:
				$item->int_day_number =  (date('z')+1);
				break;
			// sequential sorting order
			default:
				$item->int_day_number =	$mdl_common->fnc_calcualte_day_diff($item->str_start_date, $item->int_max_days);			
				break;			
		}
		$item->arr_verse_info					= 	$mdl_default->_buildQuery_get_verse_of_the_day_info($item->int_day_number);
		$item->arr_verse_of_day					=	$mdl_default->_buildQuery_get_verse_of_the_day($item->arr_verse_info, $item->str_Bible_Version);

		foreach($item->arr_verse_info as $obj_verse_info)
		{
			$item->int_book_id 			= $obj_verse_info->book_name;
			$item->int_chapter_number 	= $obj_verse_info->chapter_number;
			$item->int_begin_verse 		= $obj_verse_info->begin_verse;
			$item->int_end_verse 		= $obj_verse_info->end_verse;
		}
		
		foreach($item->arr_verse_of_day as $obj_verse)
		{
			$item->str_verse .= " ".$obj_verse->verse;
		}
		$item->str_scripture = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_book_id)." ".$item->int_chapter_number.":".$item->int_begin_verse;
		if($item->int_end_verse != 0)
		{
			$item->str_scripture .= "-".$item->int_end_verse;	
		}
		
		$item->str_url = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$item->str_menuItem.
							"&bible=".$item->str_Bible_Version.
							"&book=".$item->int_book_id."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_book_id])).
							"&chapter=".$item->int_chapter_number.'-chapter');
	}
	else
	{
		$str_verse_rss = simplexml_load_file('http://www.biblegateway.com/votd/get/?format=atom&version='.$item->str_biblegateway_version);
		$item->str_url = $str_verse_rss->entry->link['href'];
		$item->str_scripture = stripslashes(strip_tags(trim($str_verse_rss->entry->title)));
		$item->str_verse = stripslashes(strip_tags(trim($str_verse_rss->entry->content)));
	}
	$item->str_title_wrapper = '<div class="zef_verse_of_day_header">'.$item->str_scripture."</div>";
	$item->str_verse_wrapper = '<div class="zef_verse_of_day_verse">'.trim($item->str_verse).'</div>';
			
	$item->str_verse_output = str_replace('{link}','<a href="'.$item->str_url.'" target="_self" rel="nofollow" id="zef_links" title="'.JText::_('MOD_ZEFANIABIBLE_VERSE_OF_THE_DAY_BIBLE_LINK_DESC').'">',$item->str_custom_html);
	$item->str_verse_output = str_replace('{/link}','</a>',$item->str_verse_output);
	$item->str_verse_output = str_replace('{passage}',$item->str_verse_wrapper,$item->str_verse_output);
	$item->str_verse_output = str_replace('{scripture}',$item->str_title_wrapper,$item->str_verse_output);		
	
	echo $item->str_verse_output;

?>