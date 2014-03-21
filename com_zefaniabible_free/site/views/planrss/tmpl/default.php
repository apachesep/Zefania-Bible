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
$cls_PlanRSS = new PlanRss($this->str_Bible_Name, $this->arr_Plan_Info); 
class PlanRss 
{

	public function __construct($str_Bible_Name, $arr_Plan_Info)
	{
		/*
			a = Plan Alias
			b = Bible Alias
			c = start day filter
			d = number of items
			e = feed type atom/rss
		*/			
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$mainframe = JFactory::getApplication();
		$str_primary_bible = 		$this->params->get('primaryBible', 'kjv');
		$str_primary_plan = 		$this->params->get('primaryReading');
		$str_plan_alias = 	JRequest::getCmd('a', $str_primary_plan);	
		$str_Bible_Version = JRequest::getCmd('b', $str_primary_bible);	
		$int_start_item = JRequest::getInt('c', JRequest::getVar('limitstart', 0, '', 'int'));
		$int_number_of_items = JRequest::getInt('d', $mainframe->getCfg('feed_limit'));
		$str_feed_type = JRequest::getCmd('e', 'rss');	
		
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$str_menuItem = $this->params->get('rp_mo_menuitem', 0);										
		$str_url_link = '';	
		$str_url_escaped = 	str_replace('&', '&amp;',$str_url_link);
		$str_admin_email = $this->params->get('adminEmail', 'admin@'.substr(JURI::root(),7,-1));
		$str_plan_name = '';
		foreach ($arr_Plan_Info as $obj_plan_info)
		{
			$str_plan_name = $obj_plan_info->plan_name;
			$str_plan_desc = JText::_($obj_plan_info->plan_desc);
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
			echo '	<title>'.$str_plan_name.' - '.$str_Bible_Name.'</title>'.PHP_EOL;
			echo '	<link>'.JRoute::_(JURI::base().$str_url_escaped).'</link>'.PHP_EOL;			
			echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
			echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
			echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
			echo '	<description>';
			echo 		$str_plan_desc;
			echo '</description>'.PHP_EOL;
			$x = 0;
			foreach ($arr_Plan_Info as $obj_plan_info)
			{
				$str_subtitle = '';
				$str_link = JRoute::_(JURI::base()."index.php?option=com_zefaniabible&view=reading&a=".$str_plan_alias."&b=".$str_Bible_Version."&c=".$obj_plan_info->day_number);
				$str_url_escaped = 	str_replace('&', '&amp;',$str_link);
				$str_subtitle = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_plan_info->book_id).' '.$obj_plan_info->begin_chapter;
				if($obj_plan_info->begin_verse != 0)
				{
					$str_subtitle = $str_subtitle .":". $obj_plan_info->begin_verse;	
				}
				if($obj_plan_info->end_chapter != 0)
				{
					$str_subtitle = $str_subtitle ."-". $obj_plan_info->end_chapter;
				}
				if($obj_plan_info->end_verse != 0)
				{
					$str_subtitle = $str_subtitle .":". $obj_plan_info->end_verse;
				}
				if($x != $obj_plan_info->day_number)
				{
					echo '	<item>'.PHP_EOL;
					echo '		<title>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $obj_plan_info->day_number.'</title>'.PHP_EOL;			
					echo '		<link>'.JRoute::_(JURI::base().$str_url_escaped).'</link>'.PHP_EOL;	
					echo '		<guid>'.JRoute::_(JURI::base().$str_url_escaped).'</guid>'.PHP_EOL;
					echo '		<pubDate>'.date('D, d M Y H:i:s O').'</pubDate>'.PHP_EOL;
					echo '		<description>'.$str_subtitle.'</description>'.PHP_EOL;
					echo '	</item>'.PHP_EOL;	
				}
				$x = $obj_plan_info->day_number;		
			}
			echo '</channel>'.PHP_EOL;
			echo '</rss>';	
		}
		else
		{
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<feed xmlns="http://www.w3.org/2005/Atom">'.PHP_EOL;
        	echo '	<title>'.$str_plan_name.' - '.$str_Bible_Name.'</title>'.PHP_EOL;
			echo '  <subtitle>'.$str_plan_desc.'</subtitle>'.PHP_EOL;
			echo '  <link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" />'.PHP_EOL;
			echo '  <link href="'.JRoute::_(JURI::base().$str_url_escaped).'" />'.PHP_EOL;
			echo '  <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '  <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			$x = 0;
			$y = time ();
			foreach ($arr_Plan_Info as $obj_plan_info)
			{
				$str_subtitle = '';
				$str_link = JRoute::_(JURI::base()."index.php?option=com_zefaniabible&view=reading&a=".$str_plan_alias."&b=".$str_Bible_Version."&c=".$obj_plan_info->day_number);
				$str_url_escaped = 	str_replace('&', '&amp;',$str_link);
				$str_subtitle = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_plan_info->book_id).' '.$obj_plan_info->begin_chapter;
				if($obj_plan_info->begin_verse != 0)
				{
					$str_subtitle = $str_subtitle .":". $obj_plan_info->begin_verse;	
				}
				if($obj_plan_info->end_chapter != 0)
				{
					$str_subtitle = $str_subtitle ."-". $obj_plan_info->end_chapter;
				}
				if($obj_plan_info->end_verse != 0)
				{
					$str_subtitle = $str_subtitle .":". $obj_plan_info->end_verse;
				}		
				if($x != $obj_plan_info->day_number)
				{						
					echo '  	<entry>'.PHP_EOL;
					echo '      	<title>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $obj_plan_info->day_number.'</title>'.PHP_EOL;
					echo '          <link href="'.JRoute::_(JURI::base().$str_url_escaped).'" />'.PHP_EOL;
					echo '          <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').$y.'</id>'.PHP_EOL;
					echo '          <updated>'.date('Y-m-d\TH:i:sP',$y).'</updated>'.PHP_EOL;
					echo '          <summary>'.$str_subtitle.'</summary>'.PHP_EOL;
					echo '          <author>'.PHP_EOL;
					echo '          	<name>'.$mainframe->getCfg('sitename').'</name>'.PHP_EOL;
					echo '              <email>'.$str_admin_email.'</email>'.PHP_EOL;
					echo '			</author>'.PHP_EOL;
					echo '	</entry>'.PHP_EOL;
				}
				$x = $obj_plan_info->day_number;
				$y = $y - 60;
			}
			echo '</feed>';				
		}
	}
}
?>