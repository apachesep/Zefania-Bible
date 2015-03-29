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
class PlanICal
{

	public function __construct($item)
	{
		$params 	= JComponentHelper::getParams( 'com_zefaniabible' );
		$doc 		= JFactory::getDocument();
		$mainframe 	= JFactory::getApplication();
		$str_offset = $mainframe->getCfg('offset');
		

		$date = new JDate('now', new DateTimeZone($str_offset));
		$int_hour_offset = $date->getOffsetFromGMT(true);
		$str_time_zone_abr = $date->format("T"); 
		$str_cal_name 	= $mainframe->getCfg('sitename')." - ".$item->str_bible_name." - ".$item->str_reading_plan_name;
		
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_common 	= new ZefaniabibleCommonHelper;

		echo 'BEGIN:VCALENDAR'.PHP_EOL;
		echo 'PRODID:-//Google Inc//Google Calendar 70.9054//EN'.PHP_EOL;
		echo 'VERSION:2.0'.PHP_EOL;
		echo 'CALSCALE:GREGORIAN'.PHP_EOL;
		echo 'METHOD:PUBLISH'.PHP_EOL;
		echo 'X-WR-CALNAME:'.$str_cal_name.PHP_EOL;
		echo 'X-WR-TIMEZONE:'.$str_offset.PHP_EOL;
		echo 'BEGIN:VTIMEZONE'.PHP_EOL;
		echo 'TZID:'.$str_offset.PHP_EOL;
		echo 'X-LIC-LOCATION:'.$str_offset.PHP_EOL;
		
		echo 'END:VTIMEZONE'.PHP_EOL;
		
		$x = 0;
		$str_subtitle = '';

		foreach($item->arr_reading as $obj_plan_info)
		{
			$str_subtitle .= $mdl_common->fnc_make_scripture_title($obj_plan_info->book_id, $obj_plan_info->begin_chapter, $obj_plan_info->begin_verse, $obj_plan_info->end_chapter, $obj_plan_info->end_verse);
			$str_link = substr(JURI::base(),0, -1).JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$obj_plan_info->day_number.'&Itemid='.$item->str_view_plan, false);
			$str_url_escaped = 	str_replace('&', '&amp;',$str_link);			
			if(($x == (count($item->arr_reading)-1) )or($item->arr_reading[($x+1)]->day_number > $obj_plan_info->day_number))
			{
				echo 'BEGIN:VEVENT'.PHP_EOL;
				echo 'DTSTART:'.date('Ymd\THis', strtotime($date . ' + '.$obj_plan_info->day_number.' day')).'Z'.PHP_EOL;
				echo 'DTEND:'.date('Ymd\THis', strtotime($date . ' + '.$obj_plan_info->day_number.' day')).'Z'.PHP_EOL;
				echo 'DTSTAMP:'.$date->format('Ymd\THis').'Z'.PHP_EOL;
				echo 'UID:'.$mainframe->getCfg('mailfrom').PHP_EOL;
				echo 'ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;CN='.$mainframe->getCfg('sitename').';X-NUM-GUESTS=0:mailto:'.$mainframe->getCfg('mailfrom').PHP_EOL;
				echo 'CREATED:'.$date.'Z'.PHP_EOL;
				echo 'DESCRIPTION:'.PHP_EOL;
				echo 'FREEBUSY=FREE'.PHP_EOL;
				echo 'LAST-MODIFIED:'.$date.'Z'.PHP_EOL;
				echo 'LOCATION:'.$str_url_escaped.PHP_EOL;
				echo 'SEQUENCE:1'.PHP_EOL;
				echo 'STATUS:TENTATIVE'.PHP_EOL;
				echo 'SUMMARY:'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$obj_plan_info->day_number." - ".$str_subtitle.PHP_EOL;
				echo 'TRANSP:OPAQUE'.PHP_EOL;
				echo 'CATEGORIES:http://schemas.google.com/g/2005#event'.PHP_EOL;
				echo 'END:VEVENT'.PHP_EOL;
				$str_subtitle = '';
			}
			else
			{
				$str_subtitle .= ', ';
			}
			$x++;
		}
		echo 'END:VCALENDAR'.PHP_EOL;
	}	
}
?>