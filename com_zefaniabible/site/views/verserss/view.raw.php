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
class ZefaniabibleViewVerserss extends JViewLegacy
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
		$document	= JFactory::getDocument();
		
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		require_once(JPATH_COMPONENT_SITE.'/models/verserss.php');
		$biblemodel = new ZefaniabibleModelVerserss;		
		
		// time zone offset.
		$config =& JFactory::getConfig();
		date_default_timezone_set($config->get('config.offset'));			
		
		$str_primary_bible = 		$this->params->get('primaryBible', 'kjv');
		$str_start_date = new DateTime($this->params->get('reading_start_date', '1-1-2012'));		
		$str_today = new DateTime(date('Y-m-d'));
		$int_day_diff = round(abs($str_today->format('U') - $str_start_date->format('U')) / (60*60*24));	
		$str_bibleVersion = JRequest::getCmd('a', $str_primary_bible);	
				
		$arr_verse_info	=	$biblemodel->_buildQuery_get_verses();
		$arr_bible_info	=	$biblemodel->_buildQuery_bible_name($str_bibleVersion);
		foreach ($arr_verse_info as $obj_verses)
		{
			$int_max_verses = count($obj_verses);
		}
		$int_verse_remainder = $int_day_diff % $int_max_verses;
		if($int_verse_remainder == 0)
		{
			$int_verse_remainder = $int_max_verses;
		}		
		$arr_verse	=	$biblemodel->_buildQuery_get_verse_of_the_day($arr_verse_info,$int_verse_remainder,$arr_bible_info);
		
		
		$this->assignRef('arr_verse',				$arr_verse);
		$this->assignRef('int_verse_remainder',		$int_verse_remainder);
		$this->assignRef('arr_verse_info',			$arr_verse_info);
		$this->assignRef('str_bible_Version',		$str_bibleVersion);
		parent::display($tpl);
	}
}