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

require_once(JPATH_COMPONENT_SITE.'/models/default.php');
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_default 	= new ZefaniabibleModelDefault;
$mdl_common 	= new ZefaniabibleCommonHelper;

$params = JComponentHelper::getParams( 'com_zefaniabible' );
$str_primary_bible 	= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
$arr_verse = $mdl_default->fnc_make_verse($str_primary_bible,$this->item->book_name,$this->item->chapter_number,$this->item->begin_verse,$this->item->end_verse);
$str_scripture = $mdl_common->fnc_make_scripture_title($this->item->book_name, $this->item->chapter_number, $this->item->begin_verse, $this->item->chapter_number, $this->item->end_verse);
$str_verse = '';
$x = 1;

foreach ($arr_verse as $verse)
{	
	if($x%2)
	{
		$str_verse .= '<div class="row0">'.$verse->verse_id." ".$verse->verse.'<br ></div>'.PHP_EOL;
	}
	else
	{
		$str_verse .= '<div class="row1">'.$verse->verse_id." ".$verse->verse.'<br ></div>'.PHP_EOL;	
	}
	$x++;
}
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'zefaniaverseofdayitem.cancel' || document.formvalidator.isValid(document.id('zefaniaverseofdayitem-form')))
		{
			Joomla.submitform(task, document.getElementById('zefaniaverseofdayitem-form'));
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_zefaniabible&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="zefaniaverseofdayitem-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('ZEFANIABIBLE_LAYOUT_DETAILS'), $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">		
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('book_name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('book_name'); ?></div>
                    </div>                	
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('chapter_number'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('chapter_number'); ?></div>
                    </div>			
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('begin_verse'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('begin_verse'); ?></div>
                    </div>			
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('end_verse'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('end_verse'); ?></div>
                    </div>
                    <?php if($this->item->id > 0){?>
                        <div class="control-group">    
                            <div class="well well-lg"><b><?php echo $str_scripture;?></b><br/><?php echo $str_verse;?></div>
                        </div>
                    <?php }?>
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