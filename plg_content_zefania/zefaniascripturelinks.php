<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Search Plugin
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

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgContentZefaniaScriptureLinks extends JPlugin
{
	private $flg_inpage_scripture;
	private $flg_auto_replace;
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		if(JRequest::getCmd('option') == "com_gcalendar")
		{
			return;	
		}	
		$document	= JFactory::getDocument();
		$docType = $document->getType();
		if($docType != 'html') return; 
		// exclude component that is broken by this pluin
	
		$this->loadLanguage();
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		JHTML::_('behavior.modal');

	}
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{ 		
		$document	= JFactory::getDocument();
		$docType = $document->getType();
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
		if(JRequest::getCmd('option') == "com_gcalendar")
		{
			return;	
		}			
		if($docType != 'html')
		{
			$str_match_fuction = "#{zefaniabible\s*(.*?)}#";
			$str_match_fuction_v2 = "#{/zefaniabible}#";
			$row->text = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, ', ', $row->text ));			
			return; 
		}	
		$this->params_zefania_comp = &JComponentHelper::getParams( 'com_zefaniabible' );
		$this->flg_inpage_scripture = $this->params->get('flg_inpage_scripture', '0');
		$this->flg_auto_replace = $this->params->get('flg_automatic_scripture_detection', '0');

		$arr_toolTipArray = array('className'=>'zefania-tip', 
			'fixed'=>true,
			'showDelay'=>'500',
			'hideDelay'=>'5000'
			);						
		JHTML::_('behavior.tooltip', '.hasTip-zefania', $arr_toolTipArray);
				
		// {zefaniabible text}##{/zefaniabible}
		$str_match_fuction = "#{zefaniabible\stext*(.*?)}(.*?){/zefaniabible}#";
		$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'), $row->text );
		
		//{zefaniabible label=''}##{/zefaniabible}
		$str_match_fuction = "#{zefaniabible\slabel=*(.*?)}(.*?){/zefaniabible}#";
		$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'), $row->text );
		
		//{zefaniabible tooltip}##{/zefaniabible}
		$str_match_fuction = "#{zefaniabible\stooltip=*(.*?)}(.*?){/zefaniabible}#";
		$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'), $row->text );
				
		if($this->flg_auto_replace)
		{
			$str_Bible_books = "";
			$str_match_fuction = "#{/zefaniabible}#";
			$str_match_fuction_v2 = "#{zefaniabible*(.*?)}#";
			$row->text = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, '', $row->text ));
			for($z = 1; $z <= 66; $z ++)
			{
				$str_Bible_books = $str_Bible_books . mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'))."|";
			}
			
			$str_match_fuction = '/(?=\S)(('.$str_Bible_books.')[\.]?[\s]?(\d{1,3})[:]?(\d{1,3})?[-]?(\d{1,3})?[:]?(\d{1,3})?)/siu';
			$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);	
			
		}
		else
		{
			$str_match_fuction = "#{zefaniabible\s*(.*?)}(.*?){/zefaniabible}#";		
			$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'), $row->text );			
		}
      	return true;
	}


	private function fnc_Make_Scripture(&$arr_matches)
	{
		$str_scripture = "";
		$str_scripture = $arr_matches[2];	
		$str_Bible_book_name = '';
		$str_new_alias = '';
		$flg_insert_text = 0;
		$flg_insert_label = 0;
		$flg_insert_tooltip = 0;
		$arr_new_alias = "";
		$str_begin_chap = '';
		$str_begin_verse = '';
		$str_end_verse = '';
		$str_end_chap = '';
		$str_proper_name = '';
		$arr_look_up_orig = '';
		$flg_add_title = 0;
		$str_Bible_alias = $this->params->get('content_Bible_alias', 'kjv');
		// text into page flag
		if(preg_match('#{zefaniabible text(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]))
		{
			$flg_insert_text = 1;
		}
		// label flag
		else if(preg_match('#{zefaniabible\slabel=*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]))
		{
			$flg_insert_label = 1;
		}
		// mouseover tooltip flag
		else if(preg_match('#{zefaniabible\stooltip*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]))
		{
			$flg_insert_tooltip = 1;
		}
				
		for($z = 1; $z <= 66; $z ++)
		{
			$arr_look_up[$z] = mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
			$arr_look_up_orig = explode('|',JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z));
			
			// Genesis 1:28-2:3
			if(preg_match('/(?=\S)((^'.$arr_look_up[$z].')[\.]?(\s)?(\d{1,3}):(\d{1,3})-(\d{1,3}):(\d{1,3}))/siU', mb_strtolower($str_scripture,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$arr_Bible_book_name = explode("|",$arr_look_up[$z]);
				$str_Bible_book_name = $arr_Bible_book_name[0];	
				$arr_scripture = explode(":",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($str_scripture,'UTF-8')));
				$str_begin_chap = $arr_scripture[0];	
				$str_end_verse = $arr_scripture[2];
				$arr_scripture = explode("-",$arr_scripture[1]);	
				$str_begin_verse = $arr_scripture[0];
				$str_end_chap = $arr_scripture[1];						
			}			
			// Genesis 1:1-3
			else if(preg_match('/(?=\S)((^'.$arr_look_up[$z].')[\.]?(\s)?(\d{1,3}):(\d{1,3})-?(\d{1,3})?)/siU', mb_strtolower($str_scripture,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$arr_Bible_book_name = explode("|",$arr_look_up[$z]);
				$str_Bible_book_name = $arr_Bible_book_name[0];	
				$arr_scripture = explode(":",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($str_scripture,'UTF-8')));
				$str_begin_chap = $arr_scripture[0];
				$arr_scripture = explode("-",$arr_scripture[1]);
				$str_begin_verse = $arr_scripture[0];
				$str_end_verse = $arr_scripture[1];
			}
			//Genesis 8:36
			else if(preg_match('/(?=\S)((^'.$arr_look_up[$z].')[\.]?(\s)?(\d{1,3}):(\d{1,3}))/siU', mb_strtolower($str_scripture,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$arr_Bible_book_name = explode("|",$arr_look_up[$z]);
				$str_Bible_book_name = $arr_Bible_book_name[0];	
				$arr_scripture = explode(":",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($str_scripture,'UTF-8')));
				$str_begin_chap = $arr_scripture[0];
				$str_begin_verse = $arr_scripture[1];
			}						
			// Genesis 1-2
			else if(preg_match('/(?=\S)((^'.$arr_look_up[$z].')[\.]?(\s)?(\d{1,3})-?(\d{1,3})?)/siU', mb_strtolower($str_scripture,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$arr_Bible_book_name = explode("|",$arr_look_up[$z]);
				$str_Bible_book_name = $arr_Bible_book_name[0];	
				$arr_scripture = explode("-",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($str_scripture,'UTF-8')));
				$str_begin_chap = $arr_scripture[0];
				$str_end_chap = $arr_scripture[1];
			}			
			// Genesis 1
			else if(preg_match('/(?=\S)((^'.$arr_look_up[$z].')[\.]?(\s)?(\d{1,3}))/siU', mb_strtolower($str_scripture,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$arr_Bible_book_name = explode("|",$arr_look_up[$z]);
				$str_Bible_book_name = $arr_Bible_book_name[0];	
				$arr_scripture = explode("-",preg_replace('/^('.$arr_look_up[$z].')[\.]?\s?/', '', mb_strtolower($str_scripture,'UTF-8')));
				$str_begin_chap = $arr_scripture[0];
			}			
			
		}
		
		if(trim($arr_matches[1]))
		{
			$str_alias = trim($arr_matches[1]);
			$flg_add_title = 1;
		}
		else
		{
			$str_alias = $str_Bible_alias;
		} 
		$arr_verses = $this->fnc_Find_Bible_Passage($str_alias, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse);
		if($flg_insert_text)
		{
			$str_scripture = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title );
		}
		else if($flg_insert_tooltip)
		{
			$str_scripture = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title );
			$str_scripture = JHTML::tooltip($str_scripture,'', '', $arr_matches[2], '', false,'hasTip-zefania');	
		}
		else if($flg_insert_label)
		{
			$arr_alias = explode("'",$arr_matches[1]);
			$str_alias = trim($arr_alias[2]);
			if($str_alias == '')
			{
				$str_alias = $str_Bible_alias;
			}
			$str_label = trim($arr_alias[1]);
			$str_scripture = $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias, $str_label);
		}
		else
		{
			$str_scripture = $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias,'' );
		}
		return $str_scripture;
	}
	protected function fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias, $str_scripture_name)
	{
		$int_BibleGateway_id  = $this->params->get('bible_gateway_version', '51');
		$int_modal_box_height  = $this->params->get('modal_box_height', '500');
		$int_modal_box_width  = $this->params->get('modal_box_width', '700'); 
		$flg_link_use  = $this->params->get('flg_link_use', '0');
			// 0 = Zefania
			// 1 = BibleGateway
		$verse = '';			
		$str_link = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id)." ".$str_begin_chap; 
		if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse))
		{
			$str_link = $str_link.":".$str_begin_verse;
		}
		else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse))
		{
			$str_link = $str_link.":".$str_begin_verse."-".$str_end_verse;
		}
		elseif(($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
		{
			$str_link = $str_link."-".$str_end_chap;
		}
		else if(($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse))
		{
			$str_link = $str_link.":".$str_begin_verse."-".$str_end_chap.":".$str_end_verse;
		}
		
		if($flg_link_use == 0)
		{
			// Modal box coding begins here.
			if($str_begin_verse == "")
			{
				$str_begin_verse = 0;
			}
			if($str_end_chap == "")
			{
				$str_end_chap = 0 ;
			}
			if($str_end_verse == '')
			{
				$str_end_verse = 0;
			}			
			$temp = 'a='.$str_alias.'&b='.$str_Bible_book_id.'&c='.$str_begin_chap.'&d='.$str_begin_verse.'&e='.$str_end_chap.'&f='.$str_end_verse;
			$str_pre_link = '<a title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link.'" target="blank" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$int_modal_box_width.',y:'.$int_modal_box_height.'}}">';
			if($str_scripture_name)
			{
				$str_link = $str_scripture_name;
			}			
			$verse = $str_pre_link.$str_link .'</a>';
			// modal bocx coding ends here			
		}
		else
		{
			// Bible gateway coding begins here
			$str_link_url = 'http://classic.biblegateway.com/passage/index.php?search='.$str_link.';&version='.$int_BibleGateway_id.';&interface=print';			
			$str_pre_link = '<a title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link.'" target="blank" href="'.$str_link_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$int_modal_box_width.',y:'.$int_modal_box_height.'}}">';
			if($str_scripture_name)
			{
				$str_link = $str_scripture_name;
			}
			$verse = $str_pre_link.$str_link .'</a>';
			// Bible gateway coding ends here			
		}
		return $verse;
	}
	// used for creating html to insert directly into page
	protected function fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title )
	{
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
	// sql calls to get Bible passage info
	protected function fnc_Find_Bible_Passage($str_alias, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$query	= "SELECT a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name FROM `#__zefaniabible_bible_text` AS a".
				' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id'.	
				" WHERE a.book_id=".(int)$str_Bible_book_id;
				$query	= $query . " AND b.alias='".trim($str_alias)."'";
				// Genesis 1
				if(($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1-2
				else if(($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id>=".(int)$str_begin_chap." AND a.chapter_id<=".(int)$str_end_chap;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:1
				else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id=".(int)$str_begin_verse;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:1-2
				else if(($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse))
				{
					$query	= $query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " AND a.verse_id<=".$str_end_verse;
					$query	= $query . " ORDER BY a.book_id, a.chapter_id, a.verse_id "; 
				}
				// Genesis 1:2-2:3
				else if(($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse))
				{
					$str_tmp_old_query = $query;
					$query	= "SELECT * FROM( ".$query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " ORDER BY a.verse_id ASC ) as c";
					$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id=".$str_end_chap." AND a.verse_id<=".$str_end_verse." ORDER BY a.verse_id ASC) as d";
					if(($str_end_chap - $str_begin_chap)>1)
					{
						$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id>=".($str_begin_chap+1)." AND a.chapter_id<=".($str_end_chap-1)." ORDER BY a.verse_id ASC) as e";
   					}
					$query  = $query. " ORDER BY chapter_id, verse_id";
				}
				
				
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		return $data;
	}
}
?>