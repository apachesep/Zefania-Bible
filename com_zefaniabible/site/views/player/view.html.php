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
class ZefaniabibleViewPlayer extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
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
			a = alias
			b = book
		*/	
		$app = JFactory::getApplication();
	
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
			
		// menu item overwrites
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			//$menu = JSite::getMenu();
			$mdl_menu = new JSite;
			$menu = $mdl_menu->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->int_primary_book_front_end 		= $params->get('primary_book_frontend');
		$item->str_xml_audio_path 				= $params->get('xmlAudioPath', 'media/com_zefaniabible/audio/');
		$item->int_player_type 					= $params->get('player_type', '0');
		$item->int_player_popup_height 			= $params->get('player_popup_height','300');
		$item->int_player_popup_width 			= $params->get('player_popup_width','300');		
		$item->flg_enable_debug					= $params->get('flg_enable_debug','0');	
		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_Bible_Book_ID 	= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
				
		$item->arr_book_info 		= $mdl_default->_buildQuery_Bible_info($item->str_Bible_Version);

		//Filters
		$this->assignRef('item',	$item);
		parent::display($tpl);
	}
}
?>