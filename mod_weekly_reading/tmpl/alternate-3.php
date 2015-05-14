<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	
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
		defined('_JEXEC') or die('Restricted access');
		
		for($x = 0; $x < 7; $x++)
		{
				echo '<div id="zef_week_reading">';
				echo '	<div id="zef_calendar_icon-4">';
				if(($item->int_week_begin_day + $x) <= $item->int_month_max_days)
				{
					echo '		<div id="zef_week_month">'.$mdl_common->fnc_get_month_name($item->int_month, 1).'</div>';
					echo '		<div id="zef_week_date">'.($item->int_week_begin_day + $x).'</div>';
				}
				else
				{
					echo '		<div id="zef_week_month">'.$mdl_common->fnc_get_month_name(($item->int_month+1), 1).'</div>';
					echo '		<div id="zef_week_date">'.($x - $item->int_days_remain_month).'</div>';					
				}
				echo '	</div>';
				echo '	<div id="zef_week_reading-links">';
					foreach($item->arr_reading as $reading)
					{
						if($reading->day_number == ($item->int_day_diff + $x))
						{
							$url = JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_primary_reading ."&bible=".$item->str_primary_bible."&day=".$reading->day_number);
							echo "<a title='"."' id='zef_links' href='".$url."'>";
							echo $mdl_common->fnc_make_scripture_title($reading->book_id, $reading->begin_chapter, $reading->begin_verse, $reading->end_chapter, $reading->end_verse, $item->flg_long_text );
							echo '</a><br> ';					
						}
					}
					if($item->int_day_diff_remain >= 0)
					{
						foreach($item->arr_reading_2 as $reading_2)
						{
							if($reading_2->day_number == ($x - $item->int_day_diff_remain))
							{
								$url = JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_primary_reading ."&bible=".$item->str_primary_bible."&day=".$reading_2->day_number);
								echo "<a title='"."' id='zef_links' href='".$url."'>";
								echo $mdl_common->fnc_make_scripture_title($reading_2->book_id, $reading_2->begin_chapter, $reading_2->begin_verse, $reading_2->end_chapter, $reading_2->end_verse, $item->flg_long_text );
								echo '</a><br> ';					
							} 
						}					
					}
				echo '	</div>';
				echo '	<div style="clear:both"></div>';
				echo '</div>';
				echo '<div style="clear:both"></div>';				
		}
?>

