<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Zefaniabible component
 */
class com_zefaniabibleInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_zefaniabible');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $type is the type of change (install, update or discover_install)

		//TODO : WRITE HERE YOUR CODE
		echo '';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// $type is the type of change (install, update or discover_install)

		//TODO : WRITE HERE YOUR CODE
		echo '';
	}
}