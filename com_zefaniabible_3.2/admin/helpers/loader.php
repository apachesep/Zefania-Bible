<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.propoved.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Some usefull constants
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('BR')) define("BR", "<br />");
if(!defined('LN')) define("LN", "\n");

//Joomla 1.6 only
if (!defined('JPATH_PLATFORM')) define('JPATH_PLATFORM', JPATH_SITE .DS. 'libraries');

// Main component aliases
if (!defined('COM_ZEFANIABIBLE')) define('COM_ZEFANIABIBLE', 'com_zefaniabible');
if (!defined('ZEFANIABIBLE_CLASS')) define('ZEFANIABIBLE_CLASS', 'Zefaniabible');

// Component paths constants
if (!defined('JPATH_ADMIN_ZEFANIABIBLE')) define('JPATH_ADMIN_ZEFANIABIBLE', JPATH_ADMINISTRATOR . DS . 'components' . DS . COM_ZEFANIABIBLE);
if (!defined('JPATH_SITE_ZEFANIABIBLE')) define('JPATH_SITE_ZEFANIABIBLE', JPATH_SITE . DS . 'components' . DS . COM_ZEFANIABIBLE);

// JQuery use
if(!defined('JQUERY_VERSION')) define('JQUERY_VERSION', '1.8.2');


$app = JFactory::getApplication();
jimport('joomla.version');
$version = new JVersion();

// Load the component Dependencies
require_once(dirname(__FILE__) .DS. 'helper.php');


require_once(dirname(__FILE__) .DS. '..' .DS. 'classes' .DS. 'loader.php');

ZefaniabibleClassLoader::setup(false, false);
ZefaniabibleClassLoader::discover('Zefaniabible', JPATH_ADMIN_ZEFANIABIBLE, false, true);

// Some helpers
ZefaniabibleClassLoader::register('JToolBarHelper', JPATH_ADMINISTRATOR .DS. "includes" .DS. "toolbar.php", true);
ZefaniabibleClassLoader::register('JSubMenuHelper', JPATH_ADMINISTRATOR .DS. "includes" .DS. "toolbar.php", true);

// Handle cross compatibilities
require_once(dirname(__FILE__) .DS. 'mvc.php');

//Instance JDom
if (!isset($app->dom))
{
	jimport('jdom.dom');
	if (!class_exists('JDom'))
		JError::raiseError(null, 'JDom plugin is required');

	JDom::getInstance();	
}