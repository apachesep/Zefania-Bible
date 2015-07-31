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
jimport('joomla.application.component.controller');

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
		$option		= JFactory::getApplication()->input->getCmd('option', 's');
        JFactory::getApplication()->input->set('view', $view);
		$str_tmpl = JRequest::getCmd('tmpl');
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		
		$jversion = new JVersion();
		
		//Contains followers
		$authorizedInUrl = array('plan','bible','bible2','book','chapter','verse','day','option', 'view', 'layout', 'Itemid', 'tmpl', 'lang','com','dict','strong','start','items','type','number', 'variant', 'year', 'month', 'query');
		
		$parts = array();
		$request = JRequest::get();
		foreach($request as $key => $value)
		{
			if (in_array($key, $authorizedInUrl))
			{
				$parts[] = $key . '=' . $value;
			}
		}
		$str_redirect_url = JRoute::_("index.php?" . implode("&", $parts), false);
		$str_requested_url =  JRoute::_("index.php?" . implode("&", $parts), false);
		
		$str_current_url = JURI::root(true).urldecode('/'.str_replace(JURI::root(),'',JURI::getInstance()->toString()));
		
		switch ($view) 
		{
			case 'standard':
			case 'compare':
			case 'reading':
			case 'calendar':
				if (($str_requested_url != $str_current_url)and($str_tmpl != 'component')and(stripos($str_current_url, 'index.php') <= 0))
				{
					header('HTTP/1.1 301 Moved Permanently');
					header('Location: '.$str_redirect_url);  
				}
				break;
		}
	
		$urlparams = array('option'=>'STRING', 'view'=>'STRING', 'layout'=>'STRING', 'Itemid'=>'INT', 'tmpl'=>'STRING', 'lang'=>'CMD', 'book'=> 'STRING', 'chapter'=> 'STRING');
		
		parent::display($cachable, $urlparams);
		return;
	}
}
