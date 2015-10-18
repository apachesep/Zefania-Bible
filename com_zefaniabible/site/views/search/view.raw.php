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
class ZefaniabibleViewSearch extends JViewLegacy
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
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
													
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		// menu item overwrites
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}

		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 	= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->query 				= strip_tags(htmlentities($jinput->get('query', 'john 3:16', 'STRING'))); 
		$item->int_limit_query		= $params->get('int_limit_query', 100);
		$item->flg_use_api			= $params->get('flg_use_api', 0);
		$item->flg_use_key			= $params->get('flg_use_key', 0);
		$item->str_api_key			= $params->get('str_api_key');		
		$item->str_user_api_key 		= $jinput->get('apikey', '', 'CMD');
		
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();
		$item->arr_Bibles 				= $mdl_default->_buildQuery_Bibles_Names();
		$item->str_bible_name			= $mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->arr_search_result		= $mdl_default->_buildQuery_search_bible($item->str_Bible_Version, $item->query, $item->int_limit_query);

		$this->document->setMimeEncoding('application/json');
		
		
		$this->assignRef('item', 		$item);
		parent::display($tpl);
	}
}