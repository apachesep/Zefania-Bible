<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

require_once(JPATH_COMPONENT_SITE.'/models/default.php');
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_default 	= new ZefaniabibleModelDefault;
$mdl_common 	= new ZefaniabibleCommonHelper;
		$cnt_comment 	= $mdl_default->fnc_count_publications('comment');
		$cnt_bibles 	= $mdl_default->fnc_count_publications('bible');
		$cnt_dict 		= $mdl_default->fnc_count_publications('dict');

?>


<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<!-- sidebar -->
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<!-- end sidebar -->
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<div class="row-fluid">
		<div>
			<div class="cpanel">        
            	<div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_BIBLES');?>" href="index.php?option=com_zefaniabible&view=zefaniabible">
	                        <span class="ico-48-zefaniabible_biblenames" title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_BIBLES');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_LAYOUT_BIBLES');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_COMMENTARIES');?>" href="index.php?option=com_zefaniabible&view=zefaniacomment">
                        	<span class="ico-48-zefaniabible_zefaniacomment" title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_COMMENTARIES');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_LAYOUT_COMMENTARIES');?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
                	<div class="button">
						<a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_DICTIONARY');?>" href="index.php?option=com_zefaniabible&view=zefaniadictionary">
        					<span class="ico-48-zefaniabible_zefaniabibledictionaryinfo" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_DICTIONARY');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_MENU_DICTIONARY');?></span>
						</a>
					</div>
				</div>
				<div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_FIELD_VERSE_OF_DAY');?>" href="index.php?option=com_zefaniabible&view=zefaniaverseofday">
							<span class="ico-48-zefaniabible_zefaniaverseofday" title="<?php  echo JText::_('ZEFANIABIBLE_FIELD_VERSE_OF_DAY');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_FIELD_VERSE_OF_DAY');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
	                <div class="button">
    	            	<a title="<?php  echo JText::_('ZEFANIABIBLE_FIELD_READING_PLAN');?>" href="index.php?option=com_zefaniabible&view=zefaniareading">
                        	<span class="ico-48-zefaniabible_zefaniareading" title="<?php  echo JText::_('ZEFANIABIBLE_FIELD_READING_PLAN');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_FIELD_READING_PLAN');?></span>
    					</a>
					</div>
		        </div>
        		<div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS');?>" href="index.php?option=com_zefaniabible&view=zefaniareadingdetails">
                            <span class="ico-48-zefaniabible_zefaniareadingdetails" title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS');?></span>
						</a>
					</div>
				</div> 
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_USERS');?>" href="index.php?option=com_zefaniabible&view=zefaniauser">
                            <span class="ico-48-zefaniabible_zefaniauser" title="<?php  echo JText::_('ZEFANIABIBLE_LAYOUT_USERS');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_LAYOUT_USERS');?></span>
						</a>
					</div>
				</div>
                <div style="clear:both"></div>
                <hr/>
                <h3><?php  echo JText::_('COM_ZEFANIABIBLE_ADVANCED_FEATURES'); ?></h3>                                                                                   
                <?php if($cnt_bibles >=1){?>
                    <div style="float:left;">
                        <div class="button">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?>" href="index.php?option=com_zefaniabible&view=zefaniascripture">
                                <span class="ico-48-zefaniabible_biblenames" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?></span>
                            </a>
                        </div>
                    </div>
                <?php }else{?>
                    <div style="float:left;">
                        <div class="button ico-disabled">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?>" href="#">
                                <span class="ico-48-zefaniabible_biblenames" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_SCRIPTURES');?></span>
                            </a>
                        </div>
                    </div>                
                <?php }?>
                <?php if($cnt_comment >=1){?>
                    <div style="float:left;">
                        <div class="button">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?>" href="index.php?option=com_zefaniabible&view=zefaniacommentdetail">
                                <span class="ico-48-zefaniabible_zefaniacomment" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?></span>
                            </a>
                        </div>
                    </div>
                <?php }else{?>
                    <div style="float:left;">
                        <div class="button ico-disabled">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?>" href="#"> 
                                <span class="ico-48-zefaniabible_zefaniacomment" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_COMMENTARY_EDIT');?></span>
                            </a>
                        </div>
                    </div>
                <?php }?>
                <?php if($cnt_dict >=1){?>
                    <div style="float:left;">
                        <div class="button">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?>" href="index.php?option=com_zefaniabible&view=zefaniabibledictdetail">
                                <span class="ico-48-zefaniabible_zefaniabibledictionaryinfo" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?></span>
                            </a>
                        </div>
                    </div>                
                <?php }else{?>
                    <div style="float:left;">
                        <div class="button ico-disabled">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?>" href="#">
                                <span class="ico-48-zefaniabible_zefaniabibledictionaryinfo" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_Dictionary_EDIT');?></span>
                            </a>
                        </div>
                    </div>                 
                <?php }?>
                    <div style="float:left;">
                        <div class="button">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_CROSS_REF_EDIT');?>" href="index.php?option=com_zefaniabible&view=zefaniacrossref">
                                <span class="ico-48-zefaniabible_references" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_CROSS_REF_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_CROSS_REF_EDIT');?></span>
                            </a>
                        </div>
                    </div>
                    <div style="float:left;">
                        <div class="button">
                            <a title="<?php  echo JText::_('ZEFANIABIBLE_MENU_PUBLISH_EDIT');?>" href="index.php?option=com_zefaniabible&view=zefaniapublish">
                                <span class="ico-48-zefaniabible_publish" title="<?php  echo JText::_('ZEFANIABIBLE_MENU_PUBLISH_EDIT');?>"></span>
                                <span><?php  echo JText::_('ZEFANIABIBLE_MENU_PUBLISH_EDIT');?></span>
                            </a>
                        </div>
                    </div>                        
			</div>
	        <div class="clearfix"></div>
		</div>
	</div>
	</form>
    <?php 
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			$obj_player_one = $mdl_credits->fnc_credits();	
	?>    
</div>