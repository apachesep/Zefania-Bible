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
class TableView {
	public function __construct($item, $obj_player)
	{	
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_common 	= new ZefaniabibleCommonHelper;	
	?>
        <form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
            <div id="zef_Bible_Main<?php if($item->str_tmpl == "component"){?>_tmpl_comp<?php }?>">
                <div class="zef_legend">
                    <?php if(($item->flg_reading_rss_button)and($item->str_tmpl != "component")){?>
                        <div class="zef_reading_rss">
                            <a title="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter);?>" target="_blank" rel="nofollow" >
                                <img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/feeds.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" />
                            </a>
                        </div>
                     <?php } ?>
                    <?php if(($item->flg_email_button)and($item->str_tmpl != "component")){?>
                    <div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?view=subscribe&option=com_zefaniabible&tmpl=component');?>" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/e_mail.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" /></a></div>
                    <?php } ?>
                    <div class="zef_bible_Header_Label"><h1 class="zef_bible_Header_Label_h1"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter; ?></h1></div>
                    <?php if(($item->flg_use_bible_selection)and(count($item->arr_Bibles) > 1)){?>
                        <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
                        <div class="zef_bible">
                            <select name="bible" id="bible" class="inputbox" onchange="this.form.submit()">
                                <?php echo $item->obj_bible_Bible_dropdown; ?>
                            </select>
                        </div>
                    <?php }else{
                            echo '<input type="hidden" name="bible" value="'.$item->str_Bible_Version.'" />';
                        }?> 
                    <?php if($item->str_tmpl == "component"){?>
                        <div style="clear:both;"></div>
                    <?php }?>                
                    <div class="zef_book_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK');?></div>
                    <div class="zef_book">
                        <select name="book" id="book" class="inputbox" onchange="this.form.submit()">
                            <?php echo $item->obj_bible_book_dropdown; ?>
                        </select>
                    </div>
                    <?php if($item->str_tmpl == "component"){?>
                        <div style="clear:both;"></div>
                    <?php }?>
                    <div class="zef_chapter_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_CHAPTER');?></div>
                    <div class="zef_Chapter">
                        <select name="chapter" id="chapter" class="inputbox" onchange="this.form.submit()">
                            <?php echo $item->obj_bible_chap_dropdown; ?>             
                        </select>
                    </div>
                    <div style="clear:both;"></div>
                    <?php if($item->flg_show_commentary){ 
                                if(count($item->arr_commentary_list)> 1){
                    ?>
                                    <div id="zef_commentary_div">
                                        <div class="zef_commentary_label"><?php echo JText::_('COM_ZEFANIABIBLE_COMMENTARY_LABEL');?></div>
                                        <div class="zef_commentary">
                                            <select name="com" id="commentary" class="inputbox" onchange="this.form.submit()">
                                                <?php echo $item->obj_commentary_dropdown;?>
                                             </select>
                                        </div>
                                    </div>
                                <?php }else{?>
                    <?php }} ?>
                    
                   <?php if($item->flg_show_dictionary){
                                if((count($item->arr_dictionary_list) > 1)and($item->flg_strong_dict)){?>
                                    <div id="zef_dictionary_div">
                                        <div class="zef_dictionary_label"><?php echo JText::_('COM_ZEFANIABIBLE_DICTIONARY_LABEL');?></div>
                                        <div class="zef_dictionary">
                                            <select name="dict" id="dictionary" class="inputbox" onchange="this.form.submit()">
                                                <?php echo $item->obj_dictionary_dropdown;?>
                                             </select>
                                        </div>
                                    </div>   	
                            <?php }?>
                                <?php if($item->flg_strong_dict){?>
                                    <div class="zef_dictionary_strong_box">
                                        <div class="zef_dictionary_strong_label"><?php echo JText::_('COM_ZEFANIABIBLE_HIDE_STRONG');?></div>
                                        <div class="zef_dictionary_strong_input" >
                                            <input type='hidden' value='0' name='strong'>
                                            <input type='checkbox' name='strong' value="1" id='zef_hide_strong' <?php if($item->flg_use_strong == 1){ echo 'checked="checked"';}?> onchange="this.form.submit()" />
                                        </div>
                                    </div>
                                <?php } ?>                         
                            <?php } ?>
                    <div style="clear:both;"></div>
                    
                    <div class="zef_top_pagination">
                        <?php if($item->flg_show_page_top){ $mdl_common->fnc_Pagination_Buttons($item);} ?>
                    </div>        
                </div>
                <?php if(($item->flg_show_audio_player)and($obj_player)){ ?>
                     <div class="zef_player">
                        <?php echo $obj_player;
                        echo '<div style="clear:both;"></div>';
                        echo  '<a href="'.JRoute::_('index.php?option=com_zefaniabible&view=player&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID)])).'&Itemid='.$item->int_menu_item_id.'&tmpl=component').'"  target="_blank" >'.JText::_('ZEFANIABIBLE_PLAYER_WHOLE_BOOK')."</a>";
                         ?>
                    </div>
                <?php }?>             
                <div style="clear:both;"></div>
                <div class="zef_bible_Chapter">
                    <article>
                    <table>
                        <?php
                        $x = 0;
                        $str_Chapter_Output = '';
                        foreach ($item->arr_Chapter as $arr_verse)
                        {
                            if($item->flg_show_dictionary == 1)
                            {
                                $str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
                                if($item->flg_use_strong == 1)
                                {
                             //       $arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);
                             //       $arr_verse->verse = preg_replace('/{dict-alias}/iu',$item->str_curr_dict,$arr_verse->verse);
                             //       $arr_verse->verse = preg_replace('/{dict-width}/iu',$item->str_dictionary_width,$arr_verse->verse);
                             //       $arr_verse->verse = preg_replace('/{dict-height}/iu',$item->str_dictionary_height,$arr_verse->verse);
                                }
                                else
                                {
                                    $arr_verse->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','',$arr_verse->verse);
                                }
                            }
                            if ($x % 2)	
                            {
                                echo '<tr class="odd">';
                            }
                            else
                            {
                                echo '<tr class="even">'; 
                            }
                            
                            echo "<td class='zef_verse_number'>".$arr_verse->verse_id."</td><td class='zef_verse' style='max-width:80%;'>".$arr_verse->verse."</td>";
                            
                            if($item->flg_show_references)
                            {
                                foreach($item->arr_references as $obj_references)
                                {
                                    if($obj_references->verse_id == $arr_verse->verse_id)
                                    {
                                        $temp = 'bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&verse='.$arr_verse->verse_id;
                                        $str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
                                        echo '<td class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></td>';	
                                        break;
                                    }
                                }
                            }		
                            
                            if($item->flg_show_commentary)
                            {
                                foreach($item->arr_commentary as $int_verse_commentary)
                                {
                                    if($arr_verse->verse_id == $int_verse_commentary->verse_id)
                                    {
                                        $str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$item->int_Bible_Book_ID."&chapter=".$item->int_Bible_Chapter."&verse=".$arr_verse->verse_id."&tmpl=component");
                                        echo '<td class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></td>";
                                    }
                                }
                            }			
                            echo '</tr>';
                            $x++;
                        }			
                ?></table>
                    </article>
                </div>     
                <div class="zef_footer">
                    <div class="zef_bot_pagination">
                        <?php if($item->flg_show_page_bot){ $mdl_common->fnc_Pagination_Buttons($item);} ?>        
                        <div style="clear:both;"></div>
                        <?php if(($item->flg_show_credit)or($item->int_menu_item_id == 0 ))
                        { 
                            require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
                            $mdl_credits = new ZefaniabibleCredits;
                            $obj_player_one = $mdl_credits->fnc_credits();
                        } ?>
                    </div>  
                </div>
            </div>
            <input type="hidden" name="option" value="<?php echo $item->str_option;?>" />
            <input type="hidden" name="view" value="<?php echo $item->str_view;?>" />
            <input type="hidden" name="Itemid" value="<?php echo $item->int_menu_item_id; ?>"/>
        </form>
        <div itemscope itemtype="http://schema.org/Book">
            <meta itemprop="name" content="<?php echo $item->str_bible_name;?>">
            <meta itemprop="image" content="<?php echo JURI::root().$item->str_default_image; ?>">
            <meta itemprop="url" content="<?php echo JFactory::getURI()->toString(); ?>">
        </div>
        <?php 
            if($item->flg_enable_debug == 1)
            {
                echo '<!--';
                print_r($item);
                echo '-->';
            }
        ?>        
<?php	
	}	
	private function fnc_check_strong_bible($arr_chapter)
	{
		$flg_is_strong = 0;
		$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
		$x=1;
		foreach($arr_chapter as $obj_chapter)
		{
			if(preg_match($str_match_fuction,$obj_chapter->verse))
			{
				$flg_is_strong = 1;
				break;
			}
			// only check 1st 5 verses
			if($x>4)
			{
				break;	
			}
			
			$x++;
		}
		return $flg_is_strong;
	}	
}
?>
