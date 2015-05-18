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
class PlanAtom
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
			echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
			echo '<feed xmlns="http://www.w3.org/2005/Atom">'.PHP_EOL;
        	echo '	<title>'.$item->str_reading_plan_name.' - '.$item->str_bible_name.'</title>'.PHP_EOL;
			echo '  <subtitle>'.$item->str_description.'</subtitle>'.PHP_EOL;
			echo '  <link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" />'.PHP_EOL;
			echo '  <link href="'.substr(JURI::base(),0, -1).JRoute::_($str_url_escaped).'" />'.PHP_EOL;
			echo '  <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').'</id>'.PHP_EOL;
			echo '  <updated>'.date('Y-m-d\TH:i:sP').'</updated>'.PHP_EOL;
			$x = 0;
			$y = time ();
			foreach ($item->arr_reading as $obj_plan_info)
			{
				$str_link = substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$obj_plan_info->day_number.'&Itemid='.$item->str_view_plan, false);
				$str_url_escaped = 	str_replace('&', '&amp;',$str_link);
				$str_subtitle .= $mdl_common->fnc_make_scripture_title($obj_plan_info->book_id, $obj_plan_info->begin_chapter, $obj_plan_info->begin_verse, $obj_plan_info->end_chapter, $obj_plan_info->end_verse);
				if($item->arr_reading[$x+1]->day_number > $obj_plan_info->day_number)
				{						
					echo '  	<entry>'.PHP_EOL;
					echo '      	<title>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $obj_plan_info->day_number.'</title>'.PHP_EOL;
					echo '          <link href="'.$str_url_escaped.'" />'.PHP_EOL;
					echo '          <id>tag:'.substr(JURI::root(),7,-1).','.date('Y-m-d').':'.date('Ymd').$y.'</id>'.PHP_EOL;
					echo '          <updated>'.date('Y-m-d\TH:i:sP',$y).'</updated>'.PHP_EOL;
					echo '          <summary>'.$str_subtitle.'</summary>'.PHP_EOL;
					echo '          <author>'.PHP_EOL;
					echo '          	<name>'.$mainframe->getCfg('sitename').'</name>'.PHP_EOL;
					echo '              <email>'.$str_admin_email.'</email>'.PHP_EOL;
					echo '			</author>'.PHP_EOL;
					echo '	</entry>'.PHP_EOL;
					$str_subtitle = '';
				}
				else
				{
					$str_subtitle = $str_subtitle.", ";
				}				
				$x++;
				$y = $y - 60;
			}
			echo '</feed>';				
	}
}
?>