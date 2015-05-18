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
class BibleReadingPlan
{
	public function __construct($item)
	{
		/*
			a = Alias
			b = Bible Book ID
			c = Begin Chapter
			d = Begin Verse
			e = End Chapter
			f = End Verse
		*/	
    	$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		echo '[{'.PHP_EOL;
		echo '	"type":"scripture",'.PHP_EOL;
		echo '	"biblealias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
		echo '	"bookname":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).'",'.PHP_EOL;
		echo '	"scripturename":"'.$item->scripture_title.'",'.PHP_EOL;
		echo '	"scripturenameshort":"'.$item->scripture_title_short.'",'.PHP_EOL;
		echo '	"booknameenglish":"'.$item->arr_english_book_names[$item->int_Bible_Book_ID].'",'.PHP_EOL;			
		echo '	"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;
		echo '	"bookid":'.$item->int_Bible_Book_ID.','.PHP_EOL;
		echo '	"beginchap":'.$item->str_begin_chap.','.PHP_EOL;
		echo '	"beginverse":'.$item->str_begin_verse.','.PHP_EOL;	
		echo '	"endchap":'.$item->str_end_chap.','.PHP_EOL;
		echo '	"endverse":'.$item->str_end_verse.','.PHP_EOL;	
		echo '	"scripture":['.PHP_EOL;
		$x = 1;
		$verse_cnt = count($item->arr_verses);
		foreach($item->arr_verses as $verses)
		{

			echo '		{'.PHP_EOL;	
			echo '			"bookid":'.$verses->book_id.','.PHP_EOL;
			echo '			"chapterid":'.$verses->chapter_id.','.PHP_EOL;
			echo '			"verseid":'.$verses->verse_id.','.PHP_EOL;	
							$verses->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','', $verses->verse); // remove strong numbers			
			echo '			"verse":"'.str_replace("\n","",htmlspecialchars($verses->verse)).'"'.PHP_EOL;
			if($x >= $verse_cnt){
				echo '		}'.PHP_EOL;
			}else{		
				echo '		},'.PHP_EOL;
			}
			$x++;
		}
		echo '	]'.PHP_EOL;
		echo '}]'.PHP_EOL;
    }
}

?>