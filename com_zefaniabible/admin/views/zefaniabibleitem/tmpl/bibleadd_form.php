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



$isNew		= ($this->zefaniabibleitem->id < 1);
$actionText = $isNew ? JText::_( "ZEFANIABIBLE_NEW" ) : JText::_( "ZEFANIABIBLE_EDIT" );
?>

<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="title">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_TITLE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'bible_name',
												'dataObject' => $this->zefaniabibleitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="alias">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_ALIAS" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'alias',
												'dataObject' => $this->zefaniabibleitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="full_name">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_FULL_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'desc',
												'dataObject' => $this->zefaniabibleitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<?php if ($this->access->get('core.edit.state')): ?>
		<tr>
			<td align="right" class="key">
				<label for="publish">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_PUBLISH" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.bool', array(
												'dataKey' => 'publish',
												'dataObject' => $this->zefaniabibleitem,
												'aclAccess' => 'core.edit.state'
												));
				?>
			</td>
		</tr>

		<?php endif; ?>
		<?php if ($this->access->get('core.edit') || $this->access->get('core.edit.state')): ?>
		<tr>
			<td align="right" class="key">
				<label for="ordering">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_ORDERING" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.ordering', array(
												'dataKey' => 'ordering',
												'dataObject' => $this->zefaniabibleitem,
												'items' => $this->lists["ordering"],
												'labelKey' => 'bible_name',
												'aclAccess' => '',
												'validatorHandler' => "numeric"
												)); ?>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<td align="right" class="key">
				<label for="file_location">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_XML_BIBLE_FILE_LOCATION" ); ?> : 
				</label>
			</td>
			<td>
            	<div style="float:left">
					<?php 
						$params = &JComponentHelper::getParams( 'com_zefaniabible' );
						$str_xml_bibles_path = $params->get('xmlBiblesPath', 'media/com_zefaniabible/bibles/');
						echo JURI::root().$str_xml_bibles_path;
					?>
                    <input name="xml_file_url" id="xml_file_url" value="<?php echo $this->zefaniabibleitem->xml_file_url; ?>" size="32" type="text">
                </div>                                
			</td>
        <tr>
        	<td></td>
        	<td>
				<div id="swfuploader">
                    <fieldset class="adminform">
						<div class="fieldset flash" id="fsUploadProgress">
                        	<span class="legend"><?php echo JText::_("ZEFANIABIBLE_FIELD_XML_UPLOAD_QUEUE");?></span>
	                        <span id="divStatus"><?php echo JText::_("ZEFANIABIBLE_FIELD_XML_UPLOAD_STATUS");?></span>                            
						</div> 
                        <div>
                        	<span id="spanButtonPlaceHolder"></span>
                            <input id="btnCancel" type="button" value="<?php echo JText::_("ZEFANIABIBLE_FIELD_XML_UPLOAD_CANCEL");?>" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px" />
						</div>
					</fieldset> 
                </div>             
            </td>
        </tr>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="xml_audio_file_location">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_XML_AUDIO_FILE_LOCATION" ); ?> :
				</label>
			</td>
			<td>
				<?php 
				
				$str_xml_audio_path = $params->get('xmlAudioPath', 'media/com_zefaniabible/audio/');				
				echo JURI::root().$str_xml_audio_path;
				 echo JDom::_('html.form.input.text', array(
												'dataKey' => 'xml_audio_url',
												'dataObject' => $this->zefaniabibleitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>
