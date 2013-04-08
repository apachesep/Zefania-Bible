<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabiblebooknames
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


// No direct access
defined('_JEXEC') or die;

class ZefaniabibleController extends JControllerLegacy
{
	
	public function __construct($config = array()) 
	{
		parent::__construct($config);
	}
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{

		$view		= JFactory::getApplication()->input->getCmd('view', 's');
		$option		=JFactory::getApplication()->input->getCmd('option', 's');
        JFactory::getApplication()->input->set('view', $view);

		$params = JComponentHelper::getParams( 'com_zefaniabible' );

		require_once(JPATH_ADMIN_ZEFANIABIBLE.'/helpers/helper.php');
		$str_redirect_url = JRoute::_(JURI::root(true).ZefaniabibleHelper::urlRequest());
		$jversion = new JVersion();

		$str_requested_url =  JRoute::_(ZefaniabibleHelper::urlRequest());
		$str_current_url = JURI::current().'/';
		// Joomla 3.0 Redirect
		if (($str_requested_url != $str_current_url)and($jversion->RELEASE == '3.0'))
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$str_redirect_url);   			
		}
		// less than 2.5 Redirect
		elseif((!JRequest::getCmd('option',null, 'get'))and($jversion->RELEASE == '2.5'))
		{
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$str_redirect_url);   			
		}		
		$urlparams = array('option'=>'STRING', 'view'=>'STRING', 'layout'=>'STRING', 'Itemid'=>'INT', 'tmpl'=>'STRING', 'lang'=>'CMD', 'a'=>'STRING','b'=>'STRING','c'=>'STRING','d'=>'STRING','e'=>'STRING','f'=>'STRING','g'=>'STRING','h'=>'STRING');
		
		parent::display($cachable, $urlparams);
		return;
	}
}
