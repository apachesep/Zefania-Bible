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
class ZefaniabibleViewZefaniascripture extends JView
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
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');

		$user 	= JFactory::getUser();

		$access = ZefaniabibleHelper::getACL();
		$state		= $this->get('State');

		$document	= &JFactory::getDocument();
		$document->title = $document->titlePrefix . JText::_("ZEFANIABIBLE_VIEW_SCRIPTURE") . $document->titleSuffix;

		$total		= $this->get( 'Total');
		$arr_pagination = $this->get( 'Pagination' );

		require_once(JPATH_COMPONENT_SITE.'/models/scripture.php');
		$mdl_bible_scripture = new ZefaniabibleModelZefaniascripture;

		$lists['order'] = $state->get('list.ordering');
		$lists['order_Dir'] = $state->get('list.direction');
		 
		print_r($state);
		$int_Bible_Chapter = $state->get('filter.biblechapter');
		$int_Bible_Verse_ID = $state->get('filter.bibleverse');
		$int_Bible_Book_ID = $state->get('filter.biblebook');
		$str_Bible_Version = $state->get('filter.bibleversion');
		
		$int_max_chapter = 		$mdl_bible_scripture->_buildQuery_max_chapters($int_Bible_Book_ID,$str_Bible_Version);
		$int_max_verse 	=		$mdl_bible_scripture->_buildQuery_max_verse($int_Bible_Book_ID,$int_Bible_Chapter);
		$arr_Bibles		= 		$mdl_bible_scripture->_buildQuery_default($arr_pagination, $int_Bible_Chapter, $int_Bible_Book_ID, $int_Bible_Verse_ID, $str_Bible_Version);		
		$arr_Bibles_versions =	$mdl_bible_scripture->_buildQuery_bible_versions();
		// Toolbar
		jimport('joomla.html.toolbar');
		$bar = & JToolBar::getInstance('toolbar');
		if ($access->get('core.create'))
			$bar->appendButton( 'Standard', "new", "JTOOLBAR_NEW", "new", false);
		if ($access->get('core.edit') || $access->get('core.edit.own'))
			$bar->appendButton( 'Standard', "edit", "JTOOLBAR_EDIT", "edit", true);
		if ($access->get('core.delete') || $access->get('core.delete.own'))
			$bar->appendButton( 'Standard', "delete", "JTOOLBAR_DELETE", "delete", true);
		if ($access->get('core.admin'))
			$bar->appendButton( 'Popup', 'options', JText::_('JTOOLBAR_OPTIONS'), 'index.php?option=com_config&view=component&component=' . $option . '&path=&tmpl=component');


		$this->assignRef('user',				JFactory::getUser());
		$this->assignRef('access',				$access);
		$this->assignRef('state',				$state);
		$this->assignRef('lists',				$lists);
		$this->assignRef('arr_Bibles_versions',	$arr_Bibles_versions);
		$this->assignRef('int_max_chapter',		$int_max_chapter);		
		$this->assignRef('int_max_verse',		$int_max_verse);	
		$this->assignRef('items',				$arr_Bibles);
		$this->assignRef('pagination',			$arr_pagination);
		$this->assignRef('config',				$config);

		parent::display($tpl);
	}





}