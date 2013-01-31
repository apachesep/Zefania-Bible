<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniaverseofday
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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniaverseofday
 *
 */
class ZefaniabibleViewZefaniaverseofdayitem extends JViewLegacy
{
	function display($tpl = null)
	{
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'addverseofday':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_addverseofday($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		//$access = ZefaniabibleHelper::getACL();
		$mdl_access =  new ZefaniabibleHelper;
		$access = $mdl_access->getACL();

		$model	= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'addverseofday');

		$document	= JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_LAYOUT_ADD_VERSE_OF_DAY") . $document->titleSuffix;


		//Form validator
		JHTML::_('behavior.formvalidation');


		$lists = array();

		//get the zefaniaverseofdayitem
		$zefaniaverseofdayitem	= $model->getItem();
		$isNew		= ($zefaniaverseofdayitem->id < 1);

		//For security, execute here a redirection if not authorized to enter a form
		if (($isNew && !$access->get('core.create'))
		|| (!$isNew && !$zefaniaverseofdayitem->params->get('access-edit')))
		{
				JError::raiseWarning(403, JText::sprintf( "JERROR_ALERTNOAUTHOR") );
				ZefaniabibleHelper::redirectBack();
		}


		$model_book_name = JModelLegacy::getInstance('zefaniabiblebooknames', 'ZefaniabibleModel');
		$model_book_name->addGroupBy("a.ordering");
		$lists['fk']['book_name'] = $model_book_name->getItems();



		//Ordering
		$orderModel = JModelLegacy::getInstance('Zefaniaverseofday', 'ZefaniabibleModel');
		$lists["ordering"] = $orderModel->getItems();

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = JToolBar::getInstance('toolbar');
		if (!$isNew && ($access->get('core.delete') || $zefaniaverseofdayitem->params->get('access-delete')))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", false);
		if ($access->get('core.edit') || ($isNew && $access->get('core.create') || $access->get('core.edit.own')))
			$bar->appendButton( 'Standard', "save", "JTOOLBAR_SAVE", "save", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "apply", "JTOOLBAR_APPLY", "apply", false);
		$bar->appendButton( 'Standard', "cancel", "JTOOLBAR_CANCEL", "cancel", false, false );




		$config	= JComponentHelper::getParams( 'com_zefaniabible' );

		JRequest::setVar( 'hidemainmenu', true );

		$user = JFactory::getUser();
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('zefaniaverseofdayitem',		$zefaniaverseofdayitem);
		$this->assignRef('config',		$config);
		$this->assignRef('isNew',		$isNew);

		parent::display($tpl);
	}




}