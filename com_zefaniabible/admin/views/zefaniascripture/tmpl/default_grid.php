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


?>


<div class="grid_wrapper">
	<table id='grid' class='adminlist' cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>

			<?php if ($this->access->get('core.edit.own') || $this->access->get('core.edit')): ?>
            <th width="5%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>
			<?php endif; ?>

			<th width="20%">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_BIBLE_VERSION", '_book_name_.bible_book_name', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>

			<th width="15%" style="text-align:center">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_VIEW_SCRIPTURE", 'a.book_id', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th> 

			<?php if ($this->access->get('core.edit.state') || $this->access->get('core.view.own')): ?>
			<th width="55%">
				<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_VIEW_SCRIPTURE_VERSE", 'a.verse', $this->state->get('list.direction'), $this->state->get('list.ordering') ); ?>
			</th>
			<?php endif; ?>
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
				<?php  echo JDom::_('html.fly', array(
												'dataKey' => '_bible_version_title',
												'dataObject' => $row,
												'href' => "javascript:listItemTask('cb" . $i . "', 'edit')"
												));
				?>
			</td>
            <td>
				<?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$row->book_id)." ".$row->chapter_id.":".$row->verse_id;	?>
			</td>

            <td style="text-align:left">
				<?php  echo JDom::_('html.fly', array(
												'dataKey' => 'verse',
												'dataObject' => $row,
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
