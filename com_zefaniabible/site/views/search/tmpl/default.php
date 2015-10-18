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
$cls_zefania_search = new ZefaniaSearch($this->item);

class ZefaniaSearch
{
	public function __construct($item)
	{	
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');			
		$mdl_common 	= new ZefaniabibleCommonHelper;
		$x = 0;
		$result_count = count($item->arr_search_result);
		if((($item->flg_use_api)and(!$item->flg_use_key)) or (($item->flg_use_api)and($item->flg_use_key)and($item->str_user_api_key == $item->str_api_key)))
		{
			echo '{'.PHP_EOL;
			echo '	"type":"search",'.PHP_EOL;
			echo '	"alias":"'.$item->str_Bible_Version.'",'.PHP_EOL;
			echo '	"biblename":"'.$item->str_bible_name.'",'.PHP_EOL;
			echo '	"query": "'.$item->query.'",'.PHP_EOL;
			echo '	"max-limit":"'.$item->int_limit_query.'",'.PHP_EOL;
			echo '	"result-count":"'.$result_count.'",'.PHP_EOL;		
			echo '	"scripture": '.PHP_EOL;
			echo '	['.PHP_EOL;		
	
			foreach($item->arr_search_result as $result)
			{
				echo '		{'.PHP_EOL;
				$item->scripture_title 			= $mdl_common->fnc_make_scripture_title($result->book_id, $result->chapter_id, $result->verse_id, 0, 0, 0 );
				$item->scripture_title_short 	= $mdl_common->fnc_make_scripture_title($result->book_id, $result->chapter_id, $result->verse_id, 0, 0, 1 );
				echo '			"scripturename":"'.$item->scripture_title.'",'.PHP_EOL;
				echo '			"scripturenameshort":"'.$item->scripture_title_short.'",'.PHP_EOL;	
				echo '			"booknameenglish":"'.$item->arr_english_book_names[$result->book_id].'",'.PHP_EOL;	
				echo '			"book_name":"'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$result->book_id).'",'.PHP_EOL;		
				echo '			"book_nr":"'.$result->book_id.'",'.PHP_EOL;			
				echo '			"chapter_nr":"'.$result->chapter_id.'",'.PHP_EOL;			
				echo '			"verse_nr":"'.$result->verse_id.'",'.PHP_EOL;
				echo '			"verse": "'.htmlspecialchars(strip_tags($result->verse)).'"'.PHP_EOL;
				$x++;
				if($x >= $result_count)
				{
					echo '		}'.PHP_EOL;
				}
				else 
				{
					echo '		},'.PHP_EOL;
				}
			}
			echo '	]'.PHP_EOL;	
			echo '}'.PHP_EOL;	
		} else {
			$mdl_common->fnc_not_auth();
		}
	}
}
?>
