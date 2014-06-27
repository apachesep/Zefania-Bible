<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Contents
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



/**
* Zefaniabible Helper functions.
*
* @package	Zefaniabible
* @subpackage	Helper
*/
class ZefaniabibleCkHelper
{
	/**
	* Cache for ACL actions
	*
	* @var object
	*/
	protected static $acl = null;

	/**
	* Directories aliases.
	*
	* @var array
	*/
	protected static $directories;

	/**
	* Determines when requirements have been loaded.
	*
	* @var boolean
	*/
	protected static $loaded = null;

	/**
	* Call a JS file. Manage fork files.
	*
	* @access	protected static
	* @param	JDocument	$doc	Document.
	* @param	string	$base	Component base from site root.
	* @param	string	$file	Component file.
	* @param	boolean	$replace	Replace the file or override. (Default : Replace)
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected static function addScript($doc, $base, $file, $replace = true)
	{
		$url = JURI::root(true) . '/' . $base . '/' . $file;
		$url = str_replace(DS, '/', $url);
		
		$urlFork = null;
		if (file_exists(JPATH_SITE .DS. $base .DS. 'fork' .DS. $file))
		{
			$urlFork = JURI::root(true) . '/' . $base . '/fork/' . $file;
			$urlFork = str_replace(DS, '/', $urlFork);
		}

		if ($replace && $urlFork)
			$url = $urlFork;

		$doc->addScript($url);

		if (!$replace && $urlFork)
			$doc->addScript($urlFork);
	}

	/**
	* Call a CSS file. Manage fork files.
	*
	* @access	protected static
	* @param	JDocument	$doc	Document.
	* @param	string	$base	Component base from site root.
	* @param	string	$file	Component file.
	* @param	boolean	$replace	Replace the file or override. (Default : Override)
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected static function addStyleSheet($doc, $base, $file, $replace = false)
	{
		$url = JURI::root(true) . '/' . $base . '/' . $file;
		$url = str_replace(DS, '/', $url);

		$urlFork = null;
		if (file_exists(JPATH_SITE .DS. $base .DS. 'fork' .DS. $file))
		{
			$urlFork = JURI::root(true) . '/' . $base . '/fork/' . $file;
			$urlFork = str_replace(DS, '/', $urlFork);
		}

		if ($replace && $urlFork)
			$url = $urlFork;

		$doc->addStyleSheet($url);

		if (!$replace && $urlFork)
			$doc->addStyleSheet($urlFork);
	}

	/**
	* Configure the Linkbar.
	*
	* @access	public static
	* @param	varchar	$view	The name of the active view.
	* @param	varchar	$layout	The name of the active layout.
	* @param	varchar	$alias	The name of the menu. Default : 'menu'
	* @return	void
	*
	* @since	1.6
	*/
	public static function addSubmenu($view, $layout, $alias = 'menu')
	{
		$items = self::getMenuItems();

		// Will be handled in XML in future (or/and with the Joomla native menus)
		// -> give your opinion on j-cook.pro/forum

		
		$client = 'admin';
		if (JFactory::getApplication()->isSite())
			$client = 'site';
	
		$links = array();
		switch($client)
		{
			case 'admin':
				switch($alias)
				{
					case 'cpanel':
					case 'menu':
					default:
						$links = array(
							'admin.biblenames.default',
							'admin.biblenames.scripture',
							'admin.zefaniacomment.default',
							'admin.zefaniareading.default',
							'admin.zefaniareadingdetails.default',
							'admin.zefaniauser.default',
							'admin.zefaniaverseofday.default',
							'admin.zefaniabibledictionaryinfo.default'
						);
								
						if ($alias != 'cpanel')
							array_unshift($links, 'admin.cpanel');
					
						break;
				}
				break;
		
			case 'site':
				switch($alias)
				{
					case 'cpanel':
					case 'menu':
					default:
						$links = array(
							'site.biblenames.plan',
							'site.biblenames.default'
						);
								
						if ($alias != 'cpanel')
							array_unshift($links, 'site.cpanel');
					
						break;
				}
				break;
		}


		//Compile with selected items in the right order
		$menu = array();
		foreach($links as $link)
		{
			if (!isset($items[$link]))
				continue;	// Not found
		
			$item = $items[$link];
	
			// Menu link
			$extension = 'com_zefaniabible';
			if (isset($item['extension']))
				$extension = $item['extension'];
	
			$url = 'index.php?option=' . $extension;
			if (isset($item['view']))
				$url .= '&view=' . $item['view'];
			if (isset($item['layout']))
				$url .= '&layout=' . $item['layout'];
	
			// Is active
			$active = ($item['view'] == $view);
			if (isset($item['layout']))
				$active = $active && ($item['layout'] == $layout);
	
			// Reconstruct it the Joomla format
			$menu[] = array(JText::_($item['label']), $url, $active, $item['icon']);

		}

		$version = new JVersion();
		//Create the submenu in the old fashion way
		if (version_compare($version->RELEASE, '3.0', '<'))
		{
			$html = "";	
			// Prepare the submenu module
			foreach ($menu as $entry )
				JSubMenuHelper::addEntry($entry[0], $entry[1], $entry[2]);
		}

		return $menu;
	}

