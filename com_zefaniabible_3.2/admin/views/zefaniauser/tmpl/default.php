<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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


ZefaniabibleHelper::headerDeclarations();
//Load the formvalidator scripts requirements.
JDom::_('html.toolbar');
?>
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">
	<?php
	$compat = '1.6';
	$version = new JVersion();
	if ($version->isCompatible('3.0'))
		$compat = '3.0';
	?>

	<?php if ($compat == '3.0'): ?>
	<div class="row-fluid">
		<div id="sidebar" class="span2">
			<div class="sidebar-nav">
				<div>

					<!-- BRICK : menu -->
					<?php echo JDom::_('html.menu.submenu', array(
						'list' => $this->menu
					)); ?>
				</div>
				<div>

					<!-- BRICK : filters -->
					<hr class="hr-condensed">
					<div class="filter-select hidden-phone">
						<h4 class="page-header"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></h4>
						<?php echo $this->filters['filter_send_reading_plan_email']->input;?>
					</div>
				</div>
			</div>
		</div>
		<div id="contents" class="span10">
			<div>

				<!-- BRICK : toolbar_plur -->
				<?php echo $this->renderToolbar($this->items);?>
			</div>
			<div>

				<!-- BRICK : filters -->
				<div class="pull-left">
					<?php echo $this->filters['search_search']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['limit']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['directionTable']->input;?>
				</div>
				<div class="pull-right">
					<?php echo $this->filters['sortTable']->input;?>
				</div>
				<div class="clearfix"></div>

			</div>
			<div>

				<!-- BRICK : grid -->
				<?php echo $this->loadTemplate('grid'); ?>
			</div>
			<div>

				<!-- BRICK : pagination -->
				<?php echo $this->pagination->getListFooter(); ?>
			</div>
		</div>
	</div>
	<?php elseif ($compat == '1.6'): ?>
	<div>
		<div>

			<!-- BRICK : toolbar_plur -->
			<?php echo $this->renderToolbar($this->items);?>
		</div>
		<div>

			<!-- BRICK : filters -->
			<div class="pull-left">
				<?php echo $this->filters['filter_send_reading_plan_email']->input;?>
			</div>
			<div class="pull-right">
				<?php echo $this->filters['limit']->input;?>
			</div>
			<div class="pull-right">
				<?php echo $this->filters['directionTable']->input;?>
			</div>
			<div class="pull-right">
				<?php echo $this->filters['sortTable']->input;?>
			</div>
			<div class="clearfix"></div>
			<div class="pull-right">
				<?php echo $this->filters['search_search']->input;?>
			</div>

		</div>
		<div>

			<!-- BRICK : grid -->
			<?php echo $this->loadTemplate('grid'); ?>
		</div>
		<div>

			<!-- BRICK : pagination -->
			<?php echo $this->pagination->getListFooter(); ?>
		</div>
	</div>
	<?php endif; ?>


	<?php 
		$jinput = JFactory::getApplication()->input;
		echo JDom::_('html.form.footer', array(
		'values' => array(
					'view' => $jinput->get('view', 'zefaniauser'),
					'layout' => $jinput->get('layout', 'default'),
					'boxchecked' => '0',
					'filter_order' => $this->escape($this->state->get('list.ordering')),
					'filter_order_Dir' => $this->escape($this->state->get('list.direction'))
				)));
	?>
</form>
<?php /* TODO : REMOVE ME */
	echo JDom::_('dev.todo', array(
		'align' => 'left',
		'file' => __FILE__
	));

?>
