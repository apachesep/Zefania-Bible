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
class ZefaniabibleViewModal extends JViewLegacy
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
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
									
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->int_primary_book_front_end 		= $params->get('primary_book_frontend', 1);
		$item->int_primary_chapter_front_end 	= $params->get('int_front_start_chapter',1);
		$item->int_modal_width 					= $params->get('int_modal_width',800);
		$item->int_modal_height 				= $params->get('int_modal_height', 500);
		$item->str_bible_gateway_version 		= $params->get('bible_gateway_version', 9); 
								
		$item->str_lang				= $jinput->get('lang', 'en-GB', 'CMD');
		$item->str_link_type		= $jinput->get('type', null, 'CMD');
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');
		$item->int_Bible_Book_ID 	= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
		$item->int_begin_chap 		= $jinput->get('chapter', $item->int_primary_chapter_front_end, 'INT');		
		$item->int_begin_verse 		= $jinput->get('verse', '1','INT');
		$item->int_end_chap			= $jinput->get('endchapter', '0', 'INT');	
		$item->int_end_verse		= $jinput->get('endverse', '0', 'INT');
		$item->flg_use_tags 		= $jinput->get('tag', '0', 'BOOL');
		$item->str_label 			= $jinput->get('label', 'link', 'CMD');								
							
		$item->arr_Bibles 				= $mdl_default->_buildQuery_Bibles_Names();
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();
		$item->obj_bible_Bible_dropdown	= $mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);
		$item->obj_bible_book_dropdown 	= $mdl_common->fnc_bible_book_dropdown($item); 
				
		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}