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

defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript" type="text/javascript">
<!--


function resetFilters()
{
	if (typeof(jQuery) != 'undefined')
	{
		jQuery('.filters :input').val('');

	/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
		jQuery('#filter_order').val('');
		jQuery('#filter_orderDir').val('');
	*/
		document.adminForm.submit();
		return;
	}

//Deprecated
	if ($('filter_send_reading_plan_email') != null)
	    $('filter_send_reading_plan_email').value='';
	if ($('filter_reading_start_date') != null)
	    $('filter_reading_start_date').value='';
	if ($('filter_search') != null)
	    $('filter_search').value='';


/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
	if ($('filter_order') != null)
	    $('filter_order').value='';
	if ($('filter_orderDir') != null)
	    $('filter_orderDir').value='';
*/

	document.adminForm.submit();
}

-->
</script>


<fieldset id="filters" class="filters">
	<legend><?php echo JText::_( "JSEARCH_FILTER_LABEL" ); ?></legend>
	<div style="float:right;">
		<div style="float:left">
			<!-- SEARCH : filter_search : search on User Name + Plan + Bible Version +   -->
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('ZEFANIABIBLE_SEARCH');?></label>
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('ZEFANIABIBLE_SEARCH'); ?>" id="filter_search" value="<?php echo $this->filters['search']->value; ?>" title="<?php echo JText::_('ZEFANIABIBLE_SEARCH'); ?>" />
			</div>      
		</div>
		<div style="float:left">
				<div class="btn-group pull-left hidden-phone">
					<button class="btn tip" onclick="this.form.submit();"><i class="icon-search"></i></button>
					<button class="btn tip" onclick="resetFilters()"><i class="icon-remove"></i></button>
				</div>
		</div>
	</div>

	<div style="float:left;"> 
    	<div style="float:left;">  
						<?php
						$arr_bible_version = array();
						foreach($this->arr_Bibles_versions as $ojb_bible_version)
						{
							$arr_bible_version[] = array("value" => $ojb_bible_version->bible_name, 'text'=>$ojb_bible_version->bible_name);
						}		
						?>	                    
                    <select name="filter_bible_name" id="filter_bible_name" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSION');?></option>
                        <?php  echo JHtml::_('select.options', $arr_bible_version, 'value', 'text', $this->state->get('filter.bible_name'));?>
                    </select> 
		</div>
        <div style="float:left;">  
                        <?php
							$arr_plan = array();
							$arr_plan[] = array("value" => null, 'text'=>JText::_( "ZEFANIABIBLE_JSEARCH_SELECT_PLAN" ));
							foreach($this->arr_Bibles_plans as $obj_plans)
							{
								$arr_plan[] = array("value" => $obj_plans->name, 'text'=>$obj_plans->name);
							}
						?>
                        <select name="filter_plan_name" id="filter_plan_name" class="inputbox" onchange="this.form.submit()">
	                        <?php echo JHtml::_('select.options', $arr_plan, 'value', 'text', $this->state->get('filter.plan_name'));?>						
						</select> 
		</div>
        <div style="float:left;">
						<?php
						$arr_send_reading = array();
						$arr_send_reading[] = array("value" => null, 'text'=>JText::_( "ZEFANIABIBLE_FILTER_NULL_SEND_READING_PLAN_EMAIL" ));
						$arr_send_reading[] = array("value" => '0', 'text'=>JText::_( "JNO" ));
						$arr_send_reading[] = array("value" => '1', 'text'=>JText::_( "JYES" ));

						echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_send_reading_plan_email',
											'dataValue' => $this->filters['send_reading_plan_email']->value,
											'list' => $arr_send_reading,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));
						?>
		</div>
        <div style="float:left;">  
						<?php
						$arr_send_verse = array();
						$arr_send_verse[] = array("value" => null, 'text'=>JText::_( "ZEFANIABIBLE_FILTER_NULL_SEND_VERSE_OF_DAY_EMAIL" ));
						$arr_send_verse[] = array("value" => '0', 'text'=>JText::_( "JNO" ));
						$arr_send_verse[] = array("value" => '1', 'text'=>JText::_( "JYES" ));

						echo JDom::_('html.form.input.select', array(
											'dataKey' => 'filter_send_verse_of_day_email',
											'dataValue' => $this->filters['send_verse_of_day_email']->value,
											'list' => $arr_send_verse,
											'listKey' => 'value',
											'labelKey' => 'text',
											'submitEventName' => 'onchange'
												));
						?>
		</div>
        <div style="float:left;">                          
						<?php echo JDom::_('html.form.input.calendar', array(
											'dataKey' => 'filter_reading_start_date',
											'dataValue' => $this->filters['reading_start_date']->value,
											'submitEventName' => 'onchange',
											'styles' => array('width' => '80px'),
											'dateFormat' => '%Y-%m-%d'
												));
						?>
		</div>
        <div clear='all'></div>
	</div>
	<div clear='all'></div>
</fieldset>
