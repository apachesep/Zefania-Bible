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
	<div>
    	<div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_EMAIL_LABEL');?></div> 
        <div>
            <input type="text" name="email" id="zef_subs_email" maxlength="50" size="40" value="<?php echo $this->item->str_email;?>">
        </div>
  </div>
	<div style="clear:both"></div> 
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL');?></div> 
             <div class="zef_reading_email">
                <select name="send_reading" id="zef_subs_send_reading" class="inputbox">
                    <option value="0" ><?php echo JText::_('JNO');?></option>
                    <option value="1" <?php if($this->item->flg_send_reading){?>selected<?php }?> ><?php echo JText::_('JYES');?></option>            
                </select>
             </div>
         </div>
         <div style="clear:both"></div> 
         
         <div>
             <div class="zef_bible_label"><?php echo JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL');?></div> 
             <div class="zef_reading_verse">
                <select name="send_verse" id="zef_subs_send_verse" class="inputbox">
                    <option value="0" ><?php echo JText::_('JNO');?></option>
                    <option value="1"  <?php if($this->item->flg_send_verse){?>selected<?php }?> ><?php echo JText::_('JYES');?></option>
                </select>
             </div>  
         </div>
         <div style="clear:both"></div> 
    
         <div>
        <?php
			if($this->item->flg_use_catcha)
			{
				$this->item->flg_catcha_correct = $mdl_common->fnc_create_catcha($this->item->str_view);
			}
			else
			{
				$this->item->flg_catcha_correct = 1;
			}
        ?>
         </div>    
    <div><input class="zef_submit_subscribtion" type="submit" value="submit" name="<?php echo JText::_('JSAVE') ?>"/></div>
</form>