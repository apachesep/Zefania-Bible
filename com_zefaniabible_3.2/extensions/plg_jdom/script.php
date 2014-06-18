<?php
/**
 * @copyright	Copyright (C) 2013 G. Tomaselli, Inc. All rights reserved.
 * @author		G. Tomaselli - http://bygiro.com - girotomaselli@gmail.com
 * @license     GNU General Public License version 2 or later.
 * JDOM library by j-cook service http://j-cook.pro
 */
 
defined('_JEXEC') or die;
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
@define("DS", DIRECTORY_SEPARATOR);

class plgsystemjdomInstallerScript
{
	var $old_params = array();

	/**
	* Constructor
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*/
	public function __construct(JAdapterInstance $adapter)
	{
		$this->old_params = self::getStoredParams();
	}
 
	/**
	* Called before any type of action
	*
	* @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function preflight($route, JAdapterInstance $adapter)
	{

	}
 
	/**
	* Called after any type of action
	*
	* @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function postflight($route, JAdapterInstance $adapter)
	{
		$app = JFactory::getApplication();
		
		$install_jdom = false;
		$remove_jdom = false;
		$manifest = $adapter->get('manifest');
		$old_params = $this->old_params;

		$parent = $adapter->get('parent');

		$paths = $parent->get('paths');
		if (!$paths)
			$paths = $parent->get('_paths'); //Legacy 2.5

				
		$src = $paths['source'] . DS . 'jdom';
		$dest = JPATH_SITE . DS . 'libraries' . DS . 'jdom';
		
		$params = array();
		$params['update'] = '1';
		
		switch($route){
			case 'install':
				$params = array();
				$params['update'] = '1';
				$params['jdomversion'] = $manifest->jdomversion;
				
				// clean installation
				$install_jdom = true;
				
				// enable plugin
				self::enablePlugin();
			break;
			
			case 'uninstall':
				// remove the jdom library folder
				$remove_jdom = true;
			break;
			
			case 'update':
				switch(intVal($old_params['update'])){
					case 0:						
					break;

					case 2:
						$vers_compare = version_compare( $manifest->jdomversion, $old_params['jdomversion'] );
						if( $vers_compare > 0) {
							// clean update jdom folder
							$remove_jdom = true;
							$install_jdom = true;								
						} elseif($vers_compare < 0){
							$params['jdomversion'] = $old_params['jdomversion'];							
						}
					break;
					

					case 1:
					default:
						// clean update jdom folder
						$remove_jdom = true;
						$install_jdom = true;
						$params['jdomversion'] = $manifest->jdomversion;
					break;						
				}

			break;
			
			default:
			break;
		}
		
		// remove JDOM
		if($remove_jdom and file_exists($dest)){
			if(JFolder::delete($dest)){
				$app->enqueueMessage(JText::_("PLG_JDOM_SUCCESSFULL_REMOVED"));
			} else {
				$app->enqueueMessage(JText::_("PLG_JDOM_ERROR_REMOVED"), 'error');
			}
		}
		
		// install JDOM
		if($install_jdom){
			if(JFolder::copy($src, $dest, '', true)){
				$app->enqueueMessage(JText::sprintf('PLG_JDOM_SUCCESSFULL_INSTALLATION', $params['jdomversion']));
				self::setParams($params);
			} else {
				$app->enqueueMessage(JText::_("PLG_JDOM_ERROR_INSTALLATION"), 'error');
			}
		}
	}
 
	/**
	* Called on installation
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function install(JAdapterInstance $adapter)
	{
	
	}
 
	/**
	* Called on update
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*
	* @return  boolean  True on success
	*/
	public function update(JAdapterInstance $adapter)
	{
		
	}
 
	/**
	* Called on uninstallation
	*
	* @param   JAdapterInstance  $adapter  The object responsible for running this script
	*/
	public function uninstall(JAdapterInstance $adapter)
	{
		// We want to call postflight after uninstall
		self::postflight('uninstall', $adapter);
	}
	

	/**
	* Get the manifest cache.
	*
	* @param   string  $extension  The extension alias in #__extensions table
	*/
	function getManifest($extension = 'jdom')
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE type="plugin" AND element = "'. $extension .'"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest;
	}
	

	/**
	* Get the component params.
	*
	* @param   string  $extension  The extension alias in #__extensions table
	*/
	function getStoredParams($extension = 'jdom')
	{
		$db = JFactory::getDbo();
		$db->setQuery('SELECT params FROM #__extensions WHERE type="plugin" AND element = "'. $extension .'"');
		$params = json_decode( $db->loadResult(), true );
		return $params;
	}

	/**
	* Enable the plugin.
	*
	* @param   string  $extension  The extension alias in #__extensions table
	*/	
	function enablePlugin($extension = 'jdom')
	{
		$db = JFactory::getDbo();
		
		$db->setQuery('UPDATE #__extensions SET enabled = 1 WHERE type="plugin" AND element = "'. $extension .'"' );
		$db->query();
	}
	
	/**
	* Sets parameter values in the extension's row of the extension table.
	*
	* @param   array  $param_array  Parameters
	* @param   string  $newInstall  Define if this install is new (merge the params with previous)
	* @param   string  $extension  The extension alias in #__extensions table
	*/
	function setParams($param_array, $newInstall = null, $extension = 'jdom')
	{
		if ( count($param_array) <= 0 ) {
			return;
		}
		
		// read the existing component value(s)
		$db = JFactory::getDbo();
		
		$params = self::getStoredParams();
		// add the new variable(s) to the existing one(s)
		foreach ( $param_array as $name => $value ) {
			$params[ (string) $name ] = (string) $value;
		}
		
		if($newInstall){
			// store the new values as a JSON string
			$paramsString = json_encode( $param_array );
		} else {
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode( $params );
		}
		$db->setQuery('UPDATE #__extensions SET params = ' .
			$db->quote( $paramsString ) .
			' WHERE type="plugin" AND element = "'. $extension .'"' );
		$db->query();
	}
}