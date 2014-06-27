<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniacomment
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


JHtml::addIncludePath(JPATH_ADMIN_ZEFANIABIBLE.'/helpers/html');
JHtml::_('behavior.tooltip');
//JHtml::_('behavior.multiselect');

$model		= $this->model;
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'a.ordering' && $listDirn != 'desc';
JDom::_('framework.sortablelist', array(
	'domId' => 'grid-zefaniacomment',
	'listOrder' => $listOrder,
	'listDirn' => $listDirn,
	'formId' => 'adminForm',
	'ctrl' => 'zefaniacomment',
	'proceedSaveOrderButton' => true,
));
?>
<div class="clearfix"></div>
<div class="">
	<table class='table' id='grid-zefaniacomment'>
		<thead>
			<tr>
				<?php if ($model->canSelect()): ?>
				<th>
					<?php echo JDom::_('html.form.input.checkbox', array(
						'dataKey' => 'checkall-toggle',
						'title' => JText::_('JGLOBAL_CHECK_ALL'),
						'selectors' => array(
							'onclick' => 'Joomla.checkAll(this);'
						)
					)); ?>
				</th>
				<?php endif; ?>

				<th>
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_TITLE", 'a.title', $listDirn, $listOrder ); ?>
				</th>

				<th style="text-align:center">
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_ALIAS", 'a.alias', $listDirn, $listOrder ); ?>
				</th>

				<th>
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_FULL_NAME", 'a.full_name', $listDirn, $listOrder ); ?>
				</th>

				<?php if ($model->canEditState()): ?>
				<th>
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_HEADING_ORDERING", 'a.ordering', $listDirn, $listOrder ); ?>
				</th>
				<?php endif; ?>

				<?php if ($model->canEditState()): ?>
				<th>
					<?php echo JText::_("ZEFANIABIBLE_FIELD_PUBLISH"); ?>
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
				<?php if ($model->canSelect()): ?>
				<td>
					<?php if ($row->params->get('access-edit') || $row->params->get('tag-checkedout')): ?>
						<?php echo JDom::_('html.grid.checkedout', array(
													'dataObject' => $row,
													'num' => $i
														));
						?>
					<?php endif; ?>
				</td>
				<?php endif; ?>

				<td>
					<?php echo JDom::_('html.fly', array(
						'commandAcl' => array('core.edit.own', 'core.edit'),
						'dataKey' => 'title',
						'dataObject' => $row,
						'num' => $i,
						'task' => 'zefaniacommentitems.edit'
					));?>
				</td>

				<td style="text-align:center">
					<?php echo JDom::_('html.fly', array(
						'commandAcl' => array('core.edit.own', 'core.edit'),
						'dataKey' => 'alias',
						'dataObject' => $row,
						'num' => $i,
						'task' => 'zefaniacommentitems.edit'
					));?>
				</td>

				<td>
					<?php echo JDom::_('html.fly', array(
						'commandAcl' => array('core.edit.own', 'core.edit'),
						'dataKey' => 'full_name',
						'dataObject' => $row,
						'num' => $i,
						'task' => 'zefaniacommentitems.edit'
					));?>
				</td>

				<?php if ($model->canEditState()): ?>
				<td>
					<?php echo JDom::_('html.grid.ordering', array(
						'aclAccess' => 'core.edit.state',
						'dataKey' => 'ordering',
						'dataObject' => $row,
						'enabled' => $saveOrder
					));?>
				</td>
				<?php endif; ?>

				<?php if ($model->canEditState()): ?>
				<td>
					<?php echo JDom::_('html.grid.publish', array(
						'ctrl' => 'zefaniacomment',
						'dataKey' => 'published',
						'dataObject' => $row,
						'num' => $i
					));?>
				</td>
				<?php endif; ?>
			</tr>
			<?php
			$k = 1 - $k;
		endfor;
		?>
		</tbody>
	</table>
</div>
