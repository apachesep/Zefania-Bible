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
$cls_verse_rss = new ClsVerseRSS($this->item);

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

		if($item->flg_redirect_request)
		{
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
			echo '		<link>'.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&amp;view=verserss&amp;bible='.$item->str_Bible_Version.'&amp;type=0&amp;day='.$item->int_day_number.'&amp;Itemid='.$item->str_view_plan.'&amp;ord='.date("mdy")).'</link>'.PHP_EOL;	
			echo '		<guid>'.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&amp;view=verserss&amp;bible='.$item->str_Bible_Version.'&amp;type=0&amp;day='.$item->int_day_number.'&amp;Itemid='.$item->str_view_plan.'&amp;ord='.date("mdy")).'</guid>'.PHP_EOL;
			echo '		<pubDate>'.date('D, d M Y 00:00:00').'</pubDate>'.PHP_EOL;		
			echo '		<description>'.PHP_EOL;	
			echo '			'.strip_tags($str_verse).PHP_EOL;	
			echo '		</description>'.PHP_EOL;
			echo '	</item>'.PHP_EOL;			
			echo '</channel>'.PHP_EOL;
			echo '</rss>';	
		}
		else
		{
			// Facebook Open Graph
			$this->doc_page = JFactory::getDocument();	
			$this->doc_page->setMetaData( 'og:title', JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY'));
			$this->doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
			$this->doc_page->setMetaData( 'og:type', "article" );	
			$this->doc_page->setMetaData( 'og:image', JURI::root().$item->str_default_image );	
			$this->doc_page->setMetaData( 'og:description', JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY') );
			$this->doc_page->setMetaData( 'og:site_name', $mainframe->getCfg('sitename') );				
			echo '<div id="zef_Bible_Main_verse_tmpl_comp">';
			echo '<div class="zef_bible_Header_Label">'.$str_title.'</div>';
			echo '<div style="clear:both"></div>';
			foreach ($item->arr_verse_of_day as $obj_verse)
			{
				if ($x % 2)
				{
					echo '<div class="odd">';
				}
				else
				{
					echo '<div class="even">'; 
				}	
				echo $obj_verse->verse.'</div>';
				$x++;
			}
			echo '<div style="clear:both"></div>';
			echo '</div>';			
		}
	}
}

?>