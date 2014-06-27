<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Cpanel
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
<?php /* TODO : REMOVE ME */
	echo JDom::_('dev.todo', array(
		'message' => '<strong>TODO</strong> : Edit this template and remove me.'
	));

?>
<h2><?php echo $this->title;?></h2>
<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm">
	<div>
		<div>
			<?php echo JDom::_('html.menu.cpanel', array(
				'list' => $this->menu
			)); ?>
			<div class="clearfix"></div>
		</div>
	</div>


	<?php 
		$jinput = JFactory::getApplication()->input;
		echo JDom::_('html.form.footer', array(
		'values' => array(
					'view' => $jinput->get('view', 'cpanel'),
					'layout' => $jinput->get('layout', 'default'),

				)));
	?>
</form>
<?php /* TODO : REMOVE ME */
	echo JDom::_('dev.todo', array(
		'align' => 'left',
		'file' => __FILE__
	));

?>
