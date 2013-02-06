<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniacomment
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



$isNew		= ($this->zefaniacommentitems->id < 1);
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
												'dataKey' => 'title',
												'dataObject' => $this->zefaniacommentitems,
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
												'dataObject' => $this->zefaniacommentitems,
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
												'dataKey' => 'full_name',
												'dataObject' => $this->zefaniacommentitems,
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
            	<input type="radio" name='publish' value="0" required="required" <?php if(!$this->zefaniacommentitems->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JNO" ); ?>
            	<input type="radio" name='publish' value="1" required="required" <?php if($this->zefaniacommentitems->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JYES" ); ?>
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
												'dataObject' => $this->zefaniacommentitems,
												'items' => $this->lists["ordering"],
												'labelKey' => 'title',
												'aclAccess' => '',
												'validatorHandler' => "numeric"
												)); ?>
			</td>
		</tr>

		<?php endif; ?>
		<tr>
			<td align="right" class="key">
				<label for="file_location">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_FILE_LOCATION" ); ?> :
				</label>
			</td>
			<td>
            <?php 
			$params = JComponentHelper::getParams( 'com_zefaniabible' );
			$str_commentary_path = $params->get('xmlCommentaryPath');
			?>
            	<select name="file_location" <?php if(!$isNew){?>disabled="disabled"<?php }?>> 
                	<option value=""><?php echo JText::_('ZEFANIABIBLE_FIELD_NULL_SELECT_COMMENTARY');?></option>
                	<option value="<?php echo '/'.$str_commentary_path.'GillEn/GillEn.xml'?>" <?php if($this->zefaniacommentitems->file_location == '/'.$str_commentary_path.'GillEn/GillEn.xml'){?>selected<?php }?>><?php echo JText::_('ZEFANIABIBLE_FIELD_COMMENTARY_GILLEN')?></option>
                    <option value="<?php echo '/'.$str_commentary_path.'KDEn/KDEn.xml'?>" <?php if($this->zefaniacommentitems->file_location == '/'.$str_commentary_path.'KDEn/KDEn.xml'){?>selected<?php }?>><?php echo JText::_('ZEFANIABIBLE_FIELD_COMMENTARY_KDEN')?></option>
                    <option value="<?php echo '/'.$str_commentary_path.'MHCEn/MHCEn.xml'?>" <?php if($this->zefaniacommentitems->file_location == '/'.$str_commentary_path.'MHCEn/MHCEn.xml'){?>selected<?php }?>><?php echo JText::_('ZEFANIABIBLE_FIELD_COMMENTARY_MHCEN')?></option>
                </select>
                <?php echo JText::_('ZEFANIABIBLE_FIELD_COMMENTARY_NOTE');?>
			</td>
		</tr>


	</table>
</fieldset>
