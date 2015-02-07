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
$arr_plan_list = $mdl_default->_buildQuery_reading_plan_list_All();
$arr_bible_list = $mdl_default->_buildQuery_Bibles_Names_All();

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

<form action="<?php echo JRoute::_('index.php?option=com_zefaniabible&view=zefaniauser'); ?>" method="post" name="adminForm" id="adminForm">

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


	<table class="table table-striped" id="zefaniabible_zefaniauserList">
		<thead>
			<tr>
				
				<!-- item checkbox -->
				<th width="1%" class="hidden-phone">
					<?php echo JHtml::_('grid.checkall'); ?>
				</th>
				
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_NAME'), 'a.user_name', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_READING_PLAN'), 'a.plan', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_BIBLE_VERSION'), 'a.bible_version', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_USER_ID'), 'a.user_id', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_EMAIL'), 'a.email', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_SEND_READING_PLAN_EMAIL'), 'a.send_reading_plan_email', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_SEND_VERSE_OF_DAY_EMAIL'), 'a.send_verse_of_day_email', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_READING_START_DATE'), 'a.reading_start_date', $listDirn, $listOrder) ?>
				</th>
				<th class="nowrap left">
					<?php echo JHtml::_('searchtools.sort', JText::_('ZEFANIABIBLE_FIELD_ID'), 'a.id', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
				
		<tbody>
		
		<?php foreach ($this->items as $i => $item) :
		$canEdit	= $user->authorise('core.edit',       'com_zefaniabible.zefaniabible_zefaniauser.'.$item->id);
		$canCheckin	= $user->authorise('core.manage',     'com_checkin');
		$canEditOwn	= $user->authorise('core.edit.own',   'com_zefaniabible.zefaniabible_zefaniauser.'.$item->id);
		$canChange	= $user->authorise('core.edit.state', 'com_zefaniabible.zefaniabible_zefaniauser.'.$item->id) && $canCheckin;
		?>
		
			<tr class="row<?php echo $i % 2; ?>">
				
				<!-- item checkbox -->
				<td class="center"><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
				
				<!-- item main field -->
				<td class="nowrap has-context">
						<div class="pull-left">
							<?php if ($canEdit || $canEditOwn) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_zefaniabible&task=zefaniauseritem.edit&id='.(int) $item->id); ?>">
								<?php echo $this->escape($item->user_name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->user_name); ?>
							<?php endif; ?>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'zefaniauseritem.');
								if (!isset($this->items[0]->published) || $this->state->get('filter.published') == -2) :
									JHtml::_('dropdown.addCustomItem', JText::_('JTOOLBAR_DELETE'), 'javascript:void(0)', "onclick=\"contextAction('cb$i', 'zefaniauser.delete')\"");
								endif;
								JHtml::_('dropdown.divider');

								// render dropdown list
								echo JHtml::_('dropdown.render');
							?>
						</div>
				</td>
				<?php 
					$str_plan_name = '';
					foreach ($arr_plan_list as $arr_plan)
					{
						if($arr_plan->id == $item->plan)
						{
							$str_plan_name = $arr_plan->name;
						}
					}
					$str_bible_name = '';
					foreach ($arr_bible_list as $arr_bible)
					{
						if($arr_bible->id == $item->bible_version)
						{
							$str_bible_name = $arr_bible->bible_name;
						}
					}					
				?>
				<td class="left"><?php echo $this->escape($str_plan_name); ?></td>
				<td class="left"><?php echo $this->escape($str_bible_name); ?></td>
				<td class="left"><?php echo $this->escape($item->user_id); ?></td>
				<td class="left"><?php echo $this->escape($item->email); ?></td>
				<td class="left" style="text-align:center">
	                <?php echo JHtml::_('jgrid.published', $item->send_reading_plan_email, $i, 'zefaniauser.', false); ?>
				</td>
				<td class="left" style="text-align:center">
	                <?php echo JHtml::_('jgrid.published', $item->send_verse_of_day_email, $i, 'zefaniauser.', false); ?>
                </td>
				<td class="left"><?php echo $this->escape($item->reading_start_date); ?></td>
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