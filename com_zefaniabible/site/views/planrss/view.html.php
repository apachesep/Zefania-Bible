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
class ZefaniabibleViewPlanrss extends JViewLegacy
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
			a = Plan Alias
			b = Bible Alias
			c = start day filter
			d = number of items
			e = feed type atom/rss
		*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;	

		$mainframe = JFactory::getApplication();			
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_primary_bible = 		$this->params->get('primaryBible', $mdl_default->_buildQuery_first_record());
		$str_primary_plan = 		$this->params->get('primaryReading');
		$str_plan_alias = 	JRequest::getCmd('a', $str_primary_plan);	
		$str_Bible_Version = JRequest::getCmd('b', $str_primary_bible);	
		$int_start_item = JRequest::getInt('c', JRequest::getVar('limitstart', 0, '', 'int'));
		$int_number_of_items = JRequest::getInt('d', $mainframe->getCfg('feed_limit'));

		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.JURI::root().'index.php?option=com_zefaniabible&view=planrss&format=raw&a='.$str_plan_alias.'&b='.$str_Bible_Version.'&c='.$int_start_item.'&d='.$int_number_of_items);
		parent::display($tpl);
	}
}