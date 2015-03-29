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

require_once(JPATH_COMPONENT_SITE.'/models/default.php');
require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
$mdl_default 	= new ZefaniabibleModelDefault;
$mdl_common 	= new ZefaniabibleCommonHelper;

$int_verses = 0;
$int_verses = $mdl_default->fnc_count_bible_verses($this->item->id);
$max_aprox_verses = 31102;
$dbl_percentage = number_format(($int_verses / $max_aprox_verses), 2)*100;
?>
<div style="margin-top:20px;">

	<div style="text-align:center"><?php echo number_format($int_verses);?>/<?php echo number_format($max_aprox_verses);?></div>
    <div class="progress">
        <div class="bar" style="width: <?php echo $dbl_percentage;?>%;"><?php echo $dbl_percentage;?>%</div>
    </div>
	<div style="text-align:center">Note data refreshes every 5 seconds.</div>    
</div>
<script>
var max_verses = <?php echo $max_aprox_verses?>;
var verses = <?php echo $int_verses; ?>;
if(verses < max_verses)
{
	setTimeout(function(){
	   window.location.reload(1);
	}, 5000);
}
</script>
