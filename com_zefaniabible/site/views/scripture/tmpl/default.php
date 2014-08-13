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
$cls_bible_reading_plan = new BibleReadingPlan($this->item);
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
		$str_scripture = $this->fnc_create_text_link($item->arr_verses, $item->int_Bible_Book_ID, $item->str_begin_chap, $item->str_end_chap, $item->str_begin_verse, $item->str_end_verse, $item->flg_add_title);
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		echo '<div class="zef_scripture_image"><img src="'.$item->str_default_image.'"></div>';
		echo '<div class="clear:both"></div>';
		echo '<div class="zef_scripture_modal">'.$str_scripture."</div>"; 
		if($item->flg_show_credit)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			$obj_player_one = $mdl_credits->fnc_credits();
		}
    }

	protected function fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title )
	{
		$verse = '';
		$x = 1;
		$int_verse_cnt = count($arr_verses);
		foreach($arr_verses as $obj_verses)
		{	
			switch (true)
			{
				// Multi verse
				case strpos($str_begin_verse, ','):
					if($x==1)
					{
						$verse .= '<div class="zef_content_scripture">';
						$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse;
						if($flg_add_title)
						{
							$verse .= ' - '.$obj_verses->bible_name;
						}
						$verse .= 	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{
						$verse .= '</div></div>';
					}
					$x++;				
					break;
				// Genesis 1:1
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse)):
					$verse = 		'<div class="zef_content_scripture">';
					$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse;
					if($flg_add_title)
					{
						$verse .= ' - '.$obj_verses->bible_name;
					}
					$verse .= 	'</div><div class="zef_content_verse"><div class="odd">'.$obj_verses->verse.'</div></div>';
					$verse .= '</div>';
					break;
				// Genesis 1:1-3
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse)):
					if($obj_verses->verse_id == $str_begin_verse)
					{
						$verse .= '<div class="zef_content_scripture">';
						$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_verse;
						if($flg_add_title)
						{
							$verse .= ' - '.$obj_verses->bible_name;
						}
						$verse .= 	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{
						$verse .= '</div></div>';
					}
					$x++;				
					break;
				// Genesis 1
				case (($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)):
					if($obj_verses->verse_id == '1')
					{
						$verse .= '<div class="zef_content_scripture">';
						$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap;
						if($flg_add_title)
						{
							$verse .= ' - '.$obj_verses->bible_name;
						}
						$verse .= 	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';
					}
					$x++;				
					break;
				// Genesis 1-2
				case (($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)):
					if(($obj_verses->verse_id == '1')and($str_begin_chap == $obj_verses->chapter_id))
					{
						$verse .= '<div class="zef_content_scripture">';
						$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.'-'.$str_end_chap;
						if($flg_add_title)
						{
							$verse .= ' - '.$obj_verses->bible_name;
						}
						$verse .= 	'</div><div class="zef_content_verse" >';
					}		
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';		
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';	
					}	
					$x++;				
					break;
				// Genesis 1:30-2:3
				case (($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse)):
					if(($obj_verses->verse_id == $str_begin_verse)and($str_begin_chap == $obj_verses->chapter_id))
					{
						$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_chap.':'.$str_end_verse;
						if($flg_add_title)
						{
							$title = $title.' - '.$obj_verses->bible_name;
						}
						$verse .= '<div class="zef_content_scripture">';
						$verse .= 	'<div class="zef_content_title">'.$title.'</div>';
						$verse .= 	'<div class="zef_content_verse" >';							
					}
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
					}							
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}		
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';	
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';			
					}
					$x++;				
					break;
				default:
					break;
			}
		}
		return $verse;
	}
}

?>
<?php 
	if($this->item->flg_enable_debug == 1)
	{
		echo '<!--';
		print_r($this->item);
		echo '-->';
	}
?>