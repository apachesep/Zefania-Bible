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

	function display( $tpl = null )
	{
		$this->document->setMimeEncoding('text/xml');
		/*
			a = plan
			b = bible
			c = day
		*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$mdl_access = new ZefaniabibleHelper;
		$access = $mdl_access->getACL();
		$document	= JFactory::getDocument();
		
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		require_once(JPATH_COMPONENT_SITE.'/models/readingrss.php');
		$biblemodel = new ZefaniabibleModelReadingrss;		
		
		$str_primary_reading = 		$this->params->get('primaryReading', 'ttb');
		$str_primary_bible = 		$this->params->get('primaryBible', 'kjv');	
		$str_start_reading_date = 	$this->params->get('reading_start_date', '1-1-2012');
			
		$str_reading_plan = JRequest::getCmd('a', $str_primary_reading);	
		$str_bibleVersion = JRequest::getCmd('b', $str_primary_bible);
		
		$arr_start_date = new DateTime($str_start_reading_date);	
		$arr_today = new DateTime(date('Y-m-d'));
		$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;
		$int_day_number = 	JRequest::getInt('c', $int_day_diff);
		
		$arr_bibles 		=	$biblemodel->_buildQuery_Bibles();
		$arr_reading 		=	$biblemodel->_buildQuery_plan($str_reading_plan, $str_start_reading_date);
		$arr_reading_plans 	= 	$biblemodel->_buildQuery_readingplan();
		$arr_plan 			=	$biblemodel->_buildQuery_current_reading($arr_reading, $str_bibleVersion);
		
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('int_day_number',		$int_day_number);
		$this->assignRef('access',				$access);
		$this->assignRef('lists',				$lists);
		$this->assignRef('bibles',				$arr_bibles);
		$this->assignRef('plan',				$arr_plan);
		$this->assignRef('str_reading_plan',	$str_reading_plan);
		$this->assignRef('str_bible_Version',	$str_bibleVersion);
		$this->assignRef('reading',				$arr_reading);
		$this->assignRef('arr_reading_plans',	$arr_reading_plans);
		$this->assignRef('config',				$config);
		parent::display($tpl);
	}
}