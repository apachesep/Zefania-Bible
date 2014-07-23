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
class ZefaniabibleViewVerserss extends JViewLegacy
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
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		require_once(JPATH_COMPONENT_SITE.'/models/verserss.php');
		$biblemodel = new ZefaniabibleModelVerserss;		
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_start_reading_date 			= $params->get('reading_start_date', '1-1-2012');
		$item->flg_use_year_date				= $params->get('flg_use_year_date', '0');
		
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_verse_of_day_verse();
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days);
		$item->str_bible_name					= 	$mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_day_number 					= 	$jinput->get('day', $item->int_day_diff, 'INT');
		$item->flg_redirect_request 			= 	$jinput->get('type', '1', 'INT');
		$item->arr_verse_info					= 	$mdl_default->_buildQuery_get_verse_of_the_day_info($item->int_day_diff);
		$item->arr_verse_of_day					=	$mdl_default->_buildQuery_get_verse_of_the_day($item->arr_verse_info, $item->str_Bible_Version);
		print_r($item->arr_verse_info);
		

		
		foreach ($arr_verse_info as $obj_verses)
		{
			$int_max_verses = count($obj_verses);
		}
		$int_verse_remainder = $int_day_diff % $int_max_verses;
		if($int_verse_remainder == 0)
		{
			$int_verse_remainder = $int_max_verses;
		}
		if($flg_use_year_date)
		{
			$int_verse_remainder = (date('z')+1);
		}				
		//$arr_verse	=	$mdl_common->_buildQuery_get_verse_of_the_day($arr_verse_info,$int_verse_remainder,$arr_bible_info);
		
		
		if($item->flg_redirect_request)
		{		
			//header('HTTP/1.1 301 Moved Permanently');
			//header('Location: '.JURI::root().'index.php?option=com_zefaniabible&view=verserss&format=raw&bible='.$item->str_primary_bible);	
		}
		$this->assignRef('arr_verse',				$arr_verse);
		$this->assignRef('int_verse_remainder',		$int_verse_remainder);
		$this->assignRef('arr_verse_info',			$arr_verse_info);
		$this->assignRef('str_bible_Version',		$str_bibleVersion);		
		parent::display($tpl);
	}
}