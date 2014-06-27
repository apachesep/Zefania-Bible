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
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
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
	* Method to install the embedded third extensions.
	*
	* @access	private
	* @param	JAdapterInstance	$adapter	Installer Component Adapter.
	* @return	void
	*
	* @since	Cook 2.6
	*/
	private function installExtensions(JAdapterInstance $adapter)
	{
		$dir = $adapter->getParent()->getPath('source') .DS. 'extensions';

		$installResults = array();

		jimport('joomla.filesystem.folder');
		$folders = JFolder::folders($dir);

		foreach($folders as $folder)
		{
			$source = $dir .DS. $folder;
		    $installer = new JInstaller;
		    $installResults[] = $installer->install($source);
		}
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
				$txtAction = JText::_('Installing');
		
				//Install all extensions contained in 'extensions' directory
				$this->installExtensions($adapter);
				break;
		
			case 'update':
				$txtAction = JText::_('Updating');

				//Install all extensions contained in 'extensions' directory
				$this->installExtensions($adapter);
				break;
	
			case 'uninstall':
				$txtAction = JText::_('Uninstalling');
		
				//Install all extensions contained in 'extensions' directory
				$this->uninstallExtensions($adapter);
				break;
	
		}

		$app = JFactory::getApplication();
		$txtComponent = JText::_('ZefaniaBible');
		$app->enqueueMessage(JText::sprintf('%s %s was successfull.', $txtAction, $txtComponent));
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


}



