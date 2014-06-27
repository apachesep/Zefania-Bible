<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniacomment
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


$fieldSets = $this->form->getFieldsets();
?>
<?php $fieldSet = $this->form->getFieldset('commentaryadd.form');?>
<fieldset class="fieldsform form-horizontal">

	<?php
	// JForms dynamic initialization (Cook Self Service proposal)
	$fieldSet['jform_ordering']->jdomOptions = array(
			'items' => $this->lists['ordering'],
			'labelKey' => 'title'
		);
	$fieldSet['jform_file_location']->jdomOptions = array(
			'actions' => array('remove', 'thumbs', 'delete', 'trash')
		);
	echo $this->renderFieldset($fieldSet);
	?>
</fieldset>
