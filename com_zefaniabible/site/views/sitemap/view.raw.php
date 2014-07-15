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
class ZefaniabibleViewSitemap extends JViewLegacy
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
		
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		require_once(JPATH_COMPONENT_SITE.'/models/sitemap.php');
		$mdl_bible_model_sitemap = new ZefaniabibleModelSitemap;		
	
		$menuitemid = $params->get('rp_mo_menuitem');
		
		$flg_only_primary_bible = $params->get('flg_only_primary_bible', '1');
		if($flg_only_primary_bible)
		{
			$str_alias = JRequest::getWord('a');
			$arr_chapter_list = $mdl_bible_model_sitemap -> _buildQuery_ChapterList($str_alias);
		}
		else
		{
			$str_alias = '';
			$arr_chapter_list = $mdl_bible_model_sitemap -> _buildQuery_ChapterList($str_alias);

		}		
		
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('int_day_number',		$int_day_number);
		$this->assignRef('access',				$access);
		$this->assignRef('arr_chapter_list',	$arr_chapter_list);

		parent::display($tpl);
	}
}