<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Zefaniabible helper class.
 *
 * @package     Zefaniabible
 * @subpackage  Helpers
 */
class ZefaniabibleHelper
{ 
	public static function addSubmenu($vName)
	{ 
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;	
		$cnt_comment 	= $mdl_default->fnc_count_publications('comment');
		$cnt_bibles 	= $mdl_default->fnc_count_publications('bible');
		$cnt_dict 		= $mdl_default->fnc_count_publications('dict');
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_MENU_CPANEL'), 
			'index.php?option=com_zefaniabible&view=cpanel', 
			$vName == 'cpanel'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_LAYOUT_BIBLES'), 
			'index.php?option=com_zefaniabible&view=zefaniabible', 
			$vName == 'zefaniabible'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_LAYOUT_COMMENTARIES'), 
			'index.php?option=com_zefaniabible&view=zefaniacomment', 
			$vName == 'zefaniacomment'
		);		
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_MENU_DICTIONARY'), 
			'index.php?option=com_zefaniabible&view=zefaniadictionary', 
			$vName == 'zefaniadictionary'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_FIELD_VERSE_OF_DAY'), 
			'index.php?option=com_zefaniabible&view=zefaniaverseofday', 
			$vName == 'zefaniaverseofday'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_FIELD_READING_PLAN'), 
			'index.php?option=com_zefaniabible&view=zefaniareading', 
			$vName == 'zefaniareading'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS'), 
			'index.php?option=com_zefaniabible&view=zefaniareadingdetails', 
			$vName == 'zefaniareadingdetails'
		);
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_LAYOUT_USERS'), 
			'index.php?option=com_zefaniabible&view=zefaniauser', 
			$vName == 'zefaniauser'
		);
		if($cnt_bibles >=1)
		{
			JHtmlSidebar::addEntry(
				JText::_('ZEFANIABIBLE_MENU_SCRIPTURES'), 
				'index.php?option=com_zefaniabible&view=zefaniascripture', 
				$vName == 'zefaniascripture'
			);		
		}
		if($cnt_comment >= 1)
		{
			JHtmlSidebar::addEntry(
				JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT'), 
				'index.php?option=com_zefaniabible&view=zefaniacommentdetail', 
				$vName == 'zefaniacommentdetail'
			);
		}
		if($cnt_dict >= 1)
		{
			JHtmlSidebar::addEntry(
				JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT'), 
				'index.php?option=com_zefaniabible&view=zefaniabibledictdetail', 
				$vName == 'zefaniabibledictdetail'
			);
		}
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_MENU_CROSS_REF_EDIT'), 
			'index.php?option=com_zefaniabible&view=zefaniacrossref', 
			$vName == 'zefaniacrossref'
		);		
		JHtmlSidebar::addEntry(
			JText::_('ZEFANIABIBLE_MENU_PUBLISH_EDIT'), 
			'index.php?option=com_zefaniabible&view=zefaniapublish', 
			$vName == 'zefaniapublish'
		);

	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function fncParseScritpure($str_search_passage)
	{
		$item = new stdClass();			
		for($z = 1; $z <= 66; $z ++)
		{
			$item->int_book_id = 0;
			$item->int_begin_chapter = 0;
			$item->int_end_chapter = 0;				
			$item->int_verse = 0;			
			$str_Bible_book = mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
			if(preg_match('/^('.$str_Bible_book.')/', trim(mb_strtolower($str_search_passage,'UTF-8'))))
			{
				$item->int_book_id = $z;
				$str_passage = trim(str_replace($str_Bible_book ,'', mb_strtolower($str_search_passage,'UTF-8')));
				switch (true)
				{
					case preg_match('/^([0-9]{1,3})-([0-9]{1,3})$/',$str_passage):					//Gen 1-4	
					case preg_match('/^([0-9]{1,3})$/',$str_passage):								// Gen 1
						$arr_split_verses = preg_split('#[-]#',$str_passage); 						// split on hyphen
						if(count($arr_split_verses) == 2)
						{
							list($item->int_begin_chapter,$item->int_end_chapter) = $arr_split_verses;
						}
						else
						{
							$item->int_begin_chapter = $str_passage;
						}
						break;						
						
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})$/',$str_passage):   				// Gen 1:1
						$arr_split_verses = preg_split('#[:-]+#',$str_passage);
						list($item->int_begin_chapter, $item->int_verse) = $arr_split_verses;
						break;
					default:
						break;
				}
				break;
			}
		}
		return $item;
	}
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_zefaniabible';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}