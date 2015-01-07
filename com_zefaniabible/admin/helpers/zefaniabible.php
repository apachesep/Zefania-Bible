<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Zefaniabible helper class.
 *
 * @package     Zefaniabible
 * @subpackage  Helpers
 */
class ZefaniabibleHelper
{
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_BIBLE_NAMES'), 
			'index.php?option=com_zefaniabible&view=zefaniabible', 
			$vName == 'zefaniabible'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_BIBLE_TEXT'), 
			'index.php?option=com_zefaniabible&view=zefaniascripture', 
			$vName == 'zefaniascripture'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_DICTIONARY_INFO'), 
			'index.php?option=com_zefaniabible&view=zefaniadictionary', 
			$vName == 'zefaniadictionary'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_DICTIONARY_DETAIL'), 
			'index.php?option=com_zefaniabible&view=zefaniabibledictdetail', 
			$vName == 'zefaniabibledictdetail'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIACOMMENT'), 
			'index.php?option=com_zefaniabible&view=zefaniacomment', 
			$vName == 'zefaniacomment'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_COMMENT_TEXT'), 
			'index.php?option=com_zefaniabible&view=zefaniacommentdetail', 
			$vName == 'zefaniacommentdetail'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIAVERSEOFDAY'), 
			'index.php?option=com_zefaniabible&view=zefaniaverseofday', 
			$vName == 'zefaniaverseofday'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIAREADING'), 
			'index.php?option=com_zefaniabible&view=zefaniareading', 
			$vName == 'zefaniareading'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIAREADINGDETAILS'), 
			'index.php?option=com_zefaniabible&view=zefaniareadingdetails', 
			$vName == 'zefaniareadingdetails'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_CROSSREF'), 
			'index.php?option=com_zefaniabible&view=zefaniacrossref', 
			$vName == 'zefaniacrossref'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIAUSER'), 
			'index.php?option=com_zefaniabible&view=zefaniauser', 
			$vName == 'zefaniauser'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_ZEFANIABIBLE_SUBMENU_ZEFANIABIBLE_ZEFANIAPUBLISH'), 
			'index.php?option=com_zefaniabible&view=zefaniapublish', 
			$vName == 'zefaniapublish'
		);

	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_zefaniabible';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	

}