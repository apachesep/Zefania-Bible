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


$fieldSets = $this->form->getFieldsets();
?>
<?php $fieldSet = $this->form->getFieldset('adduser.form');?>
<fieldset class="fieldsform form-horizontal">

	<?php
	// JForms dynamic initialization (Cook Self Service proposal)
	$fieldSet['jform_bible_version']->jdomOptions = array(
			'list' => $this->lists['fk']['bible_version']
		);
	$fieldSet['jform_plan']->jdomOptions = array(
			'list' => $this->lists['fk']['plan']
		);
	$fieldSet['jform_user_id']->jdomOptions = array(
			'list' => $this->lists['fk']['user_id']
		);
	echo $this->renderFieldset($fieldSet);
	?>
</fieldset>
