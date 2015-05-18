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

	function display( $tpl = null )
	{
		/*
			a = bible alias
			b = book id
			c = chapter number
		*/		
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
				
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
			
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->int_primary_book_front_end 		= $params->get('primary_book_frontend');
		$item->int_primary_chapter_front_end 	= $params->get('int_front_start_chapter',1);		
		$item->str_default_image 				= $params->get('str_default_image', 'media/com_zefaniabible/images/bible_100.jpg');
		$item->int_menu_item 					= $params->get('rp_mo_menuitem', 0);
		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->int_Bible_Book_ID 	= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
		$item->int_Bible_Chapter 	= $jinput->get('chapter', $item->int_primary_chapter_front_end, 'INT');
		$item->str_variant		 	= $jinput->get('variant', 'default', 'CMD');	
			
		$item->arr_Bibles 				= $mdl_default->_buildQuery_Bibles_Names();
		$item->arr_Chapter 				= $mdl_default->_buildQuery_Chapter($item->int_Bible_Chapter,$item->int_Bible_Book_ID,$item->str_Bible_Version);
		$item->str_bible_name			= $mdl_common->fnc_find_bible_name($item->arr_Bibles,$item->str_Bible_Version);
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();
		$item->str_view_plan			= $mdl_default->_buildQuery_get_menu_id('standard');
		$item->int_max_chapter			= $mdl_default->_buildQuery_Max_Chapter($item->int_Bible_Book_ID);
		$item->int_max_verse			= $mdl_default->_buildQuery_Max_Verse($item->int_Bible_Book_ID,$item->int_Bible_Chapter);
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();
		
		switch($item->str_variant)
		{
			case "atom":
				$this->document->setMimeEncoding('text/xml');
				break;
				
			case "json":
			case "json2":
				$this->document->setMimeEncoding('application/json');
//				JResponse::setHeader('Content-Disposition','attachment;filename='.$item->str_Bible_Version.'.json');
				break;
												
			default:
				$this->document->setMimeEncoding('text/xml');				
				break;	
		}					
		
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('item',$item);
		parent::display($tpl);
	}
}