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
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<div id="zef_Bible_Main">
    	<div class="zef_legend">
        	<?php if($this->item->flg_show_ical){?>
	            <div class="zef_reading_ical">
					<a title="<?php echo JText::_('ZEFANIABIBLE_ICAL_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?option=com_zefaniabible&view=planrss&plan='.$this->item->str_reading_plan.'&bible='.$this->item->str_Bible_Version).'?variant=ical';?>" target="_blank" rel="nofollow" >
                    	<img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/ical.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_ICAL_BUTTON_TITLE'); ?>" />
                    </a>                
                </div>
            <?php }?>
			<?php if($this->item->flg_reading_rss_button){?>
		        <div class="zef_reading_rss">
                	<a title="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?option=com_zefaniabible&view=planrss&format=raw&plan='.$this->item->str_reading_plan.'&bible='.$this->item->str_Bible_Version.'&start='.$this->pagination->limitstart.'&items='.$this->pagination->limit.'&variant=rss');?>" target="_blank" rel="nofollow" >
                    	<img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/feeds.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_RSS_BUTTON_TITLE'); ?>" />
                    </a>
				</div>                
             <?php } ?>        
        	<?php if($this->item->flg_email_button){?>
			<div class="zef_email_button"><a title="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" target="blank" href="<?php echo	JRoute::_('index.php?view=subscribe&option=com_zefaniabible&tmpl=component');?>" class="modal" rel="{handler: 'iframe', size: {x:500,y:400}}" ><img class="zef_email_img" src="<?php echo JURI::root()."media/com_zefaniabible/images/e_mail.png"; ?>" width="24" height="24" alt="<?php echo JText::_('ZEFANIABIBLE_EMAIL_BUTTON_TITLE'); ?>" /></a></div>        
            <?php } ?>
    		<div class="zef_reading_label"><?php echo JText::_('ZEFANIABIBLE_READING_PLAN');?></div>
            <div class="zef_reading_plan">
                <select name="plan" id="reading" class="inputbox" onchange="this.form.submit()">
                    <?php echo $this->item->obj_reading_plan_dropdown;?>
                </select>
            </div>
            <div style="clear:both"></div>       
            <?php if($this->item->flg_use_bible_selection){?>
            <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_VERSION');?></div>
            <div class="zef_bible">
                <select name="bible" id="bible" class="inputbox" onchange="this.form.submit()">
                    <?php echo $this->item->obj_bible_Bible_dropdown;?>
                </select>
            </div>
            <?php }else {
				echo '<input type="hidden" name="bible" value="'.$this->item->str_Bible_Version.'" />';
			} ?>
             <div style="clear:both"></div>
        <div class="zef_reading_name"><h1 class="zef_bible_Header_Label_h1"><?php echo $this->item->str_reading_plan_name;?></h1></div>
        <div class="zef_reading_desc"><?php echo $this->item->str_description; ?></div>
		<div class="zef_top_pagination">        
				<?php 
					if($this->item->flg_show_page_top)
					{?>
                        <div class="pagination">
                            <p class="counter">
                                <div style="float:left"><?php echo $this->pagination->getPagesCounter(); ?></div>
                                <div style="float:right"><?php echo JText::_('JGLOBAL_DISPLAY_NUM')." ".$this->pagination->getLimitBox();?></div>
                                <div style="clear:both"></div>
                            </p>
                            <?php echo $this->pagination->getPagesLinks(); ?>
                        </div>
				<?php	}
				?>   
		</div>
        <div style="clear:both"></div>
	</div>
        <div class="zef_bible_Chapter"> 
        <?php echo $this->item->chapter_output; ?>     
        </div>
        <div class="zef_footer">
			<div class="zef_bot_pagination">        
                <?php 
					if($this->item->flg_show_page_bot)
					{
						echo $this->pagination->getListFooter();
					}
				?>  
                <div style="clear:both"></div>
                <?php 
				if($this->item->flg_show_credit)
				{
					require_once(JPATH_COMPONENT_SITE.'/helpers/credits.php');
					$mdl_credits = new ZefaniabibleCredits;
					$obj_player_one = $mdl_credits->fnc_credits();
				}
            	?>                
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