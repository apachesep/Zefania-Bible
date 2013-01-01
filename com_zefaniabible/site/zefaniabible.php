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


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

@define('JPATH_ADMIN_ZEFANIABIBLE', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_zefaniabible');
@define('JPATH_SITE_ZEFANIABIBLE', JPATH_SITE . DS . 'components' . DS . 'com_zefaniabible');

require_once(JPATH_ADMIN_ZEFANIABIBLE .DS.'helpers'.DS.'helper.php');
JHTML::_("behavior.mootools");

// Set the table directory
JTable::addIncludePath(JPATH_ADMIN_ZEFANIABIBLE . DS . 'tables');

//Document title
$document	= &JFactory::getDocument();
$document->titlePrefix = "ZefaniaBible - ";
$document->titleSuffix = "";

if (defined('JDEBUG') && count($_POST))
	$_SESSION['Zefaniabible']['$_POST'] = $_POST;


$view = JRequest::getCmd( 'view');

switch ($view)
{
		case 'commentary' :
		case 'commentaryitem' :
			$controllerName = "commentary";
			break;
		case 'compare' :
		case 'compareitem' :
			$controllerName = "compare";			
			break;
		case 'reading' :
		case 'readingitem' :
			$controllerName = "reading";	
		case 'readingrss' :
		case 'readingrssitem' :
			$controllerName = "readingrss";						
			break;
		case 'verserss' :
		case 'verserssitem' :
			$controllerName = "verserss";						
			break;			
		case 'plan' :
		case 'planitem' :
			$controllerName = "plan";			
			break;		
		case 'player' :
		case 'playeritem' :
			$controllerName = "player";			
			break;
		case 'scripture' :
		case 'scriptureitem' :
			$controllerName = "scripture";			
			break;
		case 'standard' :
		case 'standarditem' :
			$controllerName = "standard";			
			break;									
		case 'subscribe' :
		case 'subscribeitem' :
			$controllerName = "subscribe";			
			break;
		case 'unsubscribe' :
		case 'unsubscribeitem' :
			$controllerName = "unsubscribe";			
			break;
		case 'verseofday' :
		case 'verseofdayitem' :
			$controllerName = "verseofday";			
			break;								
		default:
			$view = 'standard';
			$layout = 'default';
			JRequest::setVar( 'view', $view);
			JRequest::setVar( 'layout', $layout);
			$controllerName = "standard";
			break;
}

require_once(JPATH_ADMIN_ZEFANIABIBLE .DS.'classes'.DS.'jcontroller.php');
if ($controllerName)
	require_once( JPATH_SITE_ZEFANIABIBLE .DS.'controllers'.DS.$controllerName.'.php' );

$controllerName = 'ZefaniabibleController'.$controllerName;


// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );

// Redirect if set by the controller
$controller->redirect();

