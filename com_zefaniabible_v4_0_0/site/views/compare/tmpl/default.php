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
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_common 	= new ZefaniabibleCommonHelper;
?>

<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div id="zef_Bible_Main">
    	<div class="zef_legend">
        	<?php if($this->item->flg_email_button){?>
            <div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?view=subscribe&option=com_zefaniabible&tmpl=component');?>" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/e_mail.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" /></a></div>
            <?php } ?>
            <div class="zef_bible_Header_Label"><h1 class="zef_bible_Header_Label_h1"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$this->item->int_Bible_Chapter; ?></h1></div>
            <?php if(($this->item->flg_use_bible_selection)and(count($this->item->arr_Bibles) > 1)){?>            
                <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION_FIRST');?></div>
                <div class="zef_bible">
                    <select name="bible" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $this->item->obj_bible_Bible_dropdown_1; ?>
                    </select>
                </div>
				<div style="clear:both;"></div>                
               <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION_SECOND');?></div>
                <div class="zef_bible">
                    <select name="bible2" id="bible" class="inputbox" onchange="this.form.submit()">
                        <?php echo $this->item->obj_bible_Bible_dropdown_2; ?>
                    </select>
                </div>                
            <?php } else {
				echo '<input type="hidden" name="bible" value="'.$this->item->str_Main_Bible_Version.'" />';
				echo '<input type="hidden" name="bible2" value="'.$this->item->str_Second_Bible_Version.'" />';
             }?> 			
            <div class="zef_book_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK');?></div>
            <div class="zef_book">
                <select name="book" id="book" class="inputbox" onchange="this.form.submit()">
					<?php echo $this->item->obj_bible_book_dropdown;?>
                </select>
            </div>

            <div class="zef_chapter_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_CHAPTER');?></div>
            <div class="zef_Chapter">
                <select name="chapter" id="chapter" class="inputbox" onchange="this.form.submit()">
					<?php echo $this->item->obj_bible_chap_dropdown;?>
                </select>
            </div>
             <div style="clear:both;"></div>
			<?php if($this->item->flg_show_commentary){ 
					if(count($this->item->arr_commentary_list)> 1){
			?>
                    <div>
                        <div class="zef_commentary_label"><?php echo JText::_('COM_ZEFANIABIBLE_COMMENTARY_LABEL');?></div>
                        <div class="zef_commentary">
                            <select name="com" id="commentary" class="inputbox" onchange="this.form.submit()">
                                <?php echo $this->item->obj_commentary_dropdown;?>
                             </select>
                        </div>
                    </div>
					<?php }else{?>
                       	<!--<input type="hidden" name="com" value="<?php echo $cls_bibleBook->str_primary_commentary;?>" />-->
            <?php }} ?>
			<?php if($this->item->flg_show_dictionary){
						if((count($this->item->arr_dictionary_list) > 1)and($this->item->flg_strong_dict)){?>
                            <div id="zef_dictionary_div">
                                <div class="zef_dictionary_label"><?php echo JText::_('COM_ZEFANIABIBLE_DICTIONARY_LABEL');?></div>
                                <div class="zef_dictionary">
                                    <select name="dict" id="dictionary" class="inputbox" onchange="this.form.submit()">
                                        <?php echo $this->item->obj_dictionary_dropdown;?>
                                     </select>
                                </div>
                            </div>
					<?php }?>
                    	<?php if($this->item->flg_strong_dict){?>
							<div class="zef_dictionary_strong_box">
                            	<div class="zef_dictionary_strong_label"><?php echo JText::_('COM_ZEFANIABIBLE_HIDE_STRONG');?></div>
								<div class="zef_dictionary_strong_input">
	                                <input type='hidden' value='0' name='strong'>
                                	<input type='checkbox' name='strong' value="1" id='zef_hide_strong' <?php if($this->item->flg_use_strong == 1){ echo 'checked="checked"';}?> onchange="this.form.submit()" />
								</div>
							</div>
	                    <?php } ?>      
                   <?php } ?>
             <div style="clear:both;"></div>
            <div class="zef_top_pagination">
				<?php if($this->item->flg_show_page_top){ $mdl_common->fnc_Pagination_Buttons($this->item);} ?>
            </div>              
        </div>   
             <div class="zef_player">
			<?php if(($this->item->flg_show_audio_player)and($this->obj_player_one)){ ?>              
             	<div class="zef_player-1"> 
                	<?php echo $this->obj_player_one; 
							echo '<div style="clear:both;"></div>';
				            echo  '<a href="'.JRoute::_('index.php?option=com_zefaniabible&view=player&bible='.$this->item->str_Bible_Version.'&book='.$this->item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$this->item->arr_english_book_names[($this->item->int_Bible_Book_ID)])).'&Itemid='.$this->item->int_menu_item_id.'&tmpl=component').'" target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";
					?>
                </div>
            <?php }?>                   
                <?php if(($this->item->flg_show_audio_player)and($this->item->flg_show_second_player)and($this->obj_player_two)){?>
                <div class="zef_player-2"> 
               		<?php echo $this->obj_player_two; 
							echo '<div style="clear:both;"></div>';
				            echo  '<a href="'.JRoute::_('index.php?option=com_zefaniabible&view=player&bible='.$this->item->str_Second_Bible_Version.'&book='.$this->item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$this->item->arr_english_book_names[($this->item->int_Bible_Book_ID)])).'&Itemid='.$this->item->int_menu_item_id.'&tmpl=component').'" target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";					
					?>
                </div>
                <?php }?>
            </div>

        <div style="clear:both;"></div>
        <div class="zef_bible_Chapter"><article><?php echo $this->item->chapter_output; ?></article></div>     
        <div class="zef_footer">
            <div class="zef_bot_pagination">
            	<?php if($this->item->flg_show_page_bot){ $mdl_common->fnc_Pagination_Buttons($this->item);} ?>  
            	<div style="clear:both;"></div>
	            <?php 
				if(($this->item->flg_show_credit)or(JRequest::getInt('Itemid') == 0 ))
				{
					require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
					$mdl_credits = new ZefaniabibleCredits;
					$obj_player_one = $mdl_credits->fnc_credits();
				} ?>
                    
            </div>             
			
        </div>
    </div>
	<input type="hidden" name="option" value="<?php echo $this->item->str_option;?>" />
	<input type="hidden" name="view" value="<?php echo $this->item->str_view;?>" />
    <input type="hidden" name="Itemid" value="<?php echo $this->item->int_menu_item_id; ?>"/>
</form>
<?php 
	if($this->item->flg_enable_debug == 1)
	{
		echo '<!--';
		print_r($this->item);
		echo '-->';
	}
?>