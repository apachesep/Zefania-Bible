<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Cpanel
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.propoved.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$mdl_zef_bible_helper = new ZefaniabibleHelper();
$mdl_zef_bible_helper->headerDeclarations();


JDom::_('html.toolbar');
?>
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">
	<?php
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0'))
		$compat = '3.0';
	?>
    <div class="row-fluid">
		<div>
			<div class="cpanel">        
            	<div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_BIBLES');?>" href="index.php?option=com_zefaniabible&view=zefaniabible">
	                        <span class="ico-48-zefaniabible_biblenames" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_BIBLES');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_BIBLES');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_SCRIPTURE');?>" href="index.php?option=com_zefaniabible&view=zefaniascripture">
                        	<span class="ico-48-zefaniabible_biblenames" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_SCRIPTURE');?>"></span>
    	                    <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_SCRIPTURE');?></span>
                        </a>
					</div>
                </div>
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_COMMENTARIES');?>" href="index.php?option=com_zefaniabible&view=zefaniacomment">
                        	<span class="ico-48-zefaniabible_zefaniacomment" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_COMMENTARIES');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_COMMENTARIES');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
	                <div class="button">
    	            	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN');?>" href="index.php?option=com_zefaniabible&view=zefaniareading">
                        	<span class="ico-48-zefaniabible_zefaniareading" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN');?></span>
    					</a>
					</div>
		        </div>
        		<div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN_DETAILS');?>" href="index.php?option=com_zefaniabible&view=zefaniareadingdetails">
                            <span class="ico-48-zefaniabible_zefaniareadingdetails" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN_DETAILS');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_READING_PLAN_DETAILS');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_USERS');?>" href="index.php?option=com_zefaniabible&view=zefaniauser">
                            <span class="ico-48-zefaniabible_zefaniauser" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_USERS');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_USERS');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
                	<div class="button">
                    	<a title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY');?>" href="index.php?option=com_zefaniabible&view=zefaniaverseofday">
							<span class="ico-48-zefaniabible_zefaniaverseofday" title="<?php  echo JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY');?>"></span>
                            <span><?php  echo JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY');?></span>
						</a>
					</div>
				</div>
                <div style="float:left;">
                	<div class="button">
						<a title="<?php  echo JText::_('COM_ZEFANIABIBLE_DICTIONARY');?>" href="index.php?option=com_zefaniabible&view=zefaniadictionary">
        					<span class="ico-48-zefaniabible_zefaniabibledictionaryinfo" title="<?php  echo JText::_('COM_ZEFANIABIBLE_DICTIONARY');?>"></span>
                            <span><?php  echo JText::_('COM_ZEFANIABIBLE_DICTIONARY');?></span>
						</a>
					</div>
				</div>
			</div>
	        <div class="clearfix"></div>
		</div>
	</div>

	<?php 
			require_once(JPATH_ADMIN_ZEFANIABIBLE.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			$obj_player_one = $mdl_credits->fnc_credits();	
	?>
</form>
