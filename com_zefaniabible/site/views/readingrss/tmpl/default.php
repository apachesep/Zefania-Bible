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
JHTML::_('behavior.modal');

$cls_bible_reading_plan = new BibleReadingPlan($this->item);

class BibleReadingPlan
{
		/*
			a = plan
			b = bible
			c = day
		*/

	public function __construct($item)
	{
		if($item->flg_redirect_request == 0)
		{
			$this->fnc_output_seperate($item);
		}
		else
		{
			$this->fnc_output_single($item);	
		}
	}
	private function fnc_output_seperate($item)
	{
		$book = 0;
		$chap = 0;
		$y = 1;
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
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
	
		foreach($item->arr_plan as $reading)
		{
			$x= 1;
			$int_len_reading = count($reading);
			foreach($reading as $plan)
			{					
				if (($plan->book_id > $book)or($plan->chapter_id > $chap))
				{
					if($x > 1)
					{
						echo '		</description>'.PHP_EOL.'	</item>'.PHP_EOL;
					}
					echo '	<item>'.PHP_EOL;
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					echo '		<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id."</title>".PHP_EOL;			
					echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'#'.$y.'</link>'.PHP_EOL;	
					echo '		<guid>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'#'.$y.'</guid>'.PHP_EOL;
					echo '		<pubDate>'.date('D, d M Y H:i:s O').'</pubDate>'.PHP_EOL;
					echo '		<description>'.PHP_EOL;
					$y++;
				}
				echo "		 ".strip_tags($plan->verse). PHP_EOL;
				$x++;
			}
			echo '		</description>'.PHP_EOL.'	</item>'.PHP_EOL;
		}
		echo '</channel>'.PHP_EOL;
		echo '</rss>';		
	}
	private function fnc_output_single($item)
	{
		$book = 0;
		$chap = 0;
		$str_title = '';
		$str_desc = '';
		$y = 1;
		$int_len = 0;
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		
		echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		echo '	<channel>'.PHP_EOL;
		echo '		<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
		echo '		<image>'.PHP_EOL;
		echo '	  		<url>'.JURI::root().$item->str_default_image.'</url>'.PHP_EOL;
		echo '	  		<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $item->str_reading_plan_name." - ". $item->str_bible_name. '</title>'.PHP_EOL;
		echo '	  		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'</link>'.PHP_EOL;
		echo '		</image>'.PHP_EOL;		
		echo '		<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $item->str_reading_plan_name.  " - ". $item->str_bible_name. '</title>'.PHP_EOL;
		echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'</link>'.PHP_EOL;				
		echo '		<generator>Zefania Bible</generator>'.PHP_EOL;
		echo '		<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
		echo '		<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
		echo '		<description>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE_DESC').' "'.$item->str_reading_plan_name.'" '. JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $item->int_day_number. ". ".JText::_($str_desc).'</description>'.PHP_EOL;		
		foreach($item->arr_plan as $reading)
		{
			$x= 1;
			$int_len_reading = count($reading);
			foreach($reading as $plan)
			{					
				if (($plan->book_id > $book)or($plan->chapter_id > $chap))
				{
					$str_desc = $str_desc. PHP_EOL;
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					$str_title = $str_title. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id. ", ";
				}
				$str_desc = $str_desc. "		 ".strip_tags($plan->verse). PHP_EOL;
				$x++;
			}
		}
		$int_len = mb_strlen($str_title);
		$str_title = mb_substr($str_title, 0, ($int_len-2),'UTF-8');
		echo '		<item>'.PHP_EOL;
		echo '		<title>'.$str_title."</title>".PHP_EOL;		
		echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'</link>'.PHP_EOL;	
		echo '		<guid>'.substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&amp;view=reading&amp;plan=".$item->str_reading_plan."&amp;bible=".$item->str_Bible_Version."&amp;day=".$item->int_day_number.'&amp;Itemid='.$item->str_view_plan).'?ord='.date("mdy").'</guid>'.PHP_EOL;
		echo '		<pubDate>'.date('D, d M Y H:i:s O').'</pubDate>'.PHP_EOL;		
		echo '		<description>';	
		echo '		'.$str_desc;
		echo '		</description>'.PHP_EOL;
		echo '		</item>'.PHP_EOL;	
		echo '	</channel>'.PHP_EOL;
		echo '</rss>'.PHP_EOL;
	}
}
?>