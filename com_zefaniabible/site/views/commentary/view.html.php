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
class ZefaniabibleViewCommentary extends JViewLegacy
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
		$mdl_access =  new ZefaniabibleHelper;
		$access = $mdl_access->getACL();

		$document	= JFactory::getDocument();

		/*
			a = commentary
			b = book
			c = chapter
			d = verse	
		*/	
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$str_primary_commentary = $params->get('primaryCommentary');
		
		$str_commentary = JRequest::getCmd('a',$str_primary_commentary);			
		$int_Bible_Book_ID = JRequest::getInt('b', '1');	
		$int_Bible_Chapter = JRequest::getInt('c', '1');
		$int_Bible_Verse = JRequest::getInt('d', '1');
		
		require_once(JPATH_COMPONENT_SITE.'/models/commentary.php');
		$mdl_commentary = new ZefaniabibleModelCommentary;				
		$str_commentary_text =	$mdl_commentary-> _buildQuery_commentary_verse($str_commentary, $int_Bible_Book_ID, $int_Bible_Chapter, $int_Bible_Verse);
		$str_commentary_name = $mdl_commentary->_buildQuery_commentary_name($str_commentary);
		
		echo '<div class="zef_commentary_title">'.$str_commentary_name."</div>";
		echo '<div class="zef_commentary_book">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_Bible_Book_ID)." ".$int_Bible_Chapter.":".$int_Bible_Verse."</div>";
		echo '<div class="zef_commentary_verse">'.$str_commentary_text."</div>";
		//Filters
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('access',		$access);
		$this->assignRef('lists',		$lists);
		$this->assignRef('config',		$config);
		parent::display($tpl);
	}
}