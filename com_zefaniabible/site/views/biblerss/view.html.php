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
class ZefaniabibleViewBiblerss extends JViewLegacy
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
			a = bible alias
			b = book id
			c = chapter number
		*/		
		$app = JFactory::getApplication();
		$jinput = JFactory::getApplication()->input;
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->int_primary_book_front_end 		= $params->get('primary_book_frontend', 1);
		$item->int_primary_chapter_front_end 	= $params->get('int_front_start_chapter',1);
		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_Bible_Book_ID 	= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
		$item->int_Bible_Chapter 	= $jinput->get('chapter', $item->int_primary_chapter_front_end, 'INT');
		$item->str_layout		 	= $jinput->get('layout', 'default', 'CMD');
		$item->str_variant		 	= $jinput->get('variant', 'rss', 'CMD');
		$item->flg_use_sef			= JFactory::getApplication()->getRouter()->getMode();
		$item->str_variant		 	= $jinput->get('variant', 'default', 'CMD');

		header('HTTP/1.1 301 Moved Permanently');

		if($item->flg_use_sef)
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=biblerss&bible='.$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter."&variant=".$item->str_variant).'?format=raw');
		}
		else
		{
			header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=biblerss&bible='.$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter."&variant=".$item->str_variant.'&format=raw', false));
		}

		parent::display($tpl);
	}
}