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


?>


<div class="grid_wrapper">
	<table id='grid' class='adminlist' cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'NUM' ); ?>
			</th>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
            <th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<?php endif; ?>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_USER_NAME", 'a.user_name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_BIBLE_VERSION", '_bible_version_.bible_name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_READING_PLAN", '_plan_.name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th>
				<?php echo JText::_("ZEFANIABIBLE_FIELD_EMAIL"); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_READING_PLAN", 'a.send_reading_plan_email', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_VERSE_OF_DAY", 'a.send_verse_of_day_email', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_READING_START_DATE", 'a.reading_start_date', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>



		</tr>
	</thead>

	<tbody>
	<?php
	$k = 0;

	for ($i=0, $n=count( $this->items ); $i < $n; $i++):

		$row = &$this->items[$i];



		?>

		<tr class="<?php echo "row$k"; ?>">

			<td class='row_id'>
				<?php echo $this->pagination->getRowOffset( $i ); ?>
            </td>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
			<td>
				<?php echo JDom::_('html.grid.checkedout', array(
											'dataKey' => '',
											'dataObject' => $row,
											'num' => $i
												));
				?>

			</td>
			<?php endif; ?>

            <td style="text-align:center">

				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'user_name',
												'dataObject' => $row,
												'href' => "javascript:listItemTask('cb" . $i . "', 'edit')"
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_bible_version_title',
												'dataObject' => $row,
												'href' => "javascript:listItemTask('cb" . $i . "', 'edit')"
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.fly', array(
												'dataKey' => '_plan_name',
												'dataObject' => $row,
												'href' => "javascript:listItemTask('cb" . $i . "', 'edit')"
												));
				?>
			</td>

            <td>
				<?php echo JDom::_('html.fly', array(
												'dataKey' => 'email',
												'dataObject' => $row,
												'href' => "javascript:listItemTask('cb" . $i . "', 'edit')"
												));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.grid.bool', array(
										'dataKey' => 'send_reading_plan_email',
										'dataObject' => $row,
										'num' => $i,
										'togglable' => true,
										'commandAcl' => ($row->params->get('access-edit')?null:'core.edit')
											));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.grid.bool', array(
										'dataKey' => 'send_verse_of_day_email',
										'dataObject' => $row,
										'num' => $i,
										'togglable' => true,
										'commandAcl' => ($row->params->get('access-edit')?null:'core.edit')
											));
				?>
			</td>

            <td style="text-align:center">
				<?php echo JDom::_('html.grid.datetime', array(
										'dataKey' => 'reading_start_date',
										'dataObject' => $row,
										'dateFormat' => "%Y-%m-%d"
											));
				?>
			</td>



		</tr>
		<?php
		$k = 1 - $k;

	endfor;
	?>
	</tbody>
	</table>




</div>

<?php echo JDom::_('html.pagination', null, $this->pagination);?>


