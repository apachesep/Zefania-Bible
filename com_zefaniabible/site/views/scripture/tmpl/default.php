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
$cls_bible_reading_plan = new BibleReadingPlan($this->arr_verses, $this->str_Bible_Version, $this->str_Bible_book_id, $this->str_begin_chap, $this->str_begin_verse, $this->str_end_chap, $this->str_end_verse, $this->flg_add_title);
class BibleReadingPlan
{
	private $arr_Bible_book_id;
	private $str_begin_chap;
	private $str_begin_verse;
	private $str_end_verse;
	private $str_end_chap;
	private $str_Bible_Path;
	private $str_full_name;
	private $arr_Bible_book_name;
	private $flg_show_credit;
	public function __construct($arr_verses, $str_Bible_Version, $str_Bible_book_id, $str_begin_chap, $str_begin_verse, $str_end_chap, $str_end_verse, $flg_add_title)
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
		$this->flg_show_credit = $this->params->get('show_credit','0');
		$str_scripture = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title);
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		echo '<div class="zef_scripture_image_div"><img class="zef_scripture_image" src="/components/com_zefaniabible/images/scripture.jpg" width="640" height="90"></div>';
		echo '<div class="zef_scripture_modal">'.$str_scripture."</div>"; 
		if($this->flg_show_credit)
		{
			require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			$obj_player_one = $mdl_credits->fnc_credits();
		}
    }

	protected function fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title )
	{
		$this->flg_show_credit = $this->params->get('show_credit','0');
		$verse = '';
		$x = 1;
		$int_verse_cnt = count($arr_verses);
		foreach($arr_verses as $obj_verses)
		{	
			// Genesis 1:1
			if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse))
			{
				$verse = 		'<div class="zef_content_scripture">';
				$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse;
				if($flg_add_title)
				{
					$verse = $verse.' - '.$obj_verses->bible_name;
				}
				$verse = $verse.	'</div><div class="zef_content_verse"><div class="odd">'.$obj_verses->verse.'</div></div>';
				$verse = $verse.'</div>';			
			}
			// Genesis 1:1-3
			else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse))
			{
				if($obj_verses->verse_id == $str_begin_verse)
				{
					$verse = $verse.'<div class="zef_content_scripture">';
					$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_verse;
					if($flg_add_title)
					{
						$verse = $verse.' - '.$obj_verses->bible_name;
					}
					$verse = $verse.	'</div><div class="zef_content_verse" >';
				}
				if ($x % 2 )
				{
					$verse = $verse.'<div class="odd">';
				}
				else
				{
					$verse = $verse.'<div class="even">';
				}
				$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
				$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
				$verse = $verse.	'<div style="clear:both"></div>';
				$verse = $verse.'</div>';
				if($x == $int_verse_cnt)
				{
					$verse = $verse.'</div></div>';
				}
				$x++;				
			}
			// Genesis 1
			else if(($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
			{
				if($obj_verses->verse_id == '1')
				{
					$verse = $verse.'<div class="zef_content_scripture">';
					$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap;
					if($flg_add_title)
					{
						$verse = $verse.' - '.$obj_verses->bible_name;
					}
					$verse = $verse.	'</div><div class="zef_content_verse" >';
				}
				if ($x % 2 )
				{
					$verse = $verse.'<div class="odd">';
				}
				else
				{
					$verse = $verse.'<div class="even">';
				}
				$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
				$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
				$verse = $verse.	'<div style="clear:both"></div>';
				$verse = $verse.'</div>';
				if($x == $int_verse_cnt)
				{	
					$verse = $verse.'</div></div>';
				}
				$x++;				
			}
			// Genesis 1-2
			else if(($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
			{
				if(($obj_verses->verse_id == '1')and($str_begin_chap == $obj_verses->chapter_id))
				{
					$verse = $verse.'<div class="zef_content_scripture">';
					$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.'-'.$str_end_chap;
					if($flg_add_title)
					{
						$verse = $verse.' - '.$obj_verses->bible_name;
					}
					$verse = $verse.	'</div><div class="zef_content_verse" >';
				}		
				if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
				{
					$verse = $verse. '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
				}
				if ($x % 2 )
				{
					$verse = $verse.'<div class="odd">';
				}
				else
				{
					$verse = $verse.'<div class="even">';
				}
				$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
				$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
				$verse = $verse.	'<div style="clear:both"></div>';
				$verse = $verse.'</div>';		
				if($x == $int_verse_cnt)
				{	
					$verse = $verse.'</div></div>';	
				}	
				$x++;				
			}
			// Genesis 1:30-2:3
			else if(($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse))
			{
				if(($obj_verses->verse_id == $str_begin_verse)and($str_begin_chap == $obj_verses->chapter_id))
				{
					$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_chap.':'.$str_end_verse;
					if($flg_add_title)
					{
						$title = $title.' - '.$obj_verses->bible_name;
					}
					$verse = $verse.'<div class="zef_content_scripture">';
					$verse = $verse.	'<div class="zef_content_title">'.$title.'</div>';
					$verse = $verse.	'<div class="zef_content_verse" >';							
				}
				if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
				{
					$verse = $verse. '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
				}							
				if ($x % 2 )
				{
					$verse = $verse.'<div class="odd">';
				}
				else
				{
					$verse = $verse.'<div class="even">';
				}		
				$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
				$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
				$verse = $verse.	'<div style="clear:both"></div>';
				$verse = $verse.'</div>';	
				if($x == $int_verse_cnt)
				{	
					$verse = $verse.'</div></div>';			
				}
				$x++;
			}
		}
		return $verse;
	}
}

?>