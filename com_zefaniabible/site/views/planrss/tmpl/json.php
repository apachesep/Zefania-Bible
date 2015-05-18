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
class PlanJSON
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
		$x= 1;
		echo '['.PHP_EOL;
		foreach($item->arr_reading as $obj_reading)
		{			
			echo '{'.PHP_EOL;
			echo '	"type":"plan",'.PHP_EOL;
			echo '	"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;
			echo '	"planname":"'.$item->str_reading_plan_name.'",'.PHP_EOL;
			echo '	"plandesc":"'.$item->str_description.'",'.PHP_EOL;
			echo '	"biblealias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
			echo '	"planalias":"'.$item->str_reading_plan.'",'.PHP_EOL;
			echo '	"maxdays":"'.$item->int_max_days.'",'.PHP_EOL;					
			echo '	"day":"'.$obj_reading->day_number.'",'.PHP_EOL;	
			echo '	"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_reading->book_id).'",'.PHP_EOL;
			echo '	"booknameenglish":"'.$item->arr_english_book_names[$obj_reading->book_id].'",'.PHP_EOL;	
			echo '	"chapter_begin":"'.$obj_reading->begin_chapter.'",'.PHP_EOL;	
			echo '	"chapter_end":"'.$obj_reading->end_chapter.'",'.PHP_EOL;
			echo '	"verse_begin":"'.$obj_reading->begin_verse.'",'.PHP_EOL;
			echo '	"end_verse":"'.$obj_reading->end_verse.'"'.PHP_EOL;
			if( $x < count($item->arr_reading))
			{
				echo '},'.PHP_EOL;
			}
			else
			{
				echo '}'.PHP_EOL;
			}
			$x++;
		}
		echo ']'.PHP_EOL;			
	}	
}
?>