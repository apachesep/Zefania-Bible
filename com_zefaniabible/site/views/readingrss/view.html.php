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
class ZefaniabibleViewReadingrss extends JViewLegacy
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
			a = plan
			b = bible
			c = day
		*/		
		$app = JFactory::getApplication();
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());
		$item->str_start_reading_date 			= 	$params->get('reading_start_date', '1-1-2012');
			
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');	
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->str_variant		 				= 	$jinput->get('variant', 'default', 'CMD');
		
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_verse_of_day_verse();
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days);
		$item->int_day_number 					= 	$jinput->get('day', $item->int_day_diff, 'INT');
		$item->flg_redirect_request 			= 	$jinput->get('type', '1', 'INT');
		$item->flg_use_sef						= 	JFactory::getApplication()->getRouter()->getMode();
		
		header('HTTP/1.1 301 Moved Permanently');
		if($item->flg_use_sef)
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=readingrss&plan='.$item->str_reading_plan."&bible=".$item->str_Bible_Version.'&day='.$item->int_day_number.'&type='.$item->flg_redirect_request.'&variant='.$item->str_variant).'?format=raw');	
		}
		else
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=readingrss&plan='.$item->str_reading_plan."&bible=".$item->str_Bible_Version.'&day='.$item->int_day_number.'&type='.$item->flg_redirect_request.'&variant='.$item->str_variant.'&format=raw', false));				
		}
		parent::display($tpl);
	}
}