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

function cimport($namespace){
	include_once JPATH_ADMIN_ZEFANIABIBLE . DS . str_replace(".", DS, $namespace) . '.php';
}

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
$layout = JRequest::getCmd( 'layout');

$mainMenu = true;

switch ($view)
{

		case 'zefaniabible' :
		case 'zefaniabibleitem' :
        	$controllerName = "zefaniabible";
			break;

		case 'zefaniacomment' :
		case 'zefaniacommentitems' :
			$controllerName = "zefaniacomment";
			break;
		
		case 'zefaniareading' :
		case 'zefaniareadingitem' :
        	$controllerName = "zefaniareading";
			break;

		case 'zefaniareadingdetails' :
		case 'zefaniareadingdetailsitem' :
        	$controllerName = "zefaniareadingdetails";
			break;

		case 'zefaniauser' :
		case 'zefaniauseritem' :
        	$controllerName = "zefaniauser";
			break;

		case 'zefaniaverseofday' :
		case 'zefaniaverseofdayitem' :
        	$controllerName = "zefaniaverseofday";
			break;
		
		case 'zefaniascripture' :
		case 'zefaniascriptureitem' :
        	$controllerName = "zefaniascripture";
			break;		

		default:
			$view = 'zefaniabible';
			$layout = 'default';
			JRequest::setVar( 'view', $view);
			JRequest::setVar( 'layout', $layout);
			$controllerName = "zefaniabible";
			break;
}


if ($mainMenu)
{
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_BIBLES"), 'index.php?option=com_zefaniabible&view=zefaniabible', ($view == 'zefaniabible'));
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_COMMENTARIES"), 'index.php?option=com_zefaniabible&view=zefaniacomment', ($view == 'zefaniacomment'));
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_SCRIPTURE"), 'index.php?option=com_zefaniabible&view=zefaniascripture', ($view == 'zefaniascripture'));		
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_READING_PLAN"), 'index.php?option=com_zefaniabible&view=zefaniareading', ($view == 'zefaniareading'));
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_READING_PLAN_DETAILS"), 'index.php?option=com_zefaniabible&view=zefaniareadingdetails', ($view == 'zefaniareadingdetails'));
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_USERS"), 'index.php?option=com_zefaniabible&view=zefaniauser', ($view == 'zefaniauser'));
		JSubMenuHelper::addEntry(JText::_("ZEFANIABIBLE_VIEW_VERSE_OF_DAY"), 'index.php?option=com_zefaniabible&view=zefaniaverseofday', ($view == 'zefaniaverseofday'));

}

require_once(JPATH_ADMIN_ZEFANIABIBLE .DS.'classes'.DS.'jcontroller.php');
if ($controllerName)
	require_once( JPATH_ADMIN_ZEFANIABIBLE .DS.'controllers'.DS.$controllerName.'.php' );

$controllerName = 'ZefaniabibleController'.$controllerName;




// Create the controller
$controller = new $controllerName();

// Perform the Request task
$controller->execute( JRequest::getCmd('task') );

// Redirect if set by the controller
$controller->redirect();

