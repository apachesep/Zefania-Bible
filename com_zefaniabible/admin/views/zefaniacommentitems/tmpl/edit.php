<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
jimport( 'joomla.filesystem.folder' );
//get the zefaniabibleitem
$model	= $this->getModel();
$mld_commentary	= $model->getItem();
$isNew		= ($mld_commentary->id < 1);
$params	= JComponentHelper::getParams( 'com_zefaniabible' );

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'zefaniacommentitems.cancel' || document.formvalidator.isValid(document.id('zefaniacommentitems-form')))
		{
			Joomla.submitform(task, document.getElementById('zefaniacommentitems-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_zefaniabible&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="zefaniacommentitems-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
		</div>
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('ZEFANIABIBLE_LAYOUT_DETAILS'), $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('full_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('full_name'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('file_location'); ?></div>
				<div class="controls"><?php if($isNew){?>
						<?php 
							$str_commentary_path = $params->get('xmlCommentaryPath', 'media/com_zefaniabible/commentary/');
							$arr_file_list_bible = JFolder::files(JPATH_SITE.'/'.$str_commentary_path,'.xml');
							$str_file_list = '';
							
							for($x = 0; $x < count($arr_file_list_bible); $x++)
							{
								$str_file_list .= '<option value="'.$arr_file_list_bible[$x].'">'.$arr_file_list_bible[$x].'</option>'.PHP_EOL;
							}
                        ?>
                        <div class="input-prepend input-append">
                            <div id="jform_file_location_icon" class="btn add-on icon-checkmark" onclick="toggleElement('jform_file_location','jform_file_location_list');"> </div>
                                <input name="jform[file_location]" id="jform_file_location" class="bible_input" value="<?php echo $this->item->file_location; ?>" type="text">
                        </div>
                        <br />
                        <div class="input-prepend input-append">
                                <div id="jform_file_location_list_icon" class="btn add-on icon-cancel" onclick="toggleElement('jform_file_location_list','jform_file_location');"> </div>
                                <select name="jform[file_location_list]" id="jform_file_location_list" class="bible_input" ><?php echo $str_file_list; ?></select>
                        </div>
                        <div id="infoUpload1" class="intend">
                            <span id="btnUpload1"></span>
                            <button id="btnCancel1" type="button" onclick="cancelQueue(upload1);" class="ss-hide upload_button" disabled="disabled">Cancel</button>
                            <span id="biblepathinfo" class="pathinfo ss-hide hasTip" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO_TOOLTIP'); ?>">
                                    <?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO').' /'.trim($str_commentary_path, '/').'/'; ?>
                            </span>
                        </div>
					<?php }else{?>
                    	<?php echo $this->form->getInput('file_location'); ?>
                    <?php }?>
				</div>
			</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>