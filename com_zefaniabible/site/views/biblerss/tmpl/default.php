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
$cls_BibleRSS = new BibleRss($this->arr_Bible_Chapter, $this->str_Bible_Name); 

class BibleRss {

	public function __construct($arr_Bible_Chapter, $str_Bible_Name)
	{
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_primary_bible = 		$this->params->get('primaryBible', 'kjv');
		$str_Bible_Version = JRequest::getCmd('a', $str_primary_bible);	
		$int_book_id = JRequest::getInt('b', 1);
		$int_chapter_id = JRequest::getInt('c', 1);
		$str_feed_type = JRequest::getCmd('d', 'rss');	
		
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$str_menuItem = $this->params->get('rp_mo_menuitem', 0);
		$str_verse = '';											
		$str_url_link = "index.php?option=com_zefaniabible&view=standard&a=".$str_Bible_Version."&b=".
			$int_book_id."&c=".$int_chapter_id."&Itemid=".$str_menuItem."&ord=".date("mdy");	
		$str_url_escaped = 	str_replace('&', '&amp;',$str_url_link);
		$str_admin_email = $this->params->get('adminEmail', 'admin@'.substr(JURI::root(),7,-1));
		
		foreach($arr_Bible_Chapter as $obj_chapter)
		{
				$str_verse = $str_verse. '				'.strip_tags($obj_chapter->verse).''.PHP_EOL;
		}
		if($str_feed_type == 'rss')
		{	
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
			echo '<channel>'.PHP_EOL;
			echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
			echo '	<image>'.PHP_EOL;
			echo '	  <url>'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'</url>'.PHP_EOL;
			echo '	  <title>'.$str_Bible_Name.'</title>'.PHP_EOL;
			echo '	  <link>'.JRoute::_(JURI::base().$str_url_escaped).'</link>'.PHP_EOL;
			echo '	</image>'.PHP_EOL;		
			echo '	<title>'.$str_Bible_Name.'</title>'.PHP_EOL;
			echo '	<link>'.JRoute::_(JURI::base().$str_url_escaped).'</link>'.PHP_EOL;			
			echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
			echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
			echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
			echo '	<description>';
			echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_chapter_id;
			echo '</description>'.PHP_EOL;		
			echo '	<item>'.PHP_EOL;
			echo '		<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_chapter_id."</title>".PHP_EOL;			
			echo '		<link>'.JRoute::_(JURI::base().$str_url_escaped).'</link>'.PHP_EOL;	
			echo '		<guid>'.JRoute::_(JURI::base().$str_url_escaped).'</guid>'.PHP_EOL;
			echo '		<pubDate>'.date('D, d M Y H:i:s O').'</pubDate>'.PHP_EOL;
			echo '		<description>'.PHP_EOL;
			echo $str_verse;
			echo '		</description>'.PHP_EOL;
			echo '	</item>'.PHP_EOL;			
			echo '</channel>'.PHP_EOL;
			echo '</rss>';	
		}
		else
		{
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<feed xmlns="http://www.w3.org/2005/Atom">'.PHP_EOL;
        	echo '	<title>'.$str_Bible_Name.'</title>'.PHP_EOL;
			echo '  <subtitle>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_chapter_id.'</subtitle>'.PHP_EOL;
			echo '  <link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" />'.PHP_EOL;
			echo '  <link href="'.JRoute::_(JURI::base().$str_url_escaped).'" />'.PHP_EOL;
			echo '  <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '  <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			echo '  	<entry>'.PHP_EOL;
			echo '      	<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$int_chapter_id.'</title>'.PHP_EOL;
			echo '          <link href="'.JRoute::_(JURI::base().$str_url_escaped).'" />'.PHP_EOL;
			echo '          <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '          <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			echo '          <summary>'.PHP_EOL;
			echo 				$str_verse;
			echo '			</summary>'.PHP_EOL;
			echo '          <author>'.PHP_EOL;
			echo '          	<name>'.$mainframe->getCfg('sitename').'</name>'.PHP_EOL;
			echo '              <email>'.$str_admin_email.'</email>'.PHP_EOL;
			echo '			</author>'.PHP_EOL;
			echo '	</entry>'.PHP_EOL;
			echo '</feed>';				
		}
	}
}
?>