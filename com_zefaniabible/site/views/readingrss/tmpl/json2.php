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

class BibleReadingPlanJSON
{
		/*
			a = plan
			b = bible
			c = day
		*/

	public function __construct($item)
	{
		echo '[{'.PHP_EOL;
		$x=1;
		echo '	"type":"reading",'.PHP_EOL;
		echo '	"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;
		echo '	"planname":"'.$item->str_reading_plan_name.'",'.PHP_EOL;
		echo '	"plandesc":"'.$item->str_description.'",'.PHP_EOL;
		echo '	"biblealias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
		echo '	"planalias":"'.$item->str_reading_plan.'",'.PHP_EOL;
		echo '	"maxdays":"'.$item->int_max_days.'",'.PHP_EOL;		
		echo '	"day":"'.$item->int_day_number.'",'.PHP_EOL;	
				
		echo '	"bookname":{'.PHP_EOL;		
		foreach($item->arr_plan as $obj_chapter)
		{			
			$y = 1;
			$flg_prev_chap = 0;
			$flg_last_verse = 0;
			foreach ($obj_chapter as $obj_verse)
			{	
				if($y != 1)
				{
					if($flg_prev_chap != $obj_verse->chapter_id)
					{
						echo '					}'.PHP_EOL;
						echo '				}'.PHP_EOL;
					}
					else
					{
						echo '					},'.PHP_EOL;
					}
				}
				$flg_chapt_change = 0;
				if($y == 1)
				{
					echo '	"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id).'":{'.PHP_EOL;	
					echo '		"chapter":{'.PHP_EOL;	
				}
				if($flg_prev_chap != $obj_verse->chapter_id)
				{
					if($flg_prev_chap != 0)
					{
						echo '			},'.PHP_EOL;
					}
					echo '			"'.$obj_verse->chapter_id.'":{'.PHP_EOL;
					echo '				"verse":{'.PHP_EOL;
					$flg_prev_chap = $obj_verse->chapter_id;
				}
				echo '					"'.$obj_verse->verse_id.'":'.PHP_EOL;			
				echo '					{'.PHP_EOL;
				echo '						"booknameenglish:":"'.$item->arr_english_book_names[$obj_verse->book_id].'",'.PHP_EOL;	
				echo '						"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id).'",'.PHP_EOL;
				echo '						"chapter":"'.$obj_verse->chapter_id.'",'.PHP_EOL;	
				echo '						"verse":"'.$obj_verse->verse_id.'",'.PHP_EOL;
				echo '						"text":"'.strip_tags($obj_verse->verse).'"'.PHP_EOL;
				
				$y++;
			}

			if($x >= count($item->arr_plan))
			{
				echo '}'.PHP_EOL;
				// used to add extra closing brackets for plans that are more than one book.
				if(count($item->arr_plan) > 1)
				{
					echo "}}}}";
				}				
			}
			else
			{
				echo '},'.PHP_EOL;
			}

			$x++;			
		}
		echo '}}}}}}]'.PHP_EOL;			
	}	
}
?>