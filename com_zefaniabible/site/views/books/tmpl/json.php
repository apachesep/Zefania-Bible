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

class BibleBooksJSON 
{
	public function __construct($item)
	{	
		echo '['.PHP_EOL;
		echo '	{'.PHP_EOL;
		echo '		"type":"bible",'.PHP_EOL;
		echo '		"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;
		echo '		"alias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
		echo '		"maxbooks":"66",'.PHP_EOL;
		echo '		"book":{'.PHP_EOL;
		for($x = 1; $x <= 66; $x++)
		{
			echo '			"'.$x.'":{'.PHP_EOL;
			echo '				"booknameenglish":"'.$item->arr_english_book_names[$x].'",'.PHP_EOL;
			echo '				"book_name":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'",'.PHP_EOL;
			echo '				"book_nr":"'.$x.'",'.PHP_EOL;
			echo '				"maxchapter":"'.$item->arr_max_chapters[($x-1)]->max_chapter.'"'.PHP_EOL;
			if($x == 66)
			{
				echo '			}'.PHP_EOL;
			}
			else
			{
				echo '			},'.PHP_EOL;
			}
		}
		echo '		}'.PHP_EOL;
		echo '	}'.PHP_EOL;
		echo ']'.PHP_EOL;
	}
}
?>

