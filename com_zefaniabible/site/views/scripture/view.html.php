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
class ZefaniabibleViewScripture extends JViewLegacy
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
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');
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
		$item->str_content_Bible_alias 			= $params->get('content_Bible_alias', 'kjv');
		$item->flg_show_credit 					= $params->get('show_credit','0');
		$item->str_default_image 				= $params->get('str_scripture_default_image','media/com_zefaniabible/images/scripture.jpg');
		$item->flg_enable_debug					= $params->get('flg_enable_debug','0');	
		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_Bible_Book_ID 	= $jinput->get('book', $item->int_primary_book_front_end, 'INT');
		$item->str_begin_chap 		= $jinput->get('chapter', $item->int_primary_chapter_front_end, 'INT');		
		$item->str_begin_verse 		= $jinput->get('verse', '1','INT');
		$item->str_end_chap			= $jinput->get('endchapter', '0', 'INT');	
		$item->str_end_verse		= $jinput->get('endverse', '0', 'INT');
		$item->type					= $jinput->get('type', '0', 'INT');
		$item->str_variant		 	= $jinput->get('variant', 'default', 'CMD');
		
		$item->flg_add_title = 0;
		if($item->str_content_Bible_alias != $item->str_Bible_Version)
		{
			$item->flg_add_title = 1;
		}
		$item->arr_verses = $mdl_default->_buildQuery_scripture($item->str_Bible_Version, $item->int_Bible_Book_ID, $item->str_begin_chap, $item->str_begin_verse, $item->str_end_chap, $item->str_end_verse);	 
		
		//Filters
		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}