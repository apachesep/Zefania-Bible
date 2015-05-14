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
class ClsVerseJSON
{
	public function __construct($item)
	{
		echo '[{'.PHP_EOL;		
		foreach($item->arr_verse_info as $obj_arr_verse_info)
		{
			echo '	"type":"verseofday",'.PHP_EOL;
			echo '	"day":"'.$item->int_day_number.'",'.PHP_EOL;
			echo '	"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;			
			echo '	"alias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
			echo '	"maxdays":"'.$item->int_max_days.'",'.PHP_EOL;
			echo '	"booknameenglish":"'.$item->arr_english_book_names[$obj_arr_verse_info->book_name].'",'.PHP_EOL;		
			
			echo '	"book_name":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_arr_verse_info->book_name).'",'.PHP_EOL;
			echo '	"book_nr":"'.$obj_arr_verse_info->book_name.'",'.PHP_EOL;			
			echo '	"chapter_nr":"'.$obj_arr_verse_info->chapter_number.'",'.PHP_EOL;
			echo '	"chapter":{'.PHP_EOL;
			$x = $obj_arr_verse_info->begin_verse;
			foreach($item->arr_verse_of_day as $obj_arr_verse_of_day)
			{
				echo '			"'.$x.'":'.PHP_EOL;
				echo '			{'.PHP_EOL;
				echo '				"verse_nr":"'.$x.'",'.PHP_EOL;
				echo '				"verse":"'.htmlspecialchars(strip_tags($obj_arr_verse_of_day->verse)).'"'.PHP_EOL;
				if($x >= $obj_arr_verse_info->end_verse)
				{
					echo '			}'.PHP_EOL;
				}
				else
				{
					echo '			},'.PHP_EOL;
				}
				$x++;
			}
		echo '		}'.PHP_EOL;			
		}
		echo '}]'.PHP_EOL;			
	}
}
?>