	/**
	* Gets a list of the actions that can be performed.
	*
	* @access	public static
	*
	* @return	JObject	An ACL object containing authorizations
	*
	* @deprecated	Cook 2.0
	*/
	public static function getAcl()
	{
		return self::getActions();
	}

	/**
	* Gets a list of the actions that can be performed.
	*
	* @access	public static
	* @param	integer	$itemId	The item ID.
	*
	* @return	JObject	An ACL object containing authorizations
	*
	* @since	1.6
	*/
	public static function getActions($itemId = 0)
	{
		if (isset(self::$acl))
			return self::$acl;

		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.state',
			'core.edit.own',
			'core.delete',
			'core.delete.own',
			'core.view.own',
		);

		foreach ($actions as $action)
			$result->set($action, $user->authorise($action, COM_ZEFANIABIBLE));

		self::$acl = $result;

		return $result;
	}

	/**
	* Return the directories aliases full paths
	*
	* @access	public static
	*
	* @return	array	Arrays of aliases relative path from site root.
	*
	* @since	2.6.4
	*/
	public static function getDirectories()
	{
		if (!empty(self::$directories))
			return self::$directories;

		$comAlias = "com_zefaniabible";
		$configMedias = JComponentHelper::getParams('com_media');
		$config = JComponentHelper::getParams($comAlias);

		$directories = array(
			'DIR_BIBLENAMES_XML_BIBLE_FILE_LOCATION' => $config->get("upload_dir_biblenames_xml_bible_file_location", "[COM_SITE]" .DS. "files" .DS. "biblenames_xml_bible_file_location"),
			'DIR_BIBLENAMES_XML_AUDIO_FILE_LOCATION' => $config->get("upload_dir_biblenames_xml_audio_file_location", "[COM_SITE]" .DS. "files" .DS. "biblenames_xml_audio_file_location"),
			'DIR_ZEFANIACOMMENT_FILE_LOCATION' => $config->get("upload_dir_zefaniacomment_file_location", "[COM_SITE]" .DS. "files" .DS. "zefaniacomment_file_location"),
			'DIR_ZEFANIABIBLEDICTIONARYINFO_XML_FILE_URL' => $config->get("upload_dir_zefaniabibledictionaryinfo_xml_file_url", "[COM_SITE]" .DS. "files" .DS. "zefaniabibledictionaryinfo_xml_file_url"),
			'DIR_FILES' => "[COM_SITE]" .DS. "files",
			'DIR_TRASH' => $config->get("trash_dir", 'images' . DS . "trash"),
		);

		$bases = array(
			'COM_ADMIN' => "administrator" .DS. 'components' .DS. $comAlias,
			'ADMIN' => "administrator",
			'COM_SITE' => 'components' .DS. $comAlias,
			'IMAGES' => $config->get('image_path', 'images'),
			'MEDIAS' => $configMedias->get('file_path', 'images'),
			'ROOT' => '',

		);



		// Parse the directory aliases
		foreach($directories as $alias => $directory)
		{
			// Parse the component base folders
			foreach($bases as $aliasBase => $directoryBase)
				$directories[$alias] = preg_replace("/\[" . $aliasBase . "\]/", $directoryBase, $directories[$alias]);
	
			// Clean tags if remains
			$directories[$alias] = preg_replace("/\[.+\]/", "", $directories[$alias]);
		}

		self::$directories = $directories;
		return self::$directories;

	}

	/**
	* Get a file path or url depending of the method
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	string	$indirect	Method to access the file : [direct,indirect,physical]
	* @param	array	$options	File parameters.
	*
	* @return	string	File path or url
	*
	* @since	Cook 2.6.1
	*/
	public static function getFile($path, $indirect = 'physical', $options = null)
	{
		switch ($indirect)
		{
			case 'physical':	// Physical file on the drive (url is a path here)
				return ZefaniabibleClassFile::getPhysical($path, $options);
	
			case 'direct':		// Direct url
				return ZefaniabibleClassFile::getUrl($path, $options);
	
			case 'indirect':	// Indirect file access (through controller)
			default:
				return ZefaniabibleClassFile::getIndirectUrl($path, $options);
		}
	}

	/**
	* Extract usefull informations from the thumb creator.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	mixed	Array of various informations
	*
	* @since	Cook 2.6.1
	*/
	public static function getImageInfos($path, $options = null)
	{
		include_once(JPATH_ADMIN_ZEFANIABIBLE .DS. 'classes' .DS. 'images.php');

		$filename = self::getFile($path, 'physical', null);

		$mime = ZefaniabibleClassFile::getMime($filename);
		$thumb = new ZefaniabibleClassImage($filename, $mime);

		$attrs = isset($options['attrs'])?$options['attrs']:null;
		$w = isset($options['width'])?(int)$options['width']:0;
		$h = isset($options['height'])?(int)$options['height']:0;

		if ($attrs)
			$thumb->attrs($attrs);

		$thumb->width($w);
		$thumb->height($h);
		$info = $thumb->info();
		
		return $info;
	}

	/**
	* Get an indirect url to find image through model restrictions.
	*
	* @access	public static
	* @param	string	$view	List model name
	* @param	string	$key	Field name where is stored the filename
	* @param	string	$id	Item id
	* @param	array	$options	File parameters.
	*
	* @return	string	Indirect url
	*
	* @since	Cook 2.6.1
	*/
	public static function getIndexedFile($view, $key, $id, $options = null)
	{
		return ZefaniabibleClassFile::getIndexUrl($view, $key, $id, $options);
	}

	/**
	* Load all menu items.
	*
	* @access	public static
	* @return	void
	*
	* @since	Cook 2.0
	*/
	public static function getMenuItems()
	{
		// Will be handled in XML in future (or/and with the Joomla native menus)
		// -> give your opinion on j-cook.pro/forum

		$items = array();

		$items['admin.biblenames.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_BIBLES',
			'view' => 'biblenames',
			'layout' => 'default',
			'icon' => 'zefaniabible_biblenames'
		);

		$items['admin.biblenames.scripture'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_SCRIPTURE',
			'view' => 'biblenames',
			'layout' => 'scripture',
			'icon' => 'zefaniabible_biblenames'
		);

		$items['admin.zefaniacomment.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_COMMENTARIES',
			'view' => 'zefaniacomment',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniacomment'
		);

		$items['admin.zefaniareading.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_READING_PLAN',
			'view' => 'zefaniareading',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniareading'
		);

		$items['admin.zefaniareadingdetails.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_READING_PLAN_DETAILS',
			'view' => 'zefaniareadingdetails',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniareadingdetails'
		);

		$items['admin.zefaniauser.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_USERS',
			'view' => 'zefaniauser',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniauser'
		);

		$items['admin.zefaniaverseofday.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_VERSE_OF_DAY',
			'view' => 'zefaniaverseofday',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniaverseofday'
		);

		$items['admin.zefaniabibledictionaryinfo.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_DICTIONARY',
			'view' => 'zefaniabibledictionaryinfo',
			'layout' => 'default',
			'icon' => 'zefaniabible_zefaniabibledictionaryinfo'
		);

		$items['admin.cpanel'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_ZEFANIABIBLE',
			'view' => 'cpanel',
			'icon' => 'zefaniabible_cpanel'
		);

		$items['site.biblenames.plan'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_PLAN',
			'view' => 'biblenames',
			'layout' => 'plan',
			'icon' => 'zefaniabible_biblenames'
		);

		$items['site.biblenames.default'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_STANDARD',
			'view' => 'biblenames',
			'layout' => 'default',
			'icon' => 'zefaniabible_biblenames'
		);

		$items['site.cpanel'] = array(
			'label' => 'ZEFANIABIBLE_LAYOUT_ZEFANIABIBLE',
			'view' => 'cpanel',
			'icon' => 'zefaniabible_cpanel'
		);

		return $items;
	}

	/**
	* Defines the headers of your template.
	*
	* @access	public static
	*
	* @return	void	
	* @return	void
	*/
	public static function headerDeclarations()
	{
		if (self::$loaded)
			return;
	
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		$siteUrl = JURI::root(true);

		$baseSite = 'components' .DS. COM_ZEFANIABIBLE;
		$baseAdmin = 'administrator' .DS. 'components' .DS. COM_ZEFANIABIBLE;

		$componentUrl = $siteUrl . '/' . str_replace(DS, '/', $baseSite);
		$componentUrlAdmin = $siteUrl . '/' . str_replace(DS, '/', $baseAdmin);

		//Required libraries
		//jQuery Loading : Abstraction to handle cross versions of Joomla
		JDom::_('framework.jquery');
		JDom::_('framework.jquery.chosen');
		JDom::_('framework.bootstrap');
		JDom::_('html.icon.glyphicon');
		JDom::_('html.icon.icomoon');


		//Load the jQuery-Validation-Engine (MIT License, Copyright(c) 2011 Cedric Dugas http://www.position-absolute.com)
		self::addScript($doc, $baseAdmin, 'js' .DS. 'jquery.validationEngine.js');
		self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'validationEngine.jquery.css');
		ZefaniabibleHelperHtmlValidator::loadLanguageScript();



		//CSS
		if ($app->isAdmin())
		{


			self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'zefaniabible.css');
			self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'toolbar.css');

		}
		else if ($app->isSite())
		{
			self::addStyleSheet($doc, $baseSite, 'css' .DS. 'zefaniabible.css');
			self::addStyleSheet($doc, $baseSite, 'css' .DS. 'toolbar.css');

		}



		self::$loaded = true;
	}

	/**
	* Load the fork file. (Cook Self Service concept)
	*
	* @access	public static
	* @param	string	$file	Current file to fork.
	* @return	void
	*
	* @since	2.6.3
	*/
	public static function loadFork($file)
	{
		//Transform the file path to reach the fork directory
		$file = preg_replace("#com_zefaniabible#", 'com_zefaniabible' .DS. 'fork', $file);

		// Load the fork file.
		if (!empty($file) && file_exists($file))
			include_once($file);
	}

	/**
	* Recreate the URL with a redirect in order to : -> keep an good SEF ->
	* always kill the post -> precisely control the request
	*
	* @access	public static
	* @param	array	$vars	The array to override the current request.
	*
	* @return	string	Routed URL.
	*/
	public static function urlRequest($vars = array())
	{
		$parts = array();

		// Authorisated followers
		$authorizedInUrl = array(
					'option' => null, 
					'view' => null, 
					'layout' => null, 
					'Itemid' => null, 
					'tmpl' => null,
					'object' => null,
					'lang' => null);

		$jinput = JFactory::getApplication()->input;

		$request = $jinput->getArray($authorizedInUrl);

		foreach($request as $key => $value)
			if (!empty($value))
				$parts[] = $key . '=' . $value;

		$cid = $jinput->get('cid', array(), 'ARRAY');
		if (!empty($cid))
		{
			$cidVals = implode(",", $cid);
			if ($cidVals != '0')
				$parts[] = 'cid[]=' . $cidVals;
		}

		if (count($vars))
		foreach($vars as $key => $value)
			$parts[] = $key . '=' . $value;

		return JRoute::_("index.php?" . implode("&", $parts), false);
	}


}

// Load the fork
ZefaniabibleCkHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleHelper')){ class ZefaniabibleHelper extends ZefaniabibleCkHelper{} }

