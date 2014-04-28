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



$isNew		= ($this->zefaniadictionaryitem->id < 1);
$actionText = $isNew ? JText::_( "ZEFANIABIBLE_NEW" ) : JText::_( "ZEFANIABIBLE_EDIT" );
$params	= JComponentHelper::getParams( 'com_zefaniabible' );
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
				<input id="name" class="inputbox " type="text" size="32" value="<?php echo $this->zefaniadictionaryitem->name; ?>" name="name" onblur="validateFields('name','<?php echo JText::_( "ZEFANIABIBLE_FIELD_TITLE" ); ?>')" />
			</td> 
            <td>
            	<div id="name_valid"></div>
            </td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="alias">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_ALIAS" ); ?> :
				</label>
			</td>
			<td> 
            	<input id="alias" class="inputbox " type="text" size="32" maxlength="5" value="<?php echo $this->zefaniadictionaryitem->alias; ?>" name="alias" onblur="validateFields('alias','<?php echo JText::_( "ZEFANIABIBLE_FIELD_ALIAS" ); ?>')"> 
			</td>
            <td>
            	<div id="alias_valid" ></div>
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
            	<input type="radio" name='publish' value="0" required="required" <?php if(!$this->zefaniadictionaryitem->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JNO" ); ?>
            	<input type="radio" name='publish' value="1" required="required" <?php if($this->zefaniadictionaryitem->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JYES" ); ?>
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
												'dataObject' => $this->zefaniadictionaryitem,
												'items' => $this->lists["ordering"],
												'labelKey' => 'name',
												'aclAccess' => '',
												'validatorHandler' => "numeric"
												)); 						
				?>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<td align="right" class="key" valign="top">
				<label for="file_location">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_XML_DICTIONARY_FILE_LOCATION" ); ?> : 
				</label>
			</td>
			<td valign="top">
            	<div style="float:left">
					<?php if($isNew){ ?>
						<?php 
                            $str_dict_path = $params->get('xmlDictionaryPath', 'media/com_zefaniabible/dictionary/');
                            $str_file_list = '';
                            foreach($this->arr_file_list as $obj_file_list)
                            {
                                $str_file_list = $str_file_list .'<option value="'.$obj_file_list.'">'.$obj_file_list.'</option>';
                            }
                        ?>                    
						<div class="input-prepend input-append">
                            <div id="xml_file_url_icon" class="btn add-on icon-checkmark" onclick="toggleElement('xml_file_url','xml_file_folder');"> </div>
                                <input name="xml_file_url" id="xml_file_url_text" class="bible_input" value="<?php echo $this->zefaniadictionaryitem->xml_file_url; ?>" type="text">
                            </div>
                            <br />
                            <div class="input-prepend input-append">
                            <div id="xml_file_folder_icon" class="btn add-on icon-cancel" onclick="toggleElement('xml_file_folder','xml_file_url');"> </div>
                                <select name="xml_file_folder" id="xml_file_folder_text" class="bible_input" disabled="disabled"><?php echo $str_file_list; ?></select>
                            </div>
                        </div>               
                    <?php }else{?>
                    	<input name="xml_file_url" id="xml_file_url" value="<?php echo $this->zefaniadictionaryitem->xml_file_url; ?>" size="75"  disabled="disabled" type="text">
                     <?php }?>
                </div>                                
			</td>
        <?php if($isNew){ ?>
        <tr>
        	<td>            	
            </td>
        	<td>
				<div id="infoUpload1" class="intend">
					<span id="btnUpload1"></span>
					<button id="btnCancel1" type="button" onclick="cancelQueue(upload1);" class="ss-hide upload_button" disabled="disabled">Cancel</button>
					<span id="biblepathinfo" class="pathinfo ss-hide hasTip" title="<?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO_TOOLTIP'); ?>">
							<?php echo JText::_('ZEFANIABIBLE_FIELD_XML_UPLOAD_UPLOADINFO').' /'.trim($params->get('xmlDictionaryPath', 'media/com_zefaniabible/dictionary/'), '/').'/'; ?>
                    </span>
				</div> 
            </td>
        </tr>
        <?php }?>
		</tr>
	</table>
</fieldset>
