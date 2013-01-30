<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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



$isNew		= ($this->zefaniauseritem->id < 1);
$actionText = $isNew ? JText::_( "ZEFANIABIBLE_NEW" ) : JText::_( "ZEFANIABIBLE_EDIT" );
?>

<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="user_name">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_USER_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'user_name',
												'dataObject' => $this->zefaniauseritem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="email">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_EMAIL" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'email',
												'dataObject' => $this->zefaniauseritem,
												'size' => "32",
												'validatorHandler' => "email"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="bible_version">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_BIBLE_VERSION" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'bible_version',
												'dataObject' => $this->zefaniauseritem,
												'list' => $this->lists['fk']['bible_version'],
												'listKey' => 'id',
												'labelKey' => 'bible_name',
												'nullLabel' => "ZEFANIABIBLE_JSEARCH_SELECT_BIBLE_VERSION"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="plan">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_READING_PLAN" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'plan',
												'dataObject' => $this->zefaniauseritem,
												'list' => $this->lists['fk']['plan'],
												'listKey' => 'id',
												'labelKey' => 'name',
												'nullLabel' => "ZEFANIABIBLE_JSEARCH_SELECT_PLAN"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="send_reading_plan_email">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_SEND_READING_PLAN_EMAIL" ); ?> :
				</label>
			</td>
			<td>
            	<input type="radio" name='send_reading_plan_email' value="0" required="required" <?php if(!$this->zefaniauseritem->send_reading_plan_email){?>checked="checked" <?php } ?>/><?php echo JText::_( "JNO" ); ?>
            	<input type="radio" name='send_reading_plan_email' value="1" required="required" <?php if($this->zefaniauseritem->send_reading_plan_email){?>checked="checked" <?php } ?>/><?php echo JText::_( "JYES" ); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="send_verse_of_day_email">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_SEND_VERSE_OF_DAY_EMAIL" ); ?> :
				</label>
			</td>
			<td>
            	<input type="radio" name='send_verse_of_day_email' value="0" required="required" <?php if(!$this->zefaniauseritem->send_verse_of_day_email){?>checked="checked" <?php } ?>/><?php echo JText::_( "JNO" ); ?>
            	<input type="radio" name='send_verse_of_day_email' value="1" required="required" <?php if($this->zefaniauseritem->send_verse_of_day_email){?>checked="checked" <?php } ?>/><?php echo JText::_( "JYES" ); ?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="reading_start_date">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_READING_START_DATE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.calendar', array(
												'dataKey' => 'reading_start_date',
												'dataObject' => $this->zefaniauseritem,
												'dateFormat' => "%Y-%m-%d"
												));

				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="user_id">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_JOOMLA_USER" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'user_id',
												'dataObject' => $this->zefaniauseritem,
												'list' => $this->lists['fk']['user_id'],
												'listKey' => 'id',
												'labelKey' => 'name',
												'nullLabel' => "ZEFANIABIBLE_JSEARCH_SELECT_USER_ID"
												));
				?>
			</td>
		</tr>


	</table>
</fieldset>
