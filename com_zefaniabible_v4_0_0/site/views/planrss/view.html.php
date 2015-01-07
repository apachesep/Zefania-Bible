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
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;	
		$mainframe = JFactory::getApplication();		
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		
		$item->str_limit_start 					=	$jinput->get('limitstart', 0, 'INT');
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->int_start_item 					= 	$jinput->get('start', $item->str_limit_start, 'INT');	
		$item->int_number_of_items				= 	$jinput->get('items', $mainframe->getCfg('feed_limit'), 'INT');	
		$item->flg_use_sef						= 	JFactory::getApplication()->getRouter()->getMode();
		$item->str_variant		 				= 	$jinput->get('variant', 'default', 'CMD');
		
		header('HTTP/1.1 301 Moved Permanently');
		if($item->flg_use_sef)
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=planrss&plan='.$item->str_reading_plan.'&bible='.$item->str_Bible_Version.'&start='.$item->int_start_item.'&items='.$item->int_number_of_items.'&variant='.$item->str_variant).'?format=raw');
		}
		else
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=planrss&plan='.$item->str_reading_plan.'&bible='.$item->str_Bible_Version.'&start='.$item->int_start_item.'&items='.$item->int_number_of_items.'&variant='.$item->str_variant.'&format=raw', false));			
		}
		parent::display($tpl);
	}
}