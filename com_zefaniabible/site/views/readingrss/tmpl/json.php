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

$cls_bible_reading_plan = new BibleReadingPlan($this->item);

class BibleReadingPlan
{
		/*
			a = plan
			b = bible
			c = day
		*/

	public function __construct($item)
	{
		switch($item->flg_redirect_request)
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
		$y = 1;
		
		foreach($item->arr_plan as $obj_chapter)
		{			
			$x = 1;		
			foreach ($obj_chapter as $obj_verse)
			{		
				echo '	{'.PHP_EOL;
				echo '		"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id).'",'.PHP_EOL;
				echo '		"chapter":"'.$obj_verse->chapter_id.'",'.PHP_EOL;	
				echo '		"verse":"'.$obj_verse->verse_id.'",'.PHP_EOL;
				echo '		"text":"'.strip_tags($obj_verse->verse).'"'.PHP_EOL;
				if(($x >= count($obj_chapter))and($y >= count($item->arr_plan)))
				{
					echo '	}'.PHP_EOL;
				}
				else
				{
					echo '	},'.PHP_EOL;
				}
				$x++;
			}
			$y++;
		}
		echo ']'.PHP_EOL;				
	}
	private function fnc_getBible_type($item)
	{
		echo '['.PHP_EOL;
		$x=1;
		echo '{"bookname":{'.PHP_EOL;		
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
				echo '						"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$obj_verse->book_id).'",'.PHP_EOL;
				echo '						"chapter":"'.$obj_verse->chapter_id.'",'.PHP_EOL;	
				echo '						"verse":"'.$obj_verse->verse_id.'",'.PHP_EOL;
				echo '						"text":"'.strip_tags($obj_verse->verse).'"'.PHP_EOL;
				
				$y++;
			}

			if($x >= count($item->arr_plan))
			{
				echo '}'.PHP_EOL;
			}
			else
			{
				echo '},'.PHP_EOL;
			}
			$x++;			
		}
		echo '}}}}}}}}}}]'.PHP_EOL;			
	}	
}
?>