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
		
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_primary_reading = 		$this->params->get('primaryReading', 'ttb');
		$str_primary_bible = 		$this->params->get('primaryBible', 'kjv');	
		$str_start_reading_date = 	$this->params->get('reading_start_date', '1-1-2012');
			
		$str_reading_plan = JRequest::getCmd('a', $str_primary_reading);	
		$str_bibleVersion = JRequest::getCmd('b', $str_primary_bible);	
		// time zone offset.
		$config =& JFactory::getConfig();
		date_default_timezone_set($config->getValue('config.offset'));	
		$arr_start_date = new DateTime($str_start_reading_date);	
		$arr_today = new DateTime(date('Y-m-d'));		
		$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;
		$int_day_number = 	JRequest::getInt('c', $int_day_diff);
		
		$int_feed_type = JRequest::getCmd('d', 0);	
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: '.JURI::root().'index.php?option=com_zefaniabible&view=readingrss&format=raw&a='.$str_reading_plan."&b=".$str_bibleVersion.'&c='.$int_day_number.'&d='.$int_feed_type);	
		parent::display($tpl);
	}
}