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

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewBooks extends JViewLegacy
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
		require_once('components/com_zefaniabible/models/default.php');
		require_once('components/com_zefaniabible/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		$item = new stdClass();
		$item->arr_english_book_names 		= 	$mdl_common->fnc_load_languages();
		$item->str_primary_bible 			= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_menuItem					=	$mdl_default->_buildQuery_get_menu_id('standard');
		$item->arr_max_chapters 			=	$mdl_default->_buildQuery_Max_Bible_Chapters($item->str_primary_bible);
		$jinput = JFactory::getApplication()->input;
		$item->str_variant		 	= $jinput->get('variant', 'list', 'CMD');
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->flg_use_sef			= JFactory::getApplication()->getRouter()->getMode();
		
		
		if($item->str_variant == 'json')
		{		
			header('HTTP/1.1 301 Moved Permanently');
			if($item->flg_use_sef)
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=books&bible='.$item->str_primary_bible.'&variant='.$item->str_variant).'?format=raw');				
			}
			else
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=books&bible='.$item->str_primary_bible.'&format=raw&variant='.$item->str_variant, false));	
			}
		}
		
		//Filters
		$this->assignRef('item', 		$item);
		parent::display($tpl);
	}
}