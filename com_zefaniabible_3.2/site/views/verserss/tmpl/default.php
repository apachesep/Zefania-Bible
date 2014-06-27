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

$cls_verse_rss = new ClsVerseRSS($this->arr_verse_info, $this->int_verse_remainder, $this->arr_verse, $this->str_bible_Version);

class ClsVerseRSS
{
	
	public function __construct($arr_verse_info, $int_verse_remainder, $arr_verse, $str_bible_Version)
	{
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$flg_redirect_request = JRequest::getCmd('b', 1);
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_default_image = $params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');		
		
		$str_verse = '';
		$x = 1;

		$int_book_name 		= 	$arr_verse_info['book_name'][$int_verse_remainder];	
		$int_chapter_number =	$arr_verse_info['chapter_number'][$int_verse_remainder];
		$int_begin_verse	=	$arr_verse_info['begin_verse'][$int_verse_remainder];
		$int_end_verse 		=	$arr_verse_info['end_verse'][$int_verse_remainder];
		$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_name)." ".$int_chapter_number.":".$int_begin_verse;
		if($int_end_verse)
		{
			$str_title = $str_title. "-". $int_end_verse;
		}

		if($flg_redirect_request)
		{
			foreach ($arr_verse as $obj_verse)
			{
				$str_verse = $str_verse." ". $obj_verse->verse;
			}			
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
			echo '<channel>'.PHP_EOL;
			echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
			echo '	<image>'.PHP_EOL;
			echo '	  <url>'.JURI::root().$str_default_image.'</url>'.PHP_EOL;
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
			echo '		<link>'.JRoute::_(JURI::base()).'index.php?option=com_zefaniabible&amp;view=verserss&amp;a='.$str_bible_Version.'&amp;b=0&amp;c='.$int_verse_remainder.'&amp;ord='.date("mdy").'</link>'.PHP_EOL;	
			echo '		<guid>'.JRoute::_(JURI::base()).'index.php?option=com_zefaniabible&amp;view=verserss&amp;a='.$str_bible_Version.'&amp;b=0&amp;c='.$int_verse_remainder.'&amp;ord='.date("mdy").'</guid>'.PHP_EOL;
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
			$this->doc_page->setMetaData( 'og:image', JURI::root().$str_default_image );	
			$this->doc_page->setMetaData( 'og:description', JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY') );
			$this->doc_page->setMetaData( 'og:site_name', $mainframe->getCfg('sitename') );				
			echo '<div id="zef_Bible_Main_verse_tmpl_comp">';
			echo '<div class="zef_bible_Header_Label">'.$str_title.'</div>';
			echo '<div style="clear:both"></div>';
			foreach ($arr_verse as $obj_verse)
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