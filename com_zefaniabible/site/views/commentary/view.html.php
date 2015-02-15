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
		$doc_page = JFactory::getDocument();
		/*
			a = commentary
			b = book
			c = chapter
			d = verse	
		*/
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$jinput = JFactory::getApplication()->input;
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		$item = new stdClass();
		$item->str_primary_commentary 			= $params->get('primaryCommentary');
		$item->str_com_default_image 			= $params->get('str_com_default_image','media/com_zefaniabible/images/commentaries.jpg');
		$item->str_commentary 		= $jinput->get('com', $item->str_primary_commentary, 'CMD');
		$item->int_Bible_Book_ID 	= $jinput->get('book', '1', 'INT');	
		$item->int_Bible_Chapter 	= $jinput->get('chapter', '1', 'INT');
		$item->int_Bible_Verse	 	= $jinput->get('verse', '1', 'INT');
				
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');			
		$item->str_commentary_text 	= 	$mdl_default->_buildQuery_commentary_verse($item->str_commentary, $item->int_Bible_Book_ID, $item->int_Bible_Chapter, $item->int_Bible_Verse);
		$item->str_commentary_name 	= 	$mdl_default->_buildQuery_commentary_name($item->str_commentary);
		$item->arr_meta				= 	$mdl_default->_buildQuery_meta($item->str_commentary, "commentary");	
		$item->str_meta_desc		= 	$mdl_common->fnc_make_meta_desc($item->arr_meta);
		$item->str_meta_key			= 	$mdl_common->fnc_make_meta_key($item->arr_meta);	

		$doc_page->setMetaData( 'keywords', $item->str_meta_key );		
		$doc_page->setMetaData( 'description', $item->str_meta_desc);
		$doc_page->addCustomTag( '<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />');
		//Filters
		$this->assignRef('item',	$item);
		parent::display($tpl);
	}
}