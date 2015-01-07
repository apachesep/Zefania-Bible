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
class ZefaniabibleViewSitemap extends JViewLegacy
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
			Sitemap
			a = bible

		*/	
		$app = JFactory::getApplication();
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();	
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->flg_only_primary_bible 			= $params->get('flg_only_primary_bible', '1');
		$item->str_Bible_Version 				= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_menu_item_id 				= $jinput->get('Itemid', null, 'INT');
		$item->flg_use_sef						= 	JFactory::getApplication()->getRouter()->getMode();
		
		header('HTTP/1.1 301 Moved Permanently');
		if($item->flg_use_sef)
		{
			if($item->flg_only_primary_bible)  
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=sitemap&bible='.$item->str_Bible_Version."&Itemid=".$item->int_menu_item_id).'?format=raw');
			}
			else
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=sitemap&Itemid='.$item->int_menu_item_id).'?format=raw');
			}	
		}
		else
		{
			if($item->flg_only_primary_bible)  
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=sitemap&bible='.$item->str_Bible_Version."&Itemid=".$item->int_menu_item_id.'&format=raw', false));
			}
			else
			{
				header('Location: '.substr(JURI::base(),0, -1).JRoute::_('index.php?option=com_zefaniabible&view=sitemap&Itemid='.$item->int_menu_item_id.'&format=raw', false));
			}		
		}
		//Filters
		$this->assignRef('item',$item);
		parent::display($tpl);
	}
}