<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder	= ($user->authorise('core.edit.state', 'com_test') && isset($this->items[0]->ordering));

require_once(JPATH_COMPONENT_SITE.'/models/default.php');
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_default 	= new ZefaniabibleModelDefault;
$mdl_common 	= new ZefaniabibleCommonHelper;
$arr_comment_list = $mdl_default->_buildQuery_Commentary_Names_All();
JError::raiseNotice('',JText::_('ZEFANIABIBLE_WARNING_MODIFY'));
?>

<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_zefaniabible&view=zefaniacommentdetail'); ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<!-- sidebar -->
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<!-- end sidebar -->
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

	<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
	?>
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>


	<table class="table table-striped" id="zefaniabible_comment_textList">
		<thead>
			<tr>
				
				<!-- item checkbox -->
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				<th class="nowrap left">
					<?php echo JText::_('ZEFANIABIBLE_VIEW_SCRIPTURE'); ?>
				</th>				
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('COM_ZEFANIABIBLE_COMMENTARY_LABEL'), 'a.bible_id', $listDirn, $listOrder) ?>
				</th>

				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_ID'), 'a.id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_zefaniabible.zefaniabible_comment_text.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin');
		$canEditOwn	= $user->authorise('core.edit.own',   'com_zefaniabible.zefaniabible_comment_text.'.$item->id);
		$canChange	= $user->authorise('core.edit.state', 'com_zefaniabible.zefaniabible_comment_text.'.$item->id) && $canCheckin;
		?>
		
			<tr class="row<?php echo $i % 2; ?>">
				
				<!-- item checkbox -->
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				
				<!-- item main field -->
				<td class="nowrap has-context">
						<div class="pull-left">
                <?php 
					$str_scripture = $mdl_common->fnc_make_scripture_title($item->book_id, $item->chapter_id, $item->verse_id, $item->chapter_id, 0);
				?>                        
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_zefaniabible&task=zefaniacommentdetailitem.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($str_scripture); ?></a>
							<?php else : ?>
								<?php echo $this->escape($str_scripture); ?>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'zefaniacommentdetailitem.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', 'zefaniacommentdetail.delete')\"");
								endif;
								JHtml::_('dropdown.divider');

								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>
				<?php 
					$str_comment_name = ''; 
					foreach ($arr_comment_list as $arr_comment)
					{
						if($arr_comment->id == $item->bible_id)
						{
							$str_comment_name = $arr_comment->title;
						}
					}				
				?>
				<td class="left"><?php echo $this->escape($str_comment_name); ?></td>
				<td class="left"><?php echo $this->escape($item->id); ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>	
	</table>
	<?php endif; ?>
	<?php echo $this->pagination->getListFooter(); ?>
	<?php //Load the batch processing form. ?>
	<?php echo $this->loadTemplate('batch'); ?>
	
	<div>
		<input type="hidden" name="task" value=" " />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

	</form>
    <?php 
			require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/credits.php');
			$mdl_credits = new ZefaniabibleCredits;
			$obj_player_one = $mdl_credits->fnc_credits();	
	?>    
</div>