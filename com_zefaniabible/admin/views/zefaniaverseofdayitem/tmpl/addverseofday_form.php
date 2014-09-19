<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniaverseofday
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



$isNew		= ($this->zefaniaverseofdayitem->id < 1);
$actionText = $isNew ? JText::_( "ZEFANIABIBLE_NEW" ) : JText::_( "ZEFANIABIBLE_EDIT" );
?>

<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="book_name">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_BOOK_NAME" ); ?> :
				</label>
			</td>
			<td>
            
				<select aria-invalid="false" id="book_name" name="book_name" class="inputbox">
					<option value=""><?php echo JText::_('ZEFANIABIBLE_JSEARCH_SELECT_BOOK_ID'); ?></option>
                        <optgroup id="oldTest" label="<?php echo JText::_('ZEFANIABIBLE_BIBLE_OLD_TEST');?>">
                        <?php 
                            for($x=1; $x<=66; $x++)
                            {
                                if($x== $this->zefaniaverseofdayitem->book_name )
                                {
                                    echo '<option value="'.$x.'" selected>'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';
                                }
                                else
                                {
                                    echo '<option value="'.$x.'">'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';
                                }
								if($x == 40)
								{
									echo '</optgroup><optgroup id="newTest" label="'.JText::_('ZEFANIABIBLE_BIBLE_NEW_TEST').'">';
								}								
                            }
                        ?>
                        </optgroup>
                </select>                  
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="chapter_number">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_CHAPTER_NUMBER" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'chapter_number',
												'dataObject' => $this->zefaniaverseofdayitem,
												'size' => "10",
												'validatorHandler' => "numeric"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="begin_verse">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_BEGIN_VERSE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'begin_verse',
												'dataObject' => $this->zefaniaverseofdayitem,
												'size' => "10",
												'validatorHandler' => "numeric"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="end_verse">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_END_VERSE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'end_verse',
												'dataObject' => $this->zefaniaverseofdayitem,
												'size' => "10",
												'validatorHandler' => "numeric"
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
            	<input type="radio" name='publish' value="0" required="required" <?php if(!$this->zefaniaverseofdayitem->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JNO" ); ?>
            	<input type="radio" name='publish' value="1" required="required" <?php if($this->zefaniaverseofdayitem->publish){?>checked="checked" <?php } ?>/><?php echo JText::_( "JYES" ); ?>
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
												'dataObject' => $this->zefaniaverseofdayitem,
												'items' => $this->lists["ordering"],
												'labelKey' => 'book_name',
												'aclAccess' => '',
												'validatorHandler' => "numeric"
												)); ?>
			</td>
		</tr>

		<?php endif; ?>


	</table>
</fieldset>
