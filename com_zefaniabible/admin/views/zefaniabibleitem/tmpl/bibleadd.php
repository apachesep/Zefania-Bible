<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
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

<?php ZefaniabibleHelper::headerDeclarations(); ?>





<?php	JToolBarHelper::title(JText::_("ZEFANIABIBLE_LAYOUT_ADD_BIBLE"), 'zefaniabible_zefaniabible' );?>

<script language="javascript" type="text/javascript">
	var FIELDS_VALIDATOR_REQUIRED = "<?php echo JText::_("ZEFANIABIBLE_FIELDS_VALIDATOR_REQUIRED_FIELD_LABEL"); ?>";
	var FIELDS_VALIDATOR_INCORRECT = "<?php echo JText::_("ZEFANIABIBLE_FIELDS_VALIDATOR_INCORRECT_FIELD_LABEL"); ?>";
</script>



<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		switch(pressbutton)
		{
			case 'cancel':
			case 'delete':
				return Joomla.submitform(pressbutton);
				break;
		}

		document.formvalidator.submitform(document.adminForm, pressbutton, function(pressbutton){
			return Joomla.submitform(pressbutton);
		});
	   	return false;
	}
</script>

<form action="<?php echo(JRoute::_("index.php")); ?>" method="post" name="adminForm" id="adminForm" class='form-validate'>




	<div>
		<?php echo $this->loadTemplate('form'); ?>
	</div>










	<?php echo JDom::_('html.form.footer', array(
		'dataObject' => $this->zefaniabibleitem,
		'values' => array(
				'option' => "com_zefaniabible",
				'view' => "zefaniabibleitem",
				'layout' => "bibleadd"
				)));
	?>

</form>