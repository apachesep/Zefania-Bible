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
defined('_JEXEC') or die('Restricted access');

defined('DS') or define("DS", DIRECTORY_SEPARATOR);


/**
* Script file of Zefaniabible component
*
* @package	Zefaniabible
* @subpackage	Installer
*/
class com_zefaniabibleInstallerScript
{
	/**
	* Called on installation
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function install(JAdapterInstance $adapter)
	{
		$adapter->getParent()->setRedirectURL('index.php?option=com_zefaniabible');
	}



	/**
	* Called after any type of action.
	*
	* @access	public
	* @param	string	$type	Type.
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function postflight($type, JAdapterInstance $adapter)
	{
		switch($type)
		{
			case 'install':
				//$txtAction = JText::_('Installing');
		
				break;
		
			case 'update':
				//$txtAction = JText::_('Updating');
				break;
	
			case 'uninstall':
				//$txtAction = JText::_('Uninstalling');
				break;
	
		}
		$app = JFactory::getApplication();
//		$txtComponent = JText::_('ZefaniaBible');
//		$app->enqueueMessage(JText::sprintf('%s %s was successfull.', $txtAction, $txtComponent));
	}
	/**
	* Called before any type of action
	*
	* @access	public
	* @param	string	$type	Type.
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function preflight($type, JAdapterInstance $adapter)
	{

	}

	/**
	* Called on uninstallation
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function uninstall(JAdapterInstance $adapter)
	{
		// We run postflight also after uninstalling
		self::postflight('uninstall', $adapter);

	}

	/**
	* Method to uninstall the embedded third extensions.
	*
	* @access	private
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	Cook 2.6
	*/
	private function uninstallExtensions(JAdapterInstance $adapter)
	{

	}

	/**
	* Called on update
	*
	* @access	public
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	1.6
	*/
	public function update(JAdapterInstance $adapter)
	{
		$adapter->getParent()->setRedirectURL('index.php?option=com_zefaniabible');
	}
	private function fnc_remove_langauge_folders()
	{
		// get zefaniaBible version
		$xmlFile = str_replace("com_", "", $option).'.xml';
		$xmlElement = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/'.$option.'/'.$xmlFile);
		if($xmlElement)
		{
			$str_version = (string) $xmlElement->version;
		}	
		// remove langauge folder for less than 3.1.3 version.
		if($str_version <= "3.1.3")
		{
			$app = JFactory::getApplication();
			jimport( 'joomla.filesystem.folder' );
			$arr_paths[0] = 'components/com_zefaniabible/language';
			$arr_paths[1] = 'administrator/components/com_zefaniabible/language';
			$arr_paths[2] = 'modules/mod_readingplan/language';
			$arr_paths[3] = 'modules/mod_verseoftheday/language';
			$arr_paths[4] = 'modules/mod_zefaniasubscribe/language';
			$arr_paths[5] = 'plugins/content/zefaniascripturelinks/language';
			$arr_paths[6] = 'plugins/search/zefaniabible/language';
			$arr_paths[7] = 'plugins/editors-xtd/zefaniabible/language';
			$arr_paths[8] = 'plugins/system/zefaniaemail/language';
			$arr_paths[9] = 'plugins/system/autotweetzefaniabible/language';									
			
			foreach ($arr_paths as $str_path )
			{
				if(JFolder::exists($str_path) == true)
				{
					$app->enqueueMessage(JText::sprintf('%s has been deleted.', $str_path));
					JFolder::delete($str_path);
				}
			}
		}
	}

}



