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

@define('JPATH_ADMIN_ZEFANIABIBLE', JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_zefaniabible');
@define('JPATH_SITE_ZEFANIABIBLE', JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_zefaniabible');

// Include dependancies
jimport('joomla.application.component.controller');
JHTML::_('behavior.framework');
JHTML::_('behavior.modal');
JHTML::stylesheet('components/com_zefaniabible/css/zefaniabible.css');
// Load languages and merge with fallbacks
$jlang = JFactory::getLanguage();
$jlang->load('com_zefaniabible', JPATH_BASE, 'en-GB', true);
$jlang->load('com_zefaniabible', JPATH_BASE, null, true);

$controller	= JControllerLegacy::getInstance('Zefaniabible');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();