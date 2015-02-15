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
class ZefaniabibleViewUnsubscribe extends JViewLegacy
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
		$user 	= JFactory::getUser();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$config = JFactory::getConfig();
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;

		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_email 						=	$jinput->get('email', $user->email, 'STRING');
		$item->str_user_name 					=	$jinput->get('name', $user->name, 'USERNAME'); 
		$item->flg_send_reading 				= 	$jinput->get('send_reading', 0, 'BOOL');
		$item->flg_send_verse					=	$jinput->get('send_verse', 0, 'BOOL');
		$item->str_view 						= 	$jinput->get('view', 'unsubscribe', 'CMD');
		
		$item->flg_use_catcha 					= 	$params->get('flg_use_catcha', '0');	
		$item->str_admin_email 					= 	$params->get('adminEmail');
		
		$item->str_from_email 					= 	$config->get( 'mailfrom' );
    	$item->str_from_email_name				= 	$config->get( 'fromname' );
		$item->id								=	$user->id;
		$item->flg_catcha_correct = 0;
		if($item->str_email)
		{
			$item->flg_email_valid 				=	$mdl_common->fnc_validate_email($item->str_email);
			if($item->flg_use_catcha)
			{
				$item->flg_catcha_correct 		= 	$mdl_common->fnc_check_catcha($item->str_view);
			}
			else{
				$item->flg_catcha_correct = 1;
			}
		}
		if(($item->flg_catcha_correct)and($item->flg_email_valid))
		{
			$mdl_default->_buildQuery_UpdateUser($item->str_email,$item->flg_send_reading,$item->flg_send_verse);
		}
		//Filters
		$this->assignRef('item',$item);
		parent::display($tpl);
	}
}