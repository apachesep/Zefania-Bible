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
$zefaniabibleitem	= $model->getItem();
$isNew		= ($zefaniabibleitem->id < 1);
$params	= JComponentHelper::getParams( 'com_zefaniabible' );

JError::raiseNotice('',JText::_('ZEFANIABIBLE_WARNING_BIBLE_INSTALL'));
JError::raiseNotice('',JText::_('ZEFANIABIBLE_WARNING_BIBLE_INSTALL_LONG_DURATION'));

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'zefaniabibleitem.cancel' || document.formvalidator.isValid(document.id('zefaniabibleitem-form')))
		{
			Joomla.submitform(task, document.getElementById('zefaniabibleitem-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_zefaniabible&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="zefaniabibleitem-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('bible_name'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('bible_name'); ?></div>
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
				<div class="control-label"><?php echo JText::_( "ZEFANIABIBLE_FIELD_XML_BIBLE_FILE_LOCATION" ); ?> : </div>
				<div class="controls">
                	<?php if($isNew){?>
						<?php 
                            $str_bibles_path = $params->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
							$arr_file_list_bible = JFolder::files(JPATH_SITE.'/'.$str_bibles_path,'.xml');
							$str_file_list = '';
							
							for($x = 0; $x < count($arr_file_list_bible); $x++)
							{
								$str_file_list .= '<option value="'.$arr_file_list_bible[$x].'">'.$arr_file_list_bible[$x].'</option>'.PHP_EOL;
							}
                        ?>
                        <div class="input-prepend input-append">
                            <div id="jform_bible_xml_file_icon" class="btn add-on icon-checkmark" onclick="toggleElement('jform_bible_xml_file','jform_bible_xml_file_list');"> </div>
                                <input name="jform[bible_xml_file]" id="jform_bible_xml_file" class="bible_input" value="<?php echo $this->item->bible_xml_file; ?>" type="text">
                        </div>
                        <br />
                        <div class="input-prepend input-append">
                                <div id="jform_bible_xml_file_list_icon" class="btn add-on icon-cancel" onclick="toggleElement('jform_bible_xml_file_list','jform_bible_xml_file');"> </div>
                                <select name="jform[bible_xml_file_list]" id="jform_bible_xml_file_list" class="bible_input" ><?php echo $str_file_list; ?></select>
                        </div>
                        <div id="infoUpload1" class="intend">
                            <span id="btnUpload1"></span>
                            <button id="btnCancel1" type="button" onclick="cancelQueue(upload1);" class="ss-hide upload_button" disabled="disabled">Cancel</button>
                            <span id="biblepathinfo" class="pathinfo ss-hide hasTip" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO_TOOLTIP'); ?>">
                                    <?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO').' /'.trim($str_bibles_path, '/').'/'; ?>
                            </span>
                        </div>
					<?php }else{?>
                    	<?php echo $this->form->getInput('bible_xml_file'); ?>
                    <?php }?>
				</div>                    
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo JText::_( "ZEFANIABIBLE_FIELD_XML_AUDIO_FILE_LOCATION" ); ?> :</div>
				<div class="controls">
                	<?php if($isNew){?>
						<?php
							$str_audio_path = $params->get('xmlAudioPath', 'media/com_zefaniabible/audio/');							
							$arr_file_list_audio = JFolder::files(JPATH_SITE.'/'.$str_audio_path,'.xml');							
							$str_file_list_audio = '';
							
							for($y = 0; $y < count($arr_file_list_audio); $y++)
							{
								$str_file_list_audio .= '<option value="'.$arr_file_list_audio[$y].'">'.$arr_file_list_audio[$y].'</option>'.PHP_EOL;
							}								
				
						?>                    
                        <div class="input-prepend input-append">
                            <div id="jform_xml_audio_url_icon" class="btn add-on icon-checkmark" onclick="toggleElement('jform_xml_audio_url','jform_xml_audio_url_list');"> </div>
                                <input name="jform[xml_audio_url]" id="jform_xml_audio_url" class="bible_input" value="<?php echo $this->item->xml_audio_url; ?>" type="text">
                        </div>
                        <br />
                            <div class="input-prepend input-append">
                                <div id="xml_audio_folder_icon" class="btn add-on icon-cancel" onclick="toggleElement('jform_xml_audio_url_list','jform_xml_audio_url');"> </div>
                                <select name="jform[xml_audio_url_list]" id="jform_xml_audio_url_list" class="bible_input" ><?php echo $str_file_list_audio; ?></select>
                            </div>
                            <div id="infoUpload2" class="intend">
                                <span id="btnUpload2"></span>
                                <button id="btnCancel2" type="button" onclick="cancelQueue(upload2);" class="ss-hide upload_button" disabled="disabled">Cancel</button>
                                <span id="audiopathinfo" class="pathinfo ss-hide hasTip" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO_TOOLTIP'); ?>">
                                    <?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO').' /'.trim($params->get('xmlAudioPath', 'media/com_zefaniabible/audio/'), '/').'/'; ?>
                                </span>
                        </div>
                    <?php }else{?>
                    	<?php echo $this->form->getInput('xml_audio_url'); ?>
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