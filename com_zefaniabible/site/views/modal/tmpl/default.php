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

	public $str_output_preview;
	public $str_output_editor;
	public function __construct($item)	
	{
		require_once(JPATH_SITE.'/components/com_zefaniabible/models/default.php');
		require_once(JPATH_SITE.'/components/com_zefaniabible/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$jlang = JFactory::getLanguage();		
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_ADMINISTRATOR, 'en-GB', true);			
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_ADMINISTRATOR, null, true);

		JHtml::_('jquery.ui');
		JHTML::_('behavior.modal');
		JHtml::_('bootstrap.tooltip');
		JHtml::_('bootstrap.popover');	
		$document = JFactory::getDocument();	
		$document->addStyleSheet(JURI::root().'plugins/content/zefaniascripturelinks/css/zefaniascripturelinks.css'); 
		$document->addScript(JURI::root().'plugins/content/zefaniascripturelinks/zefaniascripturelinks.js');		
		$this->str_output_editor = "";
		$this->str_output_preview = "";
		
		$str_alias = '';
		$str_unique_id = uniqid();
		$str_label = "";
		$flg_add_bible_title = 0;
		// load language
		$jlang = JFactory::getLanguage();
		if($item->str_lang != "en-GB")
		{
			$jlang->load('com_zefaniabible', JPATH_BASE, $item->str_lang, true);				
		}		
		// blank if same
		if($item->str_Bible_Version != $item->str_primary_bible)
		{
			$str_alias = $item->str_Bible_Version;	
			$flg_add_bible_title = 1;	
		}
		else
		{
			$str_alias = $item->str_primary_bible;	
		}
		// blank if same
		if($item->str_label != "")
		{
			$str_label = $item->str_label;
		}

		$str_scripture_name = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID )." ".$item->int_begin_chap;
		switch (true)
		{
			// make John 3:1
			case (($item->int_end_chap == 0)and($item->int_end_verse == 0)and($item->int_begin_verse !=0)):
				$str_scripture_name .= ":".$item->int_begin_verse;
				break;
			// make John 3:1-3
			case (($item->int_end_chap == 0)and($item->int_end_verse != 0)and($item->int_begin_verse !=0)):
				$str_scripture_name .= ":".$item->int_begin_verse."-".$item->int_end_verse;
				break;
			// make John 1-3
			case (($item->int_end_chap != 0)and($item->int_end_verse == 0)and($item->int_begin_verse ==0)):
				$str_scripture_name .= "-".$item->int_end_chap;
				break;
			// Make John 1:20-3:2
			case (($item->int_end_chap != 0)and($item->int_end_verse != 0)and($item->int_begin_verse !=0)):
				$str_scripture_name .= ":".$item->int_begin_verse."-".$item->int_end_chap.":".$item->int_end_verse;
				break;
			default:
				// no default
			break;		
		}
		if($flg_add_bible_title)
		{
			$str_title = $str_scripture_name.' - '. $str_alias;
		}
		else
		{
			$str_title = $str_scripture_name;
		}	
		$str_obj = '{type: \''.$item->str_link_type.'\', unique_id: \''.$str_unique_id.'\', native_name: \''.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).'\', scripture: \''.$str_scripture_name.'\', bible: \''.$str_alias.'\', book: '.$item->int_Bible_Book_ID.', chapter: '.$item->int_begin_chap.', verse: '.$item->int_begin_verse.', endchapter: '.$item->int_end_chap.', endverse: '.$item->int_end_verse.', width: '.$item->int_modal_width.', height: '.$item->int_modal_height.' }';		
		$temp = '&bible='.$str_alias.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_begin_chap.'&verse='.$item->int_begin_verse.'&endchapter='.$item->int_end_chap.'&endverse='.$item->int_end_verse;		
		switch($item->str_link_type)
		{
			case "biblegateway":
				if($item->flg_use_tags)
				{
					$str_link = urlencode(str_replace(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID) ,$item->arr_english_book_names[$item->int_Bible_Book_ID], $str_scripture_name));
					$str_url = 'http://classic.biblegateway.com/passage/index.php?search='.$str_link.';&version='.$item->str_bible_gateway_version.';&interface=print';							
					$str_scripture_verse = '<a href="'.$str_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_modal_width.',y:'.$item->int_modal_height.'}}" title="'. JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'; 
				}
				else
				{
					$str_scripture_verse = '{zefaniabible biblegateway '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}'; 					
				}
				break;
								
			case "dialog":
				if($item->flg_use_tags)
				{
					$str_scripture_verse = '<a id="zef-scripture-dialog" data-toggle="modal" data-target="#div-'.$str_unique_id.'" class="zef-scripture-dialog-'.$str_unique_id.'" onclick="fnc_scripture('.$str_obj.');" title="'.JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK').' '.$str_scripture_name.'">'.$str_scripture_name .'</a><div style="float:left" role="dialog" aria-labelledby="div-'.$str_unique_id.'" aria-hidden="true" id="div-'.$str_unique_id.'" class="modal fade " title="'.$str_title .'" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="div-'.$str_unique_id.'-label">'.$str_scripture_name.'</h4></div><div id="modal-body" class="modal-body-'.$str_unique_id.'">...</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.JText::_('COM_ZEFANIABIBLE_CLOSE').'</button></div></div></div></div>';					
				}
				else
				{
					$str_scripture_verse = '{zefaniabible dialog '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}'; 
				}
				break;
				
			case "label":	
				if($item->flg_use_tags)
				{
					$str_scripture_verse = '<a href="'.JURI::root().'/index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_modal_width.',y:'.$item->int_modal_height.'}}" title="'. JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-label">'.$str_label .'</a>'; 				
				}
				else
				{
					$str_scripture_verse = "{zefaniabible label='".$str_label."' ".$str_alias."}".$str_scripture_name."{/zefaniabible}"; 
				}
				break;
				
			case "popover":
				if($item->flg_use_tags)
				{
					$str_scripture_verse = '<a id="zef-scripture-popover-link" class="zef-scripture-popover-'.$str_unique_id.' hasPopover" onmouseover="fnc_scripture('.$str_obj.');" data-content="<div class=\'div-'.$str_unique_id.'\'><p></p></div>" data-placement="right" title="'.$str_title.'" id="zef-scripture-popover">'.$str_scripture_name .'</a>';		
				}
				else
				{
					$str_scripture_verse = '{zefaniabible popover '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}'; 
				}
				break;								
				
			case "text":
				if($item->flg_use_tags)
				{
					$arr_verses = $mdl_default->_buildQuery_scripture($str_alias, $item->int_Bible_Book_ID , $item->int_begin_chap, $item->int_begin_verse, $item->int_end_chap, $item->int_end_verse);	 
					
					$str_scripture_verse = $mdl_common->fnc_scripture_text_link($arr_verses, $item->int_Bible_Book_ID, $item->int_begin_chap, $item->int_end_chap, $item->int_begin_verse, $item->int_end_verse, $flg_add_bible_title, '','','', $flg_add_title = 1);
				}
				else
				{
					$str_scripture_verse = '{zefaniabible text '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}';
				}
				break;
				
			case "tooltip":
				if($item->flg_use_tags)
				{
					$str_scripture_verse = '<a id="zef-scripture-tooltip" data-placement="right" onMouseOver="fnc_scripture('.$str_obj.');" data-original-title="<strong>'.$str_title.'</strong><br /><div id=\'div-zef-scripture-tooltip\' class=\'div-'.$str_unique_id.'\'><p></p></div>" class="'.$str_unique_id.' hasTooltip" title="">'.$str_scripture_name .'</a>';
				}
				else
				{
					$str_scripture_verse = '{zefaniabible tooltip '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}';
				}
				break;
				
			default:
				if($item->flg_use_tags)
				{
					$str_scripture_verse = '<a href="'.JURI::root().'index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->int_modal_width.',y:'.$item->int_modal_height.'}}" title="'. JText::_('COM_ZEFANIABIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'; 
				}
				else
				{
					$str_scripture_verse = '{zefaniabible '.$str_alias.'}'.$str_scripture_name.'{/zefaniabible}';
				}
				break;		
		}
		$this->str_output_preview = $str_scripture_verse;
		$this->str_output_editor = $str_scripture_verse;

		return $this->str_output_editor;
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
                <option value="default" <?php if($this->item->str_link_type == "default"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_DEFAULT'); ?></option>
                <option value="label" <?php if($this->item->str_link_type == "label"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_LABEL'); ?></option>
                <option value="text" <?php if($this->item->str_link_type == "text"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_TEXT'); ?></option>
                <option value="tooltip" <?php if($this->item->str_link_type == "tooltip"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_TOOLTIP'); ?></option>
                <option value="biblegateway" <?php if($this->item->str_link_type == "biblegateway"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_BIBLEGATEWAY'); ?></option>
                <option value="dialog" <?php if($this->item->str_link_type == "dialog"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_DIALOG'); ?></option>
                <option value="popover" <?php if($this->item->str_link_type == "popover"){?>selected<?php }?>><?php echo JText::_('COM_ZEFANIABIBLE_LINK_TYPE_POPOVER'); ?></option>                
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
                <input  name="chapter" id="zef_button_begin_chap" value="<?php if($this->item->int_begin_chap != 0){ echo $this->item->int_begin_chap;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_BEGIN_CHAPTER') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>:</div>
            <div>
                <input  name="verse" id="zef_button_begin_verse" value="<?php if($this->item->int_begin_verse != 0){echo $this->item->int_begin_verse;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_BEGIN_VERSE') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>-</div>
            <div>
                <input  name="endchapter" id="zef_button_end_chap" value="<?php if($this->item->int_end_chap != 0){echo $this->item->int_end_chap;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_END_CHAPTER') ?>" type="text" maxlength="3" size="5" />
            </div>
            <div>:</div>
            <div>
                <input  name="endverse" id="zef_button_end_verse" value="<?php if($this->item->int_end_verse !=0){echo $this->item->int_end_verse;}?>" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_END_VERSE') ?>" type="text" maxlength="3" size="5" />
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
if((document.getElementById("zef_button_link_type").value != "label")&&(document.getElementById("zef_button_link_type").value != "biblegateway-label"))
{
	document.getElementById('zef_label').style.visibility="hidden";
	document.getElementById('zef_label').style.display="none";
}
function fnc_show_hide(id)
{
	if((document.getElementById("zef_button_link_type").value == "label")||(document.getElementById("zef_button_link_type").value == "biblegateway-label"))
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
	//window.parent.SqueezeBox.close();	
}
</script>