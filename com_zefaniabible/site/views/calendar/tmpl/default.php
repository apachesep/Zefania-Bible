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
defined('_JEXEC') or die('Restricted access');

$cls_bible_calendar = new CalendarViewDefault($this->item);

class CalendarViewDefault
{
	public function __construct($item)
	{
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$str_redirect_url = "index.php?option=com_zefaniabible&view=calendar&bible=".$item->str_Bible_Version."&plan=".$item->str_reading_plan;
		$int_year_prev = $item->int_year;
		$int_month_prev = $item->int_month - 1;
		$int_year_next = $item->int_year;
		$int_month_next = $item->int_month + 1;
		if($item->int_month == 12)
		{
			$int_year_next = $item->int_year+1;
			$int_month_next =  1;
		}
		if($item->int_month == 1)
		{
			$int_year_prev = $item->int_year-1;
			$int_month_prev = 12;			
		}
		//print_r($item);
		$str_redirect_prev_url = JRoute::_($str_redirect_url. "&year=".$int_year_prev."&month=".$int_month_prev);
		$str_redirect_next_url = JRoute::_($str_redirect_url. "&year=".$int_year_next."&month=".$int_month_next);		

		$y= 1;
		
		echo '<form action="'. JFactory::getURI()->toString().'" method="post" id="adminForm" name="adminForm">'.PHP_EOL;
		echo '<div class="zef_legend">'.PHP_EOL;
		echo '	<div class="zef_reading_label">'.JText::_('ZEFANIABIBLE_READING_PLAN').'</div>'.PHP_EOL;
		echo '	<div class="zef_reading_plan">'.PHP_EOL;
		echo '		<select name="plan" id="reading" class="inputbox" onchange="this.form.submit()">'.PHP_EOL;
		echo 		$item->obj_reading_plan_dropdown;
		echo '		</select>'.PHP_EOL;
		echo '	</div>'.PHP_EOL;
		echo '	<div style="clear:both"></div>  '.PHP_EOL;
		if($item->flg_use_bible_selection)
		{
			echo '	<div class="zef_bible_label">'. JText::_('ZEFANIABIBLE_BIBLE_VERSION').'</div>'.PHP_EOL;
			echo '	<div class="zef_bible">'.PHP_EOL;
			echo '		<select name="bible" id="bible" class="inputbox" onchange="this.form.submit()">'.PHP_EOL;
			echo		$item->obj_bible_Bible_dropdown;
			echo '		</select>'.PHP_EOL;
			echo '	</div>'.PHP_EOL;
			echo '	<div style="clear:both"></div>'.PHP_EOL;
		}else{
			echo '<input type="hidden" name="bible" value="'.$item->str_Bible_Version.'" />';
		}
		echo '</div>'.PHP_EOL;
		
		echo '<div class="zef_calendar">'.PHP_EOL;
		echo '	<div class="zef_calendar_month_header">'.PHP_EOL;
		if($item->flg_show_page_top)
		{
			if($item->flg_show_pagination_type == 1)
			{
				echo '		<div class="zef_calendar_month_prev"><a href="'.$str_redirect_prev_url.'">'.$mdl_common->fnc_get_month_name($int_month_prev).'</a></div>'.PHP_EOL;
				echo '		<div class="zef_calendar_month_name">'.$mdl_common->fnc_get_month_name($item->int_month).' '.$item->int_year.'</div>'.PHP_EOL;
				echo '		<div class="zef_calendar_month_next"><a href="'.$str_redirect_next_url.'">'.$mdl_common->fnc_get_month_name($int_month_next).'</a></div>'.PHP_EOL;				
			}
			else
			{
				$urlPrepend = "document.location.href=('";
				$urlPostpend = "')";				
				echo '		<div class="zef_calendar_month_prev"><input title="'.$mdl_common->fnc_get_month_name($int_month_prev).'" type="button" id="zef_Buttons" class="zef_lastChapter" name="lastChapter" onclick="'.$urlPrepend.$str_redirect_prev_url.$urlPostpend.'"  value="'. $mdl_common->fnc_get_month_name($int_month_prev).'" /></div>';
				echo '		<div class="zef_calendar_month_name">'.$mdl_common->fnc_get_month_name($item->int_month).' '.$item->int_year.'</div>'.PHP_EOL;
				echo '		<div class="zef_calendar_month_next"><input title="'.$mdl_common->fnc_get_month_name($int_month_next).'" type="button" id="zef_Buttons" class="zef_lastChapter" name="lastChapter" onclick="'.$urlPrepend.$str_redirect_next_url.$urlPostpend.'"  value="'. $mdl_common->fnc_get_month_name($int_month_next).'" /></div>';
				
			}
		}else{
			echo '		<div class="zef_calendar_month_prev"></div>'.PHP_EOL;			
			echo '		<div class="zef_calendar_month_name">'.$mdl_common->fnc_get_month_name($item->int_month).' '.$item->int_year.'</div>'.PHP_EOL;
			echo '		<div class="zef_calendar_month_next"></div>'.PHP_EOL;
		}
		echo '		<div style="clear:both"></div>'.PHP_EOL;	
		echo '	</div>'.PHP_EOL;
		
		
		for($o = 0; $o < 7; $o++)
		{
			echo '	<div class="zef_calendar_day_names">'.$mdl_common->fnc_get_day_name($o).'</div>'.PHP_EOL;
		}
		echo '	<div style="clear:both"></div>'.PHP_EOL;			

		$int_total_days = $item->int_month_begin_weekday + $item->int_month_max_days;
		$int_calendar_rows = ceil($int_total_days/7);
		echo '	<div class="zef_calendar_graph">'.PHP_EOL;
		for($z = 0; $z < $int_calendar_rows; $z++)
		{
			echo '		<div class="zef_calendar_week">'.PHP_EOL;			
			for($x = 0; $x < 7; $x++)
			{
				if(((($z == 0)and($x >= $item->int_month_begin_weekday))or($z > 0))and($y <= $item->int_month_max_days))
				{
					$t=1;
					echo '			<div class="zef_calendar_day';
					// add style sheet for saturday's
					if($x ==6)
					{
						echo ' zef_calendar_sat';	
					}
					// add style sheet for last week in the month
					if($z == $int_calendar_rows -1 )
					{
						echo ' zef_calendar_last_row';
					}
					// add style sheet for today's date
					if(($y == $item->int_current_day)and($item->int_current_month == $item->int_month)and($item->int_current_year == $item->int_year))
					{
						echo ' zef_calendar_today';
					}
					echo '" style="border-color:'.$item->str_calendar_border_color.';';
					// add background color for today's date
					if(($y == $item->int_current_day)and($item->int_current_month == $item->int_month)and($item->int_current_year == $item->int_year))
					{
						echo 'background-color:'.$item->str_calendar_today_color.';';
					}					
					echo '">'.$y.'<br><div class="zef_calendar_reading">';
					foreach($item->arr_reading as $arr_reading)
					{
						if($arr_reading->day_number == ($item->int_day_diff+$y))
						{
							echo '<div class="zef_calendar_link_div" style="border-color:'.$item->str_calendar_border_color.'"><a class="zef_calendar_link" style="background-color:'.$item->str_calendar_link_color.'; border-color:'.$item->str_calendar_border_color.';color:'.$item->str_calendar_link_text_color.'" title="'.JText::_('ZEFANIABIBLE_VERSE_READING_PLAN_OVERVIEW_CLICK_TITLE').'" href="'.JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$arr_reading->day_number.'&Itemid='.$item->str_view_plan).'#'.$t.'" target="_self">';
							echo $mdl_common->fnc_make_scripture_title(
									$arr_reading->book_id,  
									$arr_reading->begin_chapter, 
									$arr_reading->begin_verse, 
									$arr_reading->end_chapter, 
									$arr_reading->end_verse, 1 );
							echo '</a></div>';
							$t++;
						}
					}
					if(($item->int_day_diff + $y) > $item->int_max_days )
					{
						$int_new_reading_days = ($item->int_day_diff + $item->int_month_max_days) - $item->int_max_days;
						foreach($item->arr_reading2 as $arr_reading)
						{
							if($arr_reading->day_number == $y - ($item->int_month_max_days - $int_new_reading_days))
							{
								echo '<div class="zef_calendar_link_div" style="border-color:'.$item->str_calendar_border_color.'"><a class="zef_calendar_link" style="background-color:'.$item->str_calendar_link_color.'; border-color:'.$item->str_calendar_border_color.';color:'.$item->str_calendar_link_text_color.'" title="'.JText::_('ZEFANIABIBLE_VERSE_READING_PLAN_OVERVIEW_CLICK_TITLE').'" href="'.JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$arr_reading->day_number.'&Itemid='.$item->str_view_plan).'#'.$t.'" target="_self">';
								echo $mdl_common->fnc_make_scripture_title(
										$arr_reading->book_id,  
										$arr_reading->begin_chapter, 
										$arr_reading->begin_verse, 
										$arr_reading->end_chapter, 
										$arr_reading->end_verse, 1 );
								echo '</a></div>';
								$t++;
							}						
						}
					}
					echo '</div></div>'.PHP_EOL;
					$y++;
				}
				else
				{
					echo '			<div class="zef_calendar_day_empty';
					if($x ==6)
					{
						echo ' zef_calendar_sat';	
					}
					if($z == $int_calendar_rows -1 )
					{
						echo ' zef_calendar_last_row';
					}
					echo '" style="background-color:'.$item->str_calendar_emptyday_color.'; border-color:'.$item->str_calendar_border_color.'"></div>'.PHP_EOL;	
				}
				
			}
			echo '		</div>'.PHP_EOL;
			echo '		<div style="clear:both"></div>'.PHP_EOL;
		}
		echo '	</div>'.PHP_EOL;
		echo '</div>'.PHP_EOL;
		echo '<input type="hidden" name="option" value="'. $item->str_option.'" />'.PHP_EOL;
		echo '<input type="hidden" name="view" value="'. $item->str_view.'" />'.PHP_EOL;
	    echo '<input type="hidden" name="Itemid" value="'. $item->int_menu_item_id.'"/>'.PHP_EOL;
	    echo '<input type="hidden" name="month" value="'. $item->int_month.'"/>'.PHP_EOL;
		echo '<input type="hidden" name="year" value="'. $item->int_year.'"/>'.PHP_EOL;
	echo '</form>'.PHP_EOL;
	}
	
}
?>       
                <div class="zef_footer">
                    <div class="zef_bot_pagination">
                        <?php if(($this->item->flg_show_credit)or($this->item->int_menu_item_id == 0 ))
                        { 
                            require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
                            $mdl_credits = new ZefaniabibleCredits;
                            $obj_player_one = $mdl_credits->fnc_credits();
                        } ?>      
                    </div>  
                </div>
                        
        <?php 
            if($this->item->flg_enable_debug == 1)
            {
                echo '<!--';
                print_r($item);
                echo '-->';
            }
        ?>  