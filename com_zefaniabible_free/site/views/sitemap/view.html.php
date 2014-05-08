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
			Sitemap
			a = bible

		*/	
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_primary_bible = $params->get('primaryBible', 'kjv');
		$flg_only_primary_bible = $params->get('flg_only_primary_bible', '1');
		$str_bible_alias = JRequest::getCmd('a', $str_primary_bible);	
		
		$menuitemid = $params->get('rp_mo_menuitem');		
		
		('HTTP/1.1 301 Moved Permanently');
		if($flg_only_primary_bible)  
		{
			header('Location: '.JURI::root().'index.php?option=com_zefaniabible&view=sitemap&format=raw&a='.$str_bible_alias."&Itemid=".$menuitemid);			
		}
		else
		{
			header('Location: '.JURI::root().'index.php?option=com_zefaniabible&view=sitemap&format=raw&Itemid='.$menuitemid);
		}
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('access',				$access);
		parent::display($tpl);
	}
}