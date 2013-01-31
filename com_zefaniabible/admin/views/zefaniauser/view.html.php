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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniauser
 *
 */
class ZefaniabibleViewZefaniauser extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();

		$option	= JRequest::getCmd('option');
		$view	= JRequest::getCmd('view');
		$layout = $this->getLayout();



		switch($layout)
		{
			case 'default':

				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}

	}
	function display_default($tpl = null)
	{
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		//$access = ZefaniabibleHelper::getACL();
		$mdl_access =  new ZefaniabibleHelper;
		$access = $mdl_access->getACL();
		$state		= $this->get('State');

		$document	= JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_LAYOUT_USERS") . $document->titleSuffix;

		// Get data from the model
		$model 		= $this->getModel();
		$model->activeAll();
		$model->active('predefined', 'default');


		$mdl_bible_user = new ZefaniabibleModelZefaniauser;
		$arr_Bibles_plans =	$mdl_bible_user->_buildQuery_plans();		
		$arr_Bibles_versions =	$mdl_bible_user->_buildQuery_bible_versions();
		
		$items		= $model->getItems();

		$total		= $this->get( 'Total');
		$pagination = $this->get( 'Pagination' );

		// table ordering
		$lists['order'] = $model->getState('list.ordering');
		$lists['order_Dir'] = $model->getState('list.direction');

		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = JToolBar::getInstance('toolbar');
		if ($access->get('core.create'))
			$bar->appendButton( 'Standard', "new", "JTOOLBAR_NEW", "new", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "edit", "JTOOLBAR_EDIT", "edit", true);
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			JToolBarHelper::preferences( 'com_zefaniabible' );



		//Filters
		//Send Reading Plan Email
		$this->filters['send_reading_plan_email'] = new stdClass();
		$this->filters['send_reading_plan_email']->value = $model->getState("filter.send_reading_plan_email");

		//Send Verse of Day Email
		$this->filters['send_verse_of_day_email'] = new stdClass();
		$this->filters['send_verse_of_day_email']->value = $model->getState("filter.send_verse_of_day_email");
		
		//reading_start_date
		$this->filters['reading_start_date'] = new stdClass();
		$this->filters['reading_start_date']->value = $model->getState("filter.reading_start_date");

		//search : search on User Name + Plan + Bible Version + 
		$this->filters['search'] = new stdClass();
		$this->filters['search']->value = $model->getState("search.search");

		$config	= JComponentHelper::getParams( 'com_zefaniabible' );

		$user = JFactory::getUser();
		$this->assignRef('user',		$user);
		$this->assignRef('access',		$access);
		$this->assignRef('state',		$state);
		$this->assignRef('lists',		$lists);
		$this->assignRef('arr_Bibles_plans',		$arr_Bibles_plans);	
		$this->assignRef('arr_Bibles_versions',	$arr_Bibles_versions);	
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('config',		$config);

		parent::display($tpl);
	}





}