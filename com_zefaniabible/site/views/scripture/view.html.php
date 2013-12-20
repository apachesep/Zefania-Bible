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
		$mdl_access = new ZefaniabibleHelper;
		$access = $mdl_access->getACL();
		$document	= JFactory::getDocument();
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');
		
		require_once(JPATH_COMPONENT_SITE.'/models/scripture.php');
		$str_Bible_Version = JRequest::getCmd('a', 'kjv');
		$str_Bible_book_id = JRequest::getInt('b', '1');
		$str_begin_chap = JRequest::getInt('c', '1');
		$str_begin_verse = JRequest::getInt('d', '1');
		$str_end_chap = JRequest::getInt('e', '0');
		$str_end_verse = JRequest::getInt('f', '0');	
		$flg_add_title = 0;	
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );		
		$str_Bible_alias = $this->params->get('content_Bible_alias', 'kjv');
		if($str_Bible_alias != $str_Bible_Version)
		{
			$flg_add_title = 1;
		}
		$biblemodel = new ZefaniabibleModelScripture;	
		$arr_verses = $biblemodel->_buildQuery_scripture($str_Bible_Version, $str_Bible_book_id, $str_begin_chap, $str_begin_verse, $str_end_chap, $str_end_verse);	 

		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('access',					$access);
		$this->assignRef('arr_verses',				$arr_verses);
		$this->assignRef('str_Bible_Version',		$str_Bible_Version);
		$this->assignRef('str_Bible_book_id',		$str_Bible_book_id);
		$this->assignRef('str_begin_chap',			$str_begin_chap);
		$this->assignRef('str_begin_verse',			$str_begin_verse);
		$this->assignRef('str_end_chap',			$str_end_chap);
		$this->assignRef('str_end_verse',			$str_end_verse);
		$this->assignRef('flg_add_title',			$flg_add_title);		
		$this->assignRef('lists',					$lists);
		$this->assignRef('config',					$config);
		parent::display($tpl);
	}
}