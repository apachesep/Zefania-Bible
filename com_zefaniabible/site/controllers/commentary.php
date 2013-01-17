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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * Zefaniabible Zefaniabible Controller
 *
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleControllerCommentary extends ZefaniabibleController
{
	var $ctrl = 'commentary';
	var $singular = 'commentaryitem';

	function __construct($config = array())
	{

		parent::__construct($config);

		$app = JFactory::getApplication();
		$this->registerTask( 'unpublish',  'unpublish' );
		$this->registerTask( 'apply',  'apply' );
	}

	function display( )
	{
		parent::display();
		if (!JRequest::getCmd('option',null, 'get'))
		{
			//Kill the post and rebuild the url
			//$this->setRedirect(ZefaniabibleHelper::urlRequest());
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.JURI::root(true).ZefaniabibleHelper::urlRequest());	
			return;
		}

	}
}