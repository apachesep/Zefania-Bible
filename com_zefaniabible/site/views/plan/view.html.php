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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( '0');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewPlan extends JViewLegacy
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
		/*
			a = plan
			b = bible
			c = day
		*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$access = ZefaniabibleHelper::getACL();
		
		// menu item overwrites
		$params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JSite::getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		
		$document	= &JFactory::getDocument();
		require_once(JPATH_COMPONENT_SITE.'/models/plan.php');
		$biblemodel = new ZefaniabibleModelPlan;
		
		// create pagination
		jimport('joomla.html.pagination');
		
		$str_primary_reading = $params->get('primaryReading', 'ttb');
		$str_reading_plan = JRequest::getCmd('a', $str_primary_reading);
		$str_start_reading_date = $params->get('reading_start_date', '1-1-2012');
		$str_primary_bible = $params->get('primaryBible', 'kjv');
		
		$arr_pagination = $biblemodel->_get_pagination_readingplan_overview($str_reading_plan);
		$arr_reading = $biblemodel->_buildQuery_readingplan_overview($str_reading_plan,$arr_pagination);
		$arr_reading_plans = $biblemodel->_buildQuery_readingplan();
		$arr_bibles = $biblemodel->_buildQuery_Bibles();
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('bibles',		$arr_bibles);
		$this->assignRef('pagination',	$arr_pagination);
		$this->assignRef('reading',		$arr_reading);
		$this->assignRef('readingplans',$arr_reading_plans);
		$this->assignRef('config',		$config);
		parent::display($tpl);		
	}
}