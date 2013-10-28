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
class ZefaniabibleViewZefaniaModal extends JViewLegacy
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
		a = Link Type
		b = Alias
		c = Bible Book
		d = Begin Chap
		e = Begin Verse
		f = End Chap
		g = End Verse
	*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();
		
		require_once(JPATH_ADMIN_ZEFANIABIBLE.'/models/zefaniamodal.php');
		$mdl_bible_modal = new ZefaniabibleModelZefaniamodal;
		$int_link_type = JRequest::getInt('a');
		$str_bible_alias = JRequest::getCmd('b');
		$int_bible_book_id = JRequest::getInt('c');
		$int_begin_chap = JRequest::getInt('d');
		$int_begin_verse = JRequest::getInt('e');
		$int_end_chap = JRequest::getInt('f');
		$int_end_verse = JRequest::getInt('g');
				
		$arr_bible_list = $mdl_bible_modal-> _buildQuery_Bibles();
		if(($int_link_type == 2)or($int_link_type == 3))
		{
			$arr_bible_verse = $mdl_bible_modal-> _buildQuery_Verses($str_bible_alias,$int_bible_book_id,$int_begin_chap,$int_begin_verse,$int_end_chap,$int_end_verse);
		}
		
		$document	= JFactory::getDocument();
		$document->addStyleSheet('/administrator/components/com_zefaniabible/css/zefaniabible.css'); 
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_LAYOUT_BIBLES") . $document->titleSuffix;
		$this->assignRef('arr_bible_list',		$arr_bible_list);
		$this->assignRef('arr_bible_verse',		$arr_bible_verse);
		parent::display($tpl);
	}
}