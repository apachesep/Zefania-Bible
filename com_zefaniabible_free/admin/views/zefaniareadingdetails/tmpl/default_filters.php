<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
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
        				<?php
							$choices = array();
							$choices[] = array("value" => null, 'text'=>JText::_( "ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE" ));
							for($x = 1; $x< 66; $x++)
							{
								$choices[] = array("value" => $x, 'text'=>JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x));
	
							}
						?>
                        <select name="filter_book_id" id="filter_book_id" class="inputbox" onchange="this.form.submit()">
	                        <?php echo JHtml::_('select.options', $choices, 'value', 'text', $this->state->get('filter.book_id'));?>						
						</select>
        				<?php
							$arr_day = array();
							$arr_day[] = array("value" => null, 'text'=>JText::_( "ZEFANIABIBLE_FILTER_NULL_SELECT_DAY" ));
							for($x = $this->int_min_day; $x<= $this->int_max_day; $x++)
							{
								$arr_day[] = array("value" => $x, 'text'=>$x);
	
							}
						?>
                        <select name="filter_day_number" id="filter_day_number" class="inputbox" onchange="this.form.submit()">
	                        <?php echo JHtml::_('select.options', $arr_day, 'value', 'text', $this->state->get('filter.day_number'));?>						
						</select>                        

					                               
	</div>
	<div clear='all'></div>
</fieldset>
