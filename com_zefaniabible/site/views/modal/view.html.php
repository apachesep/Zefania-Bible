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
		a = Language 
		b = Link Type
		c = set tag flag
		d = Label
		e = Alias
		f = Bible Book
		g = Begin Chap
		h = Begin Verse
		i = End Chap
		j = End Verse
	*/
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();
		
		require_once(JPATH_COMPONENT_SITE.'/models/modal.php');
		$mdl_bible_modal = new ZefaniabibleModelModal;
		$int_link_type = JRequest::getInt('b');
		$str_bible_alias = JRequest::getWord('e');
		$int_bible_book_id = JRequest::getInt('f');
		$int_begin_chap = JRequest::getInt('g');
		$int_begin_verse = JRequest::getInt('h');
		$int_end_chap = JRequest::getInt('i');
		$int_end_verse = JRequest::getInt('j');
				
		$arr_bible_list = $mdl_bible_modal-> _buildQuery_Bibles();
		if(($int_link_type == 2)or($int_link_type == 3))
		{
			$arr_bible_verse = $mdl_bible_modal-> _buildQuery_Verses($str_bible_alias,$int_bible_book_id,$int_begin_chap,$int_begin_verse,$int_end_chap,$int_end_verse);
		}
		$this->assignRef('arr_bible_list',		$arr_bible_list);
		$this->assignRef('arr_bible_verse',		$arr_bible_verse);		
		parent::display($tpl);
	}
}