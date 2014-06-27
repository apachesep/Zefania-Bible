<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Users
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
?>
<div class="grid_wrapper">
	<table  class='table' id='grid-users'>
		<thead>
			<tr>
				<th>
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_NAME", 'a.name', $listDirn, $listOrder ); ?>
				</th>

				<th>
					<?php echo JHTML::_('grid.sort',  "ZEFANIABIBLE_FIELD_USERNAME", 'a.username', $listDirn, $listOrder ); ?>
				</th>

				<th width="10px">
					<?php echo JText::_("ZEFANIABIBLE_FIELD_ID"); ?>
				</th>
			</tr>
		</thead>
	
		<tbody>
			<?php
		//Get the name of the field to populate on return
		$modalObject = JFactory::getApplication()->input->get('object', null, 'cmd');

		$k = 0;
		for ($i=0, $n=count( $this->items ); $i < $n; $i++):
			$row = &$this->items[$i];
			?>

	
			<?php
			//Pickable rows
			//Receive the callback function
			$input = JFactory::getApplication()->input;
			$function	= $input->get('function', 'jSelectItem');
			//Prepare the params to send to the callback
			$pickValue = $row->id;
			$pickLabel = $this->escape(addslashes($row->name));
			$jsPick = "if (window.parent) window.parent.$function('$pickValue', '$pickLabel', '$modalObject');"
			?>
	
			<tr class="<?php echo "row$k"; ?> pickable-row"
				style="cursor:pointer"
				onclick="<?php echo $jsPick; ?>">

				<td>
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'name',
						'dataObject' => $row
					));?>
				</td>

				<td>
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'username',
						'dataObject' => $row
					));?>
				</td>

				<td width="10px">
					<?php echo JDom::_('html.fly', array(
						'dataKey' => 'id',
						'dataObject' => $row
					));?>
				</td>

			</tr>
			<?php
			$k = 1 - $k;

		endfor;
		?>
		</tbody>
	</table>
</div>
