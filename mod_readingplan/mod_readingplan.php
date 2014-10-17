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
defined('_JEXEC') or die('Restricted access');
		if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
		{
			JError::raiseWarning('5', 'ZefaniaBible - Reading Plan Module - ZefaniaBible component is not installed or not enabled.');
			return;
		
		}
		JHTML::stylesheet('verse.css', 'modules/mod_verseoftheday/css/'); 
		$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

		// load languages
		$jlang = JFactory::getLanguage();
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		$jlang->load('mod_readingplan', JPATH_BASE."/modules/mod_readingplan", 'en-GB', true);
		$jlang->load('mod_readingplan', JPATH_BASE."/modules/mod_readingplan", null, true);
		
		// import component classes
		require_once('components/com_zefaniabible/models/default.php');
		require_once('components/com_zefaniabible/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$item = new stdClass();
		$item->str_primary_reading 		= 	$params->get('reading_plan', $mdl_default->_buildQuery_first_plan());
		$item->str_primary_bible 		= 	$params->get('bibleAlias', $mdl_default->_buildQuery_first_record());	
		$item->str_menuItem 			= 	$params->get('rp_mo_menuitem', 0);
		$item->str_start_reading_date 	= 	$params->get('reading_start_date', '1-1-2012');
		$item->str_custom_html 			= 	$params->get('str_custom_html');
		$item->flg_import_user_data 	=	$params->get('flg_import_user_data', '0');
		$user 							= 	JFactory::getUser();
		
		// use user data
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
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_reading_days($item->str_primary_reading);
		$item->int_day_number					= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days);
		$item->arr_reading						=	$mdl_default->_buildQuery_reading_plan($item->str_primary_reading,$item->int_day_number);
		
		$item->cnt_reading_elements = count($item->arr_reading);
		$item->str_link = JRoute::_("index.php?option=com_zefaniabible&view=reading&Itemid=".$item->str_menuItem."&plan=".$item->str_primary_reading."&bible=".$item->str_primary_bible."&day=".$item->int_day_number);
		$item->str_verse_output_link = '<a rel="follow" title="'.JText::_('MOD_ZEFANIABIBLE_READING_PLAN_CLICK_TITLE').'" href="'.$item->str_link.'" target="_self">';		
		
		$x =0;
		foreach ($item->arr_reading as $arr_reading)
		{
			if(($arr_reading->begin_verse == 0)and($arr_reading->end_verse == 0))
			{
				if($arr_reading->begin_chapter == $arr_reading->end_chapter)
				{
					$item->str_scripture .= JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter;					
				}
				else
				{
					$item->str_scripture .= JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter."-".$arr_reading->end_chapter;
				}
			}
			else
			{
				$item->str_scripture .=  JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_reading->book_id)." ".$arr_reading->begin_chapter.":".$arr_reading->begin_verse."-".$arr_reading->end_chapter.":".$arr_reading->end_verse;
			}
			if(($item->cnt_reading_elements > 1)and($x <  $item->cnt_reading_elements))
			{
				$item->str_scripture .=  "<br>";
			}			
			$x++;
		}
			$item->str_verse_output = str_replace('{link}',$item->str_verse_output_link,$item->str_custom_html);
			$item->str_verse_output = str_replace('{/link}','</a>',$item->str_verse_output);
			$item->str_verse_output = str_replace('{scripture}',trim($item->str_scripture),$item->str_verse_output);
					
		echo $item->str_verse_output;	
		
?>

