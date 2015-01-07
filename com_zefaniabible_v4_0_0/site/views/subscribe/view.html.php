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
class ZefaniabibleViewSubscribe extends JViewLegacy
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
		$config = JFactory::getConfig();
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 				= 	$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());	
		$item->flg_use_catcha 					= 	$params->get('flg_use_catcha', '0');
		$item->str_admin_email 					= 	$params->get('adminEmail');
		$item->str_start_reading_date 			= 	JHtml::date($params->get('reading_start_date', '1-1-2012'),'d-m-Y');
				
		$item->str_Bible_Version 				= 	$jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->str_reading_plan 				= 	$jinput->get('plan', $item->str_primary_reading,'CMD');			
		$item->flg_send_reading 				= 	$jinput->get('send_reading', 0, 'BOOL');
		$item->flg_send_verse					=	$jinput->get('send_verse', 0, 'BOOL');
		$item->str_user_name 					=	$jinput->get('name', $user->name, 'USERNAME');
		$item->str_email 						=	$jinput->get('email', $user->email, 'STRING');
		$item->str_view 						= 	$jinput->get('view', 'subscribe', 'CMD');
		$item->str_start_date					= 	$jinput->get('date', $item->str_start_reading_date, 'CMD');

		$item->str_from_email 					= 	$config->get( 'mailfrom' );
    	$item->str_from_email_name				= 	$config->get( 'fromname' );
		$item->arr_Bibles 						= 	$mdl_default->_buildQuery_Bibles_Names();
		$item->arr_reading_plan_list			= 	$mdl_default->_buildQuery_reading_plan_list($item);
		$item->obj_reading_plan_dropdown		=	$mdl_common->fnc_reading_plan_drop_down($item);
		$item->obj_bible_Bible_dropdown			= 	$mdl_common->fnc_bible_name_dropdown($item->arr_Bibles,$item->str_Bible_Version);
		$item->id								=	$user->id;

		$item->flg_email_valid = 0;
		if($item->str_email)
		{
			$item->flg_email_valid 				=	$mdl_common->fnc_validate_email($item->str_email);
		}
		if($item->str_start_date)
		{
			$item->flg_date_valid				= 	$mdl_common->fnc_validate_date($item->str_start_date);
		}
		if(($item->flg_use_catcha)and(($item->flg_send_reading)or($item->flg_send_verse)))
		{
			$item->flg_catcha_correct 			= 	$mdl_common->fnc_check_catcha($item->str_view);
		}
		else
		{
			$item->flg_catcha_correct = 1;
		}
		
		if(($item->flg_email_valid)and($item->flg_date_valid)and($item->flg_catcha_correct))
		{
			if(($item->flg_send_reading)or($item->flg_send_verse))
			{
				$mdl_default->_buildQuery_InsertUser($item);
				$mdl_common->sendSignUpEmail($item);
			}
			else
			{
				JError::raiseWarning('',JText::_('ZEFANIABIBLE_SELECT_EMAIL'));
			}			
		}

		//Filters
		$this->assignRef('item', $item);
		parent::display($tpl);
	}
}