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
class ZefaniabibleViewBiblerss extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display( $tpl = null )
	{
		$this->document->setMimeEncoding('text/xml');
		/*
			a = bible alias
			b = book id
			c = chapter number
		*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$document	= JFactory::getDocument();
		
		$this->params = JComponentHelper::getParams( 'com_zefaniabible' );
		require_once(JPATH_COMPONENT_SITE.'/models/biblerss.php');

		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;		

		$mdl_Bible_Model = new ZefaniabibleModelBiblerss;		
		$str_primary_bible = 		$this->params->get('primaryBible', $mdl_default->_buildQuery_first_record());
		$str_Bible_Version = JRequest::getCmd('a', $str_primary_bible);	
		$int_book_id = JRequest::getInt('b', 1);
		$int_chapter_id = JRequest::getInt('c', 1);
				
		$arr_Bible_Chapter = $mdl_Bible_Model-> _buildQuery_Chapter($str_Bible_Version, $int_book_id, $int_chapter_id);
		$str_Bible_Name = $mdl_Bible_Model-> _buildQuery_Bible_Name($str_Bible_Version);
		
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('access',				$access);
		$this->assignRef('lists',				$lists);
		$this->assignRef('config',				$config);
		$this->assignRef('arr_Bible_Chapter',	$arr_Bible_Chapter);
		$this->assignRef('str_Bible_Name',	$str_Bible_Name);
		parent::display($tpl);
	}
}