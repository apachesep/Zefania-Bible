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
class ZefaniabibleViewStrong extends JViewLegacy
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
		/*
			a = Dictionary alias
			b = Strong ID
		*/
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_dictionary  			= $params->get('str_primary_dictionary','');
		$item->str_default_image 				= $params->get('str_dict_default_image','media/com_zefaniabible/images/dictionary.jpg');
		$item->flg_show_credit 					= $params->get('show_credit','0');
		$item->str_dictionary_height 			= $params->get('str_dictionary_height','500');
		$item->str_dictionary_width 			= $params->get('str_dictionary_width','800');	
				
		$item->str_tmpl 			= $jinput->get('tmpl',null,'CMD');
		$item->str_curr_dict 		= $jinput->get('dict', $item->str_primary_dictionary, 'CMD');
		$item->str_strong_id		= $jinput->get('item', '1', 'CMD');
		
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');
		$item->arr_passage 		= $mdl_default->_buildQuery_strong($item->str_curr_dict,$item->str_strong_id);	 
		$item->str_dict_name 	= $mdl_default->_buildQuery_dict_name($item->str_curr_dict);

		//Filters
		$this->assignRef('item',				$item);	
		parent::display($tpl);
	}
}