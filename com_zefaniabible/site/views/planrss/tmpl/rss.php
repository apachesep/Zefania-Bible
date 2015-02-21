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
class PlanRss 
{

	public function __construct($item)
	{
		/*
			a = Plan Alias
			b = Bible Alias
			c = start day filter
			d = number of items
			e = feed type atom/rss
		*/			
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$mainframe = JFactory::getApplication();
		$doc = JFactory::getDocument();
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$str_menuItem = $params->get('rp_mo_menuitem', 0);										
		$str_url_link = '';	
		$str_url_escaped = 	str_replace('&', '&amp;',$str_url_link);
		$str_admin_email = $params->get('adminEmail', 'admin@'.substr(JURI::root(),7,-1));
		$str_subtitle = '';
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
			echo '<channel>'.PHP_EOL;
			echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
			echo '	<image>'.PHP_EOL;
			echo '	  <url>'.JURI::root().$item->str_default_image.'</url>'.PHP_EOL;
			echo '	  <title>'.$item->str_bible_name.'</title>'.PHP_EOL;
			echo '	  <link>'.substr(JURI::base(),0, -1).JRoute::_($str_url_escaped).'</link>'.PHP_EOL;
			echo '	</image>'.PHP_EOL;		
			echo '	<title>'.$item->str_reading_plan_name.' - '.$item->str_bible_name.'</title>'.PHP_EOL;
			echo '	<link>'.substr(JURI::base(),0, -1).JRoute::_($str_url_escaped).'</link>'.PHP_EOL;			
			echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
			echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
			echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
			echo '	<description>';
			echo 		$item->str_description;
			echo '</description>'.PHP_EOL;
			$x = 0;
			
			foreach ($item->arr_reading as $obj_plan_info)
			{
				$str_link = substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$obj_plan_info->day_number.'&Itemid='.$item->str_view_plan, false);
				$str_url_escaped = 	str_replace('&', '&amp;',$str_link);
				$str_subtitle .= $mdl_common->fnc_make_scripture_title($obj_plan_info->book_id, $obj_plan_info->begin_chapter, $obj_plan_info->begin_verse, $obj_plan_info->end_chapter, $obj_plan_info->end_verse);
				if(($x == (count($item->arr_reading)-1) )or($item->arr_reading[($x+1)]->day_number > $obj_plan_info->day_number))
				{
					echo '	<item>'.PHP_EOL;
					echo '		<title>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $obj_plan_info->day_number.'</title>'.PHP_EOL;			
					echo '		<link>'.$str_url_escaped.'</link>'.PHP_EOL;	
					echo '		<guid>'.$str_url_escaped.'</guid>'.PHP_EOL;
					echo '		<pubDate>'.$item->str_today.'</pubDate>'.PHP_EOL;
					echo '		<description>'.$str_subtitle.'</description>'.PHP_EOL;
					echo '	</item>'.PHP_EOL;	
					$str_subtitle = '';
				}
				else
				{
					$str_subtitle = $str_subtitle.", ";
				}
				$x++;		
			}
			echo '</channel>'.PHP_EOL;
			echo '</rss>';	
	}
	
}
?>