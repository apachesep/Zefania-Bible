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
$function	= JFactory::getApplication()->input->get('function', 'jSelectScripture');
$document	= JFactory::getDocument();
$document->addStyleSheet('/plugins/content/zefaniascripturelinks/css/zefaniascripturelinks.css'); 
		
$cls_button_scripture = new cls_button_scripture($this->item); 

class cls_button_scripture {
	/*
		a = Language 
		b = Link Type 
		c = set tag flag
		d = Label
		e = Alias
		f = Bible Book
		g = Begin Chap
		h = Begin Verse
		i = End Chap
		j = End Verse
	*/
		
	public function __construct($item)	
	{
		$arr_toolTipArray = array('className'=>'zefania-tip', 
			'fixed'=>true,
			'showDelay'=>'500',
			'hideDelay'=>'5000'
			);
		JHTML::_('bootstrap.tooltip', '.hasTip-zefania', $arr_toolTipArray);

		// Load languages and merge with fallbacks
		$jlang = JFactory::getLanguage();
		if($item->str_lang != "en-GB")
		{
			$jlang->load('com_zefaniabible', JPATH_COMPONENT, $item->str_lang, true);				
		}
		$flg_add_title = 0;
		$str_link = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".$item->str_begin_chap; 
		if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and(!$item->str_end_verse))
		{
			$str_link .= ":".$item->str_begin_verse;
		}
		else if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
		{
			$str_link .= ":".$item->str_begin_verse."-".$item->str_end_verse;
		}
		elseif(($item->str_begin_chap)and($item->str_end_chap)and(!$item->str_begin_verse)and(!$item->str_end_verse))
		{
			$str_link .= "-".$item->str_end_chap;
		}
		else if(($item->str_begin_chap)and($item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
		{
			$str_link .= ":".$item->str_begin_verse."-".$item->str_end_chap.":".$item->str_end_verse;
		}	
		if(($item->str_label == "")and($item->int_link_type == 1)and($item->str_begin_chap != 0))
		{
			$item->str_label = $str_link;
		}
		if($item->str_primary_bible = $item->str_Bible_Version)
		{
			$flg_add_title = 1;
		}
		if(($item->str_Bible_Version != '')and($item->str_begin_chap != 0)and($item->int_Bible_Book_ID != 0))
		{
			switch ($item->int_link_type)
			{
				case 1:
					$this->fnc_default_link($str_link,$flg_add_title,$item);
					break;
				case 2:
					$this->fnc_create_text_link( $flg_add_title, $item );
					break;
				case 3:
					$this->fnc_create_text_link($flg_add_title, $item );
					if($item->flg_use_tags)
					{
						$temp = $this->str_output_editor;
						if($flg_add_title)
						{
							$this->str_output_editor = "{zefaniabible tooltip ".$item->str_Bible_Version."}".$str_link."{/zefaniabible}";
						}
						else
						{
							$this->str_output_editor = "{zefaniabible tooltip}".$str_link."{/zefaniabible}";
						}
						$this->str_output_preview = $this->str_output_editor.'<hr><legend>'.JText::_('COM_ZEFANIABIBLE_TEXT_PREVIEW').'</legend>'.JHTML::tooltip($temp,'', '', $str_link, '', false,'hasTip-zefania');
					}
					else
					{
						$this->str_output_editor = JHTML::tooltip($this->str_output_editor,'', '', $str_link, '', false,'hasTip-zefania');
						$this->str_output_preview = $this->str_output_editor;
					}
					break;
				case 4:
				case 5:
					$this->fnc_biblegateway_link($str_link, $flg_add_title, $item);
					break;
				default:
					$this->fnc_default_link($str_link,$flg_add_title, $item);
			}
		}
	}
	private function fnc_biblegateway_link($str_link, $flg_add_title, $item)
	{
			// Bible gateway coding begins here
			$str_eng_passage = $item->arr_english_book_names[$item->int_Bible_Book_ID]." ".$item->str_begin_chap;		
			if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and(!$item->str_end_verse))
			{
				$str_eng_passage .= ":".$item->str_begin_verse;
			}
			else if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
			{
				$str_eng_passage .= ":".$item->str_begin_verse."-".$item->str_end_verse;
			}
			elseif(($item->str_begin_chap)and($item->str_end_chap)and(!$item->str_begin_verse)and(!$item->str_end_verse))
			{
				$str_eng_passage .= "-".$item->str_end_chap;
			}
			else if(($item->str_begin_chap)and($item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
			{
				$str_eng_passage .= ":".$item->str_begin_verse."-".$item->str_end_chap.":".$item->str_end_verse;
			}				
			$str_link_url = 'http://classic.biblegateway.com/passage/index.php?search='.urlencode($str_eng_passage).';&version='.$item->str_bible_gateway_version.';&interface=print';			
			$str_pre_link = '<a title="'. JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link.'" target="blank" href="'.$str_link_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_modal_width.',y:'.$item->int_modal_height.'}}">';
			if($item->int_link_type == 5)
			{
				$str_link = $item->str_label;
			}
			$str_output = $str_pre_link.$str_link .'</a>';
			// Bible gateway coding ends here
			$this->str_output_editor = $str_output;
			$str_output .=  '<hr><legend>'.JText::_('COM_ZEFANIABIBLE_MODAL_PREVIEW').'</legend><iframe src="'.$str_link_url.'" width="700" border="1"></iframe>';
			$this->str_output_preview = $str_output;		
	}
	private function fnc_default_link($str_link,$flg_add_title,$item)
	{
		$str_output = '';
		$str_url_link = JURI::root().'index.php?view=scripture&option=com_zefaniabible&tmpl=component&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->str_begin_chap.'&verse='.$item->str_begin_verse.'&endchapter='.$item->str_end_chap.'&endverse='.$item->str_end_verse;
		$str_output .= '<a title="'. JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link.'" target="blank" href="'.JRoute::_($str_url_link).'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_modal_width.',y:'.$item->int_modal_height.'}}">';
		if($item->int_link_type == 1)
		{
			$str_output .=  $item->str_label;
		}
		else
		{
			$str_output .=  $str_link;
		}
		$str_output .=  '</a>';
		if($item->flg_use_tags)
		{
			if($flg_add_title)
			{
				$this->str_output_editor = "{zefaniabible ".$item->str_Bible_Version."}".$str_link."{/zefaniabible}";
			}
			else
			{
				$this->str_output_editor = "{zefaniabible}".$str_link."{/zefaniabible}";
			}
		}
		else
		{
			$this->str_output_editor = $str_output;
		}
		$str_output = $this->str_output_editor. '<hr><legend>'.JText::_('COM_ZEFANIABIBLE_MODAL_PREVIEW').'</legend><iframe src="'.$str_url_link.'" width="700" border="1"></iframe>';
		$this->str_output_preview = $str_output;
	}
	protected function fnc_create_text_link($flg_add_title, $item )
	{
		$verse = '';
		$x = 1;
		$int_verse_cnt = count($item->arr_bible_verse);
		foreach($item->arr_bible_verse as $obj_verses)
		{	
			// Genesis 1:1
			if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and(!$item->str_end_verse))
			{
				$verse = 		'<div class="zef_content_scripture">';
				$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$item->str_begin_chap.':'.$item->str_begin_verse;
				$verse .= 	'<div class="zef_content_title">'.$title;
				if($flg_add_title)
				{
					$verse .= ' - '.$obj_verses->bible_name;
				}
				$verse .= 	'</div><div class="zef_content_verse"><div class="odd">'.$obj_verses->verse.'</div></div>';
				$verse .= '</div>';			
			}
			// Genesis 1:1-3
			else if(($item->str_begin_chap)and(!$item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
			{
				if($obj_verses->verse_id == $item->str_begin_verse)
				{
					$verse .= '<div class="zef_content_scripture">';
					$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$item->str_begin_chap.':'.$item->str_begin_verse.'-'.$item->str_end_verse;
					$verse .= 	'<div class="zef_content_title">'.$title;
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
				$verse .= 	'<div class="zef_conent_clear"></div>';
				$verse .= '</div>';
				if($x == $int_verse_cnt)
				{
					$verse .= '</div></div>';
				}
				$x++;				
			}
			// Genesis 1
			else if(($item->str_begin_chap)and(!$item->str_end_chap)and(!$item->str_begin_verse)and(!$item->str_end_verse))
			{
				if($obj_verses->verse_id == '1')
				{
					$verse .= '<div class="zef_content_scripture">';
					$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$item->str_begin_chap;
					$verse .= 	'<div class="zef_content_title">'.$title;
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
				$verse .= 	'<div class="zef_conent_clear"></div>';
				$verse .= '</div>';
				if($x == $int_verse_cnt)
				{	
					$verse .= '</div></div>';
				}
				$x++;				
			}
			// Genesis 1-2
			else if(($item->str_begin_chap)and($item->str_end_chap)and(!$item->str_begin_verse)and(!$item->str_end_verse))
			{
				if(($obj_verses->verse_id == '1')and($item->str_begin_chap == $obj_verses->chapter_id))
				{
					$verse .= '<div class="zef_content_scripture">';
					$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$item->str_begin_chap.'-'.$item->str_end_chap;
					$verse .= 	'<div class="zef_content_title">'.$title;
					if($flg_add_title)
					{
						$verse .= ' - '.$obj_verses->bible_name;
					}
					$verse .= 	'</div><div class="zef_content_verse" >';
				}		
				if(($obj_verses->verse_id == '1')and($item->str_begin_chap != $obj_verses->chapter_id))
				{
					$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$obj_verses->chapter_id.'</div>';
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
				$verse .= 	'<div class="zef_conent_clear"></div>';
				$verse .= '</div>';		
				if($x == $int_verse_cnt)
				{	
					$verse .= '</div></div>';	
				}	
				$x++;				
			}
			// Genesis 1:30-2:3
			else if(($item->str_begin_chap)and($item->str_end_chap)and($item->str_begin_verse)and($item->str_end_verse))
			{
				if(($obj_verses->verse_id == $item->str_begin_verse)and($item->str_begin_chap == $obj_verses->chapter_id))
				{
					$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$item->str_begin_chap.':'.$item->str_begin_verse.'-'.$item->str_end_chap.':'.$item->str_end_verse;
					if($flg_add_title)
					{
						$title = $title.' - '.$obj_verses->bible_name;
					}
					$verse .= '<div class="zef_content_scripture">';
					$verse .= 	'<div class="zef_content_title">'.$title.'</div>';
					$verse .= 	'<div class="zef_content_verse" >';							
				}
				if(($obj_verses->verse_id == '1')and($item->str_begin_chap != $obj_verses->chapter_id))
				{
					$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.$obj_verses->chapter_id.'</div>';
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
				$verse .= 	'<div class="zef_conent_clear"></div>';
				$verse .= '</div>';	
				if($x == $int_verse_cnt)
				{	
					$verse .= '</div></div>';			
				}
				$x++;
			}
		}
		if(($item->flg_use_tags)and($item->int_link_type == 2))
		{
			if($flg_add_title)
			{
				$this->str_output_preview = 
				$this->str_output_editor = "{zefaniabible text ".$item->str_Bible_Version."}".$title."{/zefaniabible}";
			}
			else
			{
				$this->str_output_editor = "{zefaniabible text}".$title."{/zefaniabible}";
			}
		}
		else
		{
			$this->str_output_editor = $verse;
			
		}
		$this->str_output_preview = $this->str_output_editor.'<hr><legend>'.JText::_('COM_ZEFANIABIBLE_TEXT_PREVIEW').'</legend>'.$verse;
	}	
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_zefaniabible&amp;view=modal&amp;tmpl=component&amp;function='.$function);?>" method="post" name="adminForm" id="adminForm">
<fieldset class="adminform">
	<legend>Pick Scipture</legend>							   
    <div class="zef_modal" id="zef_modal">
    	<div id="zef_lang_list">
        	<label id="zef_lang_list_label"><?php echo JText::_('COM_ZEFANIABIBLE_SELECT_LANG'); ?></label>
            <select name="lang" id="zef_button_lang_list" class="inputbox" >
                <?php 
					foreach(JLanguage::getKnownLanguages() as $arr_system_lang)
					{
						if($arr_system_lang['tag'] == $this->item->str_lang)
						{
							echo '<option value="'.$arr_system_lang['tag'].'" selected>'.$arr_system_lang['name'].'</option>';
						}
						else
						{
							echo '<option value="'.$arr_system_lang['tag'].'">'.$arr_system_lang['name'].'</option>';
						}
					}
                ?>            
            </select>
        </div>
        <div class="zef_modal_type" id="zef_modal_type">
            <label id="zef_link_type_label" title="<?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_DESC'); ?>"><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE'); ?></label>
            <select name="type" id="zef_button_link_type" class="inputbox"  onchange="fnc_show_hide('zef_label');">
                <option value="0"<?php if($this->item->int_link_type == 0){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_DEFAULT'); ?></option>
                <option value="1"<?php if($this->item->int_link_type == 1){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_LABEL'); ?></option>
                <option value="2"<?php if($this->item->int_link_type == 2){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_TEXT'); ?></option>
                <option value="3"<?php if($this->item->int_link_type == 3){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_TOOLTIP'); ?></option>
                <option value="4"<?php if($this->item->int_link_type == 4){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_BIBLEGATEWAY'); ?></option>
                <option value="5"<?php if($this->item->int_link_type == 5){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_BIBLEGATEWAY')." ".JText::_('COM_ZEFANIABIBLE_LINK_TYPE_LABEL'); ?></option>
            </select>
            <div class="zef_conent_clear"></div>
        </div>
        <div class="zef_scriputure_tags_div" id="zef_scriputure_tags_div">
        	<label id="zef_scriputure_tags_label"><?php echo JText::_('COM_ZEFANIABIBLE_SCRIPTURE_LINK_TAGS') ?></label>
        	<input type="checkbox" name="tag" id="zef_scriputure_tags" <?php if($this->item->flg_use_tags){?>checked<?php }?>/>
            <div class="zef_conent_clear"></div>
        </div>
        <div style="clear:both"></div>
		<div class="zef_label" id="zef_label">
        	<label><?php echo JText::_('COM_ZEFANIABIBLE_LABEL') ?></label>
			<input name="label" id="zef_button_label" value="<?php echo $this->item->str_label; ?>" title="<?php echo JText::_('COM_ZEFANIABIBLE_LABEL') ?>" type="text" maxlength="25" size="25" />
            <div class="zef_conent_clear"></div>
		</div>
        <div style="clear:both"></div>
        <div class="zef_alias" id="zef_alias">
            <label class="zef_scripture_label"><?php echo JText::_('ZEFANIABIBLE_FIELD_ALIAS'); ?></label>
            <select name="bible" id="zef_button_bible_alias" class="inputbox">
                <option value="" ><?php echo JText::_('ZEFANIABIBLE_JSEARCH_SELECT_BIBLE_VERSION'); ?></option>
                <?php 
					echo $this->item->obj_bible_Bible_dropdown;
                ?>
            </select>
            <div class="zef_conent_clear"></div>
        </div>
        <div style="clear:both"></div>
        <div class="zef_scripture" id="zef_scripture">
            <div>
                <label><?php echo JText::_('ZEFANIABIBLE_FIELD_BIBLE_SCRIPTURE');?></label>
                <select name="book" id="zef_button_bible_book" class="inputbox" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_BIBLE_BOOK_NAME');?>">
                    <option value="0" ><?php echo JText::_('ZEFANIABIBLE_JSEARCH_SELECT_BOOK_ID'); ?></option>
                    <?php 
						echo $this->item->obj_bible_book_dropdown;
                    ?>
                </select>
            </div>
            <div>
                <input  name="chapter" id="zef_button_begin_chap" value="<?php if($this->item->str_begin_chap != 0){ echo $this->item->str_begin_chap;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_BEGIN_CHAPTER') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>:</div>
            <div>
                <input  name="verse" id="zef_button_begin_verse" value="<?php if($this->item->str_begin_verse != 0){echo $this->item->str_begin_verse;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_BEGIN_VERSE') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>-</div>
            <div>
                <input  name="endchapter" id="zef_button_end_chap" value="<?php if($this->item->str_end_chap != 0){echo $this->item->str_end_chap;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_END_CHAPTER') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>:</div>
            <div>
                <input  name="endverse" id="zef_button_end_verse" value="<?php if($this->item->str_end_verse !=0){echo $this->item->str_end_verse;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_END_VERSE') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="zef_conent_clear"></div>
        <div>
        	<input type="submit" />
            <button type="button" onclick="fnc_save();" <?php if($cls_button_scripture->str_output_editor == ''){?>disabled<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_SAVE_CLOSE');?></button> 
        </div>
    </div>
</fieldset>
<fieldset class="adminform">
	<legend><?php echo JText::_('COM_ZEFANIABIBLE_SCRIPTURE_PREVIEW'); ?></legend>
	<div class="zef_button_output">
    	<?php echo $cls_button_scripture->str_output_preview; ?>
    </div>
</fieldset>
</form>
<script language="javascript">
if((document.getElementById("zef_button_link_type").value != 1)&&(document.getElementById("zef_button_link_type").value != 5))
{
	document.getElementById('zef_label').style.visibility="hidden";
	document.getElementById('zef_label').style.display="none";
}
function fnc_show_hide(id)
{
	if((document.getElementById("zef_button_link_type").value == 1)||(document.getElementById("zef_button_link_type").value == 5))
	{
		document.getElementById(id).style.visibility="visible";
		document.getElementById(id).style.display="block";
	}
	else
	{
		document.getElementById(id).style.visibility="hidden";
		document.getElementById(id).style.display="none";
	}
}
function fnc_save()
{
	var str_html_button = '<?php echo addslashes($cls_button_scripture->str_output_editor);?>';
	window.parent.jSelectScripture(str_html_button);
	window.parent.SqueezeBox.close();	
}
</script>