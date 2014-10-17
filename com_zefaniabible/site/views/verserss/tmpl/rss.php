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

class ClsVerseRSS
{
	public function __construct($item)
	{
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();		
		$str_verse = '';
		$x = 1;
		$int_book_name = 1;
		$int_chapter_number = 1;
		$int_begin_verse = 1;
		$int_end_verse = 0;
		foreach ($item->arr_verse_info as $obj_arr_verse_info)
		{
			$int_book_name 		= 	$obj_arr_verse_info->book_name;	
			$int_chapter_number =	$obj_arr_verse_info->chapter_number;
			$int_begin_verse	=	$obj_arr_verse_info->begin_verse;
			$int_end_verse 		=	$obj_arr_verse_info->end_verse;
		}
		$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_name)." ".$int_chapter_number.":".$int_begin_verse;
		if($int_end_verse)
		{
			$str_title .= "-". $int_end_verse;
		}
			foreach ($item->arr_verse_of_day as $obj_verse)
			{
				$str_verse .= " ". $obj_verse->verse;
			}			
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
			echo '<channel>'.PHP_EOL;
			echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
			echo '	<image>'.PHP_EOL;
			echo '	  <url>'.JURI::root().$item->str_default_image.'</url>'.PHP_EOL;
			echo '	  <title>'.JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY').'</title>'.PHP_EOL;
			echo '	  <link>'.JRoute::_(JURI::base()).'index.php?ord='.date("mdy").'</link>'.PHP_EOL;
			echo '	</image>'.PHP_EOL;		
			echo '	<title>'.JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY').'</title>'.PHP_EOL;
			echo '	<link>'.JRoute::_(JURI::base()).'index.php?ord='.date("mdy").'</link>'.PHP_EOL;				
			echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
			echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
			echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
			echo '	<description>'.JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY').'</description>'.PHP_EOL;
			echo '	<item>'.PHP_EOL;
			echo '		<title>'.$str_title.'</title>'.PHP_EOL;	 
			echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&amp;view=verserss&amp;variant=html&amp;&amp;bible='.$item->str_Bible_Version.'&amp;day='.$item->int_day_number.'&amp;Itemid='.$item->str_view_plan.'&amp;ord='.date("mdy")).'</link>'.PHP_EOL;	
			echo '		<guid>'.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&amp;view=verserss&amp;variant=html&amp;bible='.$item->str_Bible_Version.'&amp;day='.$item->int_day_number.'&amp;Itemid='.$item->str_view_plan.'&amp;ord='.date("mdy")).'</guid>'.PHP_EOL;
			echo '		<pubDate>'.$item->str_today.'</pubDate>'.PHP_EOL;		
			echo '		<description>'.PHP_EOL;	
			echo '			'.strip_tags($str_verse).PHP_EOL;	
			echo '		</description>'.PHP_EOL;
			echo '	</item>'.PHP_EOL;			
			echo '</channel>'.PHP_EOL;
			echo '</rss>';	
	}
}

?>