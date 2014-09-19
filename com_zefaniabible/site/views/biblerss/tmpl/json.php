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
JHTML::_('behavior.modal');
$cls_BibleJSON = new BibleJSON($this->item); 

class BibleJSON {

	public function __construct($item)
	{	
		switch($item->flg_type)
		{
			case 1:
				$this->fnc_bibleorg_type($item);
				break;
			default:
				$this->fnc_getBible_type($item);
				break;
		}		
	}
	private function fnc_bibleorg_type($item)
	{
		echo '['.PHP_EOL;
		foreach($item->arr_Chapter as $obj_chapter)
		{			
			echo '{'.PHP_EOL;
			echo '	"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).'",'.PHP_EOL;
			echo '	"chapter":"'.$item->int_Bible_Chapter.'",'.PHP_EOL;	
			echo '	"verse":"'.$obj_chapter->verse_id.'",'.PHP_EOL;
			echo '	"text":"'.strip_tags($obj_chapter->verse).'"'.PHP_EOL;
			if($obj_chapter->verse_id >= count($item->arr_Chapter))
			{
				echo '}'.PHP_EOL;
			}
			else
			{
				echo '},'.PHP_EOL;
			}
		}
		echo ']'.PHP_EOL;				
	}
	private function fnc_getBible_type($item)
	{
		echo '[{'.PHP_EOL;
		echo '"type":"chapter",'.PHP_EOL;
		echo '"book_name":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).'",'.PHP_EOL;
		echo '"book_nr":'.$item->int_Bible_Book_ID.','.PHP_EOL;			
		echo '"chapter_nr":"'.$item->int_Bible_Chapter.'",'.PHP_EOL;	
		echo '"chapter":{'.PHP_EOL;

		foreach($item->arr_Chapter as $obj_chapter)
		{			
			echo '"'.$obj_chapter->verse_id.'":'.PHP_EOL;
			echo '{'.PHP_EOL;
			echo '	"verse_nr":'.$obj_chapter->verse_id.','.PHP_EOL;
			echo '	"verse":"'.strip_tags($obj_chapter->verse).'"'.PHP_EOL;
			if($obj_chapter->verse_id >= count($item->arr_Chapter))
			{
				echo '}'.PHP_EOL;
			}
			else
			{
				echo '},'.PHP_EOL;
			}
		}
		echo '}}]'.PHP_EOL;			
	}
}
?>