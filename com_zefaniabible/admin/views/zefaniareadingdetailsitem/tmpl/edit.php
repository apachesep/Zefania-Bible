<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'zefaniareadingdetailsitem.cancel' || document.formvalidator.isValid(document.id('zefaniareadingdetailsitem-form')))
		{
			Joomla.submitform(task, document.getElementById('zefaniareadingdetailsitem-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_zefaniabible&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="zefaniareadingdetailsitem-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('ZEFANIABIBLE_LAYOUT_DETAILS'), $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">	
            <div class="control-group">
                <div class="control-label"><?php echo $this->form->getLabel('plan'); ?></div>
                <div class="controls"><?php echo $this->form->getInput('plan'); ?></div>
            </div>                		
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('book_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('book_id'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('begin_chapter'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('begin_chapter'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('begin_verse'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('begin_verse'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end_chapter'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end_chapter'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end_verse'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end_verse'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('day_number'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('day_number'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>