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

defined('_JEXEC') or die('Restricted access'); ?>
<?php 
class BibleReadingPlanSeperate
{
		/*
			a = plan
			b = bible
			c = day
		*/

	public function __construct($item)
	{
		$book = 0;
		$chap = 0;
		$int_end_verse = 0;
		$int_begin_verse = 0;
		$y = 1;
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();	
		
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		echo '<channel>'.PHP_EOL;
		echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
		echo '	<image>'.PHP_EOL;
		echo '	  <url>'.JURI::root().$item->str_default_image.'</url>'.PHP_EOL;
		echo '	  <title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $item->str_reading_plan_name." - ". $item->str_bible_name. '</title>'.PHP_EOL;
		echo '	  <link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?amp;ord='.date("mdy").'</link>'.PHP_EOL;
		echo '	</image>'.PHP_EOL;		
		echo '	<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $item->str_reading_plan_name.  " - ". $item->str_bible_name. '</title>'.PHP_EOL;
		echo '	<link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'</link>'.PHP_EOL;				
		echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
		echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
		echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
		echo '	<description>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE_DESC').' "'.$item->str_reading_plan_name.'" '. JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $item->int_day_number. ". ".JText::_($item->str_description).'</description>'.PHP_EOL;		
		//print_r($item->arr_reading);
		foreach($item->arr_plan as $reading)
		{
			$x= 1;
			$int_len_reading = count($reading);
			foreach($reading as $plan)
			{				
				$int_begin_verse = 0;
				$int_end_verse = 0;
				$str_verse .= "		 ".strip_tags($plan->verse). PHP_EOL;
				if($x == 1);
				{
					$int_begin_verse = $x;
				}

				if($x >= $int_len_reading)
				{
					echo '	<item>'.PHP_EOL;
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					// loop over particular days day and get verse begin and end values
					foreach($item->arr_reading as $arr_reading)
					{
						if(($arr_reading->begin_chapter == $chap)and($arr_reading->book_id == $book)and($plan->verse_id == $arr_reading->end_verse))
						{
							if((	$arr_reading->begin_verse != 0)and($arr_reading->end_verse !=0))
							{
									$int_begin_verse =$arr_reading->begin_verse;
									$int_end_verse = $arr_reading->end_verse;
							}
						}
					}
					echo '		<title>'.$mdl_common->fnc_make_scripture_title($book, $chap, $int_begin_verse, $chap, $int_end_verse)."</title>".PHP_EOL;			
					echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'#'.$y.'</link>'.PHP_EOL;	
					echo '		<guid>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'#'.$y.'</guid>'.PHP_EOL;
					echo '		<pubDate>'.$item->str_today.'</pubDate>'.PHP_EOL;
					echo '		<description>'.PHP_EOL;
					echo 		$str_verse;
					echo '		</description>'.PHP_EOL;
					echo '	</item>'.PHP_EOL;
					$str_verse = '';
					$y++;
				}
				$x++;
			}
			
		}
		echo '</channel>'.PHP_EOL;
		echo '</rss>';		
	}
}
?>