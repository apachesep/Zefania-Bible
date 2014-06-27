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



/**
* JLoader Class for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Class
*/
class ZefaniabibleCkClassLoader extends JLoader
{
	/**
	* Holds proxy classes and the class names the proxy.
	*
	* @var array
	*/
	protected static $classAliases;

	/**
	* Container for already imported library paths.
	*
	* @var array
	*/
	protected static $imported;

	/**
	* Container for namespace => path map.
	*
	* @var array
	*/
	protected static $namespaces;

	/**
	* Container for registered library class prefixes and path lookups.
	*
	* @var array
	*/
	protected static $prefixes;

	/**
	* Autoload a class based on name.
	*
	* @access	private static
	* @param	string	$class	The fully qualified class name to autoload.
	*
	* @return	boolean	True on success, false otherwise.
	*
	* @since	11.3
	*/
	private static function _autoload($class)
	{
		foreach (self::$prefixes as $prefix => $lookup)
		{
			$chr = strlen($prefix) < strlen($class) ? $class[strlen($prefix)] : 0;

			if (strpos($class, $prefix) === 0 && ($chr === strtoupper($chr)))
			{
				return self::_load(substr($class, strlen($prefix)), $lookup);
			}
		}

		return false;
	}

	/**
	* Load a class based on name and lookup array.
	*
	* @access	private static
	* @param	string	$class	The fully qualified class name to autoload.
	* @param	string	$lookup	The array of base paths to use for finding the class file.
	*
	* @return	boolean	True if the class was loaded, false otherwise.
	*
	* @since	12.1
	*/
	private static function _load($class, $lookup)
	{
		// Split the class name into parts separated by camelCase.
		$parts = preg_split('/(?<=[a-z0-9])(?=[A-Z])/x', $class);

		// If there is only one part we want to duplicate that part for generating the path.
		$parts = (count($parts) === 1) ? array($parts[0], $parts[0]) : $parts;

		foreach ($lookup as $base)
		{
			// Generate the path based on the class name parts.
			$path = $base . '/' . implode('/', array_map('strtolower', $parts)) . '.php';

			// Load the file if it exists.
			if (file_exists($path))
			{
				return include $path;
			}
		}

		return false;
	}

	/**
	* Reduce a path, replacing the site root by aliases. Minimalist for the
	* moment.
	*
	* @access	private static
	* @param	string	$path	path to reduce
	*
	* @return	string	Aliased path.
	*
	* @since	Cook 2.6.3
	*/
	private static function aliasDir($path)
	{
		$path = str_replace(JPATH_SITE, '.', $path);
		return $path;
	}

	/**
	* Method to discover classes of a given type in a given path.
	* 
	* Joomla fix. This should go in the core.
	*
	* @access	public static
	* @param	string	$classPrefix	The class name prefix to use for discovery.
	* @param	string	$parentPath	Full path to the parent folder for the classes to discover.
	* @param	boolean	$force	True to overwrite the autoload path value for the class if it already exists.
	* @param	boolean	$recurse	Recurse through all child directories as well as the parent path.
	* @return	void
	*
	* @since	Cook 2.6.3
	*/
	public static function discover($classPrefix, $parentPath, $force = true, $recurse = false)
	{
		try
		{			
			if ($recurse)
			{
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator($parentPath),
					RecursiveIteratorIterator::SELF_FIRST
				);
			}
			else
			{
				$iterator = new DirectoryIterator($parentPath);
			}

			foreach ($iterator as $file)
			{
				// Filename can be also a folder name
				$fileName = $file->getFilename();

				// Trim the base path
				$relativePath = substr($file->getPath(), strlen($parentPath) +1);

				// Transform the directories in array
				$namespace = explode(DS, $relativePath);


				// Ignored folders
				if (in_array($namespace[0], array(
						'_fork', 							// Because thoses directory are never exectuded (samples files)
						'legacy', 							// Because legacy is not always required
						'models', 'views', 'controllers'	// Because MVC files are properly loaded and depends of the client (site/admin) folder
					)))
					continue;

				// Singularise the class name
				for ($i = 0 ; $i < count($namespace) ; $i ++)
				{
					$val = $namespace[$i];
					switch($val)
					{
						case 'rules': $val = 'rule'; break;
		
						// Remove 's' or 'es' folder suffixes (classES, controllerS, modelS, ...)
						// ex : classes/models/item > classmodelitem
						default : $val = preg_replace('#e?s$#', '', $val); break;
					}

					// Exclude 'fork' from class name
					if (($i > 0) || ($val != 'fork'))
						$namespace[$i] = $val;					
				}

				// Only load for php files.
				// Note: DirectoryIterator::getExtension only available PHP >= 5.3.6
				if ($file->isFile() && substr($fileName, strrpos($fileName, '.') + 1) == 'php')
				{
					// Add the filename as the last namespace part value.
					$name = preg_replace('#\.php$#', '', $fileName);
	
					// Remove doubled ends. ex : class/file/file > classfile
					if ($name != $namespace[count($namespace)-1])
						$namespace[] = $name;
		
					//build the class name. Notice that is all lowercase;
					$class = strtolower($classPrefix . implode('', $namespace));



					// Register the class with the autoloader if not already registered or the force flag is set.
					$classes = self::getClassList();
					if (empty($classes[$class]) || $force)
					{
						self::register($class, $file->getPath() .DS. $fileName);
					}
				}
			}
		}
		catch (UnexpectedValueException $e)
		{
			// Exception will be thrown if the path is not a directory. Ignore it.
		}
	}

	/**
	* Dump the current loaded and registrated files.
	*
	* @access	public static
	*
	* @return	string	Rendered HTML.
	*
	* @since	Cook 2.6.3
	*/
	public static function dump()
	{
		$jinput = JFactory::getApplication()->input;
		if ($jinput->get('task') == 'file')
			return;

		$html = '<h2>Prefixes</h2>' . '<hr/>';

		if (self::$prefixes && count(self::$prefixes))
		{
			ksort(self::$prefixes);
			foreach(self::$prefixes as $prefix => $stack)
			{
				$html .= '<h3>' .$prefix. '</h3>';
				foreach($stack as $path)
					$html .= str_replace(JPATH_SITE, '.', $path) .BR;
			}
	
		}

		$html .= '<br/><h2>getClassList</h2>' . '<hr/>';
		$classes = self::getClassList();
		if ($classes && count($classes))
		{
			ksort($classes);

			foreach($classes as $name => $path)
			{
				$html .= '<h4>' .$name. '</h4>';

				$color = '000';
				if (!file_exists($path))
					$color = 'F00';

				$html .= '<span style="color:#' . $color . '">'
					.	self::aliasDir($path)
					.	'</span>' . BR;
			}
	
		}

		// imported classes  jimport()
		$html .= '<br/><h2>Imported</h2>' . '<hr/>';
		if (self::$imported && count(self::$imported))
		{			
			ksort(self::$imported);
			foreach(self::$imported as $namespace => $include)
			{
				if (!$include)
					continue;

				$html .= "jimport('<strong>" . $namespace . "</strong>');" .BR;	
			}
	
		}

		echo $html;
	}

	/**
	* Method to get the list of registered classes and their respective file
	* paths for the autoloader.
	*
	* @access	public static
	*
	* @return	array	The array of class => path values for the autoloader.
	*
	* @since	11.1
	*/
	public static function getClassList()
	{
		if (isset(self::$_classes))
			return self::$_classes;

		return self::$classes;
	}

	/**
	* Method to get the list of registered namespaces.
	*
	* @access	public static
	*
	* @return	array	The array of namespace => path values for the autoloader.
	*
	* @since	12.3
	*/
	public static function getNamespaces()
	{
		return self::$namespaces;
	}

	/**
	* Loads a class from specified directories.
	*
	* @access	public static
	* @param	string	$key	The class name to look for (dot notation).
	* @param	string	$base	Search this directory for the class.
	*
	* @return	boolean	True on success.
	*
	* @since	11.1
	*/
	public static function import($key, $base = null)
	{
		// Only import the library if not already attempted.
		if (!isset(self::$imported[$key]))
		{
			// Setup some variables.
			$success = false;
			$parts = explode('.', $key);
			$class = array_pop($parts);
			$base = (!empty($base)) ? $base : __DIR__;
			$path = str_replace('.', DIRECTORY_SEPARATOR, $key);

			// Handle special case for helper classes.
			if ($class == 'helper')
			{
				$class = ucfirst(array_pop($parts)) . ucfirst($class);
			}
			// Standard class.
			else
			{
				$class = ucfirst($class);
			}

			// If we are importing a library from the Joomla namespace set the class to autoload.
			if (strpos($path, 'joomla') === 0)
			{
				// Since we are in the Joomla namespace prepend the classname with J.
				$class = 'J' . $class;

				// Only register the class for autoloading if the file exists.
				if (is_file($base . '/' . $path . '.php'))
				{
					self::$classes[strtolower($class)] = $base . '/' . $path . '.php';
					$success = true;
				}
			}
			/*
			 * If we are not importing a library from the Joomla namespace directly include the
			 * file since we cannot assert the file/folder naming conventions.
			 */
			else
			{
				// If the file exists attempt to include it.
				if (is_file($base . '/' . $path . '.php'))
				{
					$success = (bool) include_once $base . '/' . $path . '.php';
				}
			}

			// Add the import key to the memory cache container.
			self::$imported[$key] = $success;
		}

		return self::$imported[$key];
	}

	/**
	* Loads a class from specified directories.
	*
	* @access	public static
	* @param	string	$class	The class to be loaded.
	*
	* @return	boolean	True on success.
	*
	* @since	11.1
	*/
	public static function load($class)
	{
		// Sanitize class name.
		$class = strtolower($class);

		// If the class already exists do nothing.
		if (class_exists($class, false))
		{
			return true;
		}

		// If the class is registered include the file.
		if (isset(self::$classes[$class]))
		{
			include_once self::$classes[$class];

			return true;
		}

		return false;
	}

	/**
	* Method to autoload classes that have been aliased using the registerAlias
	* method.
	*
	* @access	public static
	* @param	string	$class	The fully qualified class name to autoload.
	*
	* @return	boolean	True on success, false otherwise.
	*
	* @since	3.2
	*/
	public static function loadByAlias($class)
	{
		// Remove the root backslash if present.
		if ($class[0] == '\\')
		{
			$class = substr($class, 1);
		}

		if (isset(self::$classAliases[$class]))
		{
			class_alias(self::$classAliases[$class], $class);
		}
	}

	/**
	* Method to autoload classes that are namespaced to the PSR-0 standard.
	*
	* @access	public static
	* @param	string	$class	The fully qualified class name to autoload.
	*
	* @return	boolean	True on success, false otherwise.
	*
	* @since	13.1
	*/
	public static function loadByPsr0($class)
	{
		// Remove the root backslash if present.
		if ($class[0] == '\\')
		{
			$class = substr($class, 1);
		}

		// Find the location of the last NS separator.
		$pos = strrpos($class, '\\');

		// If one is found, we're dealing with a NS'd class.
		if ($pos !== false)
		{
			$classPath = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0, $pos)) . DIRECTORY_SEPARATOR;
			$className = substr($class, $pos + 1);
		}
		// If not, no need to parse path.
		else
		{
			$classPath = null;
			$className = $class;
		}

		$classPath .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		// Loop through registered namespaces until we find a match.
		foreach (self::$namespaces as $ns => $paths)
		{
			if (strpos($class, $ns) === 0)
			{
				// Loop through paths registered to this namespace until we find a match.
				foreach ($paths as $path)
				{
					$classFilePath = $path . DIRECTORY_SEPARATOR . $classPath;

					// We check for class_exists to handle case-sensitive file systems
					if (file_exists($classFilePath) && !class_exists($class, false))
					{
						return (bool) include_once $classFilePath;
					}
				}
			}
		}

		return false;
	}

	/**
	* Directly register a class to the autoload list.
	*
	* @access	public static
	* @param	string	$class	The class name to register.
	* @param	string	$path	Full path to the file that holds the class to register.
	* @param	boolean	$force	True to overwrite the autoload path value for the class if it already exists.
	* @return	void
	*
	* @since	11.1
	*/
	public static function register($class, $path, $force = true)
	{
		// Sanitize class name.
		$class = strtolower($class);

		// Only attempt to register the class if the name and file exist.
		if (!empty($class) && is_file($path))
		{
			$classes = self::getClassList();
			if (empty($classes[$class]) || $force)
			{		
				if (isset(self::$_classes))
					self::$_classes[$class] = $path;
				else
					self::$classes[$class] = $path;
			}
		}
	}

	/**
	* Offers the ability for "just in time" usage of `class_alias()`.
	*
	* @access	public static
	* @param	string	$alias	The alias name to register.
	* @param	string	$original	The original class to alias.
	*
	* @return	boolean	True if registration was successful. False if the alias already exists.
	*
	* @since	3.2
	*/
	public static function registerAlias($alias, $original)
	{
		if (!isset(self::$classAliases[$alias]))
		{
			self::$classAliases[$alias] = $original;

			return true;
		}

		return false;
	}

	/**
	* Register a namespace to the autoloader. When loaded, namespace paths are
	* searched in a "last in, first out" order.
	*
	* @access	public static
	* @param	string	$namespace	A case sensitive Namespace to register.
	* @param	string	$path	A case sensitive absolute file path to the library root where classes of the given namespace can be found.
	* @param	boolean	$reset	True to reset the namespace with only the given lookup path.
	* @param	boolean	$prepend	If true, push the path to the beginning of the namespace lookup paths array.
	* @return	void
	*
	* @since	3.2
	*/
	public static function registerNamespace($namespace, $path, $reset = false, $prepend = false)
	{
		// Verify the library path exists.
		if (!file_exists($path))
		{
			throw new RuntimeException('Library path ' . $path . ' cannot be found.', 500);
		}

		// If the namespace is not yet registered or we have an explicit reset flag then set the path.
		if (!isset(self::$namespaces[$namespace]) || $reset)
		{
			self::$namespaces[$namespace] = array($path);
		}

		// Otherwise we want to simply add the path to the namespace.
		else
		{
			if ($prepend)
			{
				array_unshift(self::$namespaces[$namespace], $path);
			}
			else
			{
				self::$namespaces[$namespace][] = $path;
			}
		}
	}

	/**
	* Register a class prefix with lookup path.
	*
	* @access	public static
	* @param	string	$prefix	The class prefix to register.
	* @param	string	$path	Absolute file path to the library root where classes with the given prefix can be found.
	* @param	boolean	$reset	True to reset the prefix with only the given lookup path.
	* @param	boolean	$prepend	If true, push the path to the beginning of the prefix lookup paths array.
	* @return	void
	*
	* @since	12.1
	*/
	public static function registerPrefix($prefix, $path, $reset = false, $prepend = false)
	{
		// Verify the library path exists.
		if (!file_exists($path))
		{
			throw new RuntimeException('Library path ' . $path . ' cannot be found.', 500);
		}

		// If the prefix is not yet registered or we have an explicit reset flag then set set the path.
		if (!isset(self::$prefixes[$prefix]) || $reset)
		{
			self::$prefixes[$prefix] = array($path);
		}
		// Otherwise we want to simply add the path to the prefix.
		else
		{
			if ($prepend)
			{
				array_unshift(self::$prefixes[$prefix], $path);
			}
			else
			{
				self::$prefixes[$prefix][] = $path;
			}
		}
	}

	/**
	* Method to setup the autoloaders for the Joomla Platform. Cook Component
	* Override.
	*
	* @access	public static
	* @param	boolean	$enablePsr	True to enable autoloading based on PSR-0.
	* @param	boolean	$enablePrefixes	True to enable prefix based class loading (needed to auto load the Joomla core).
	* @param	boolean	$enableClasses	True to enable class map based class loading (needed to auto load the Joomla core).
	* @param	boolean	$enableComponentLoader	Cook implementation : True to enable component super class loading (not used for the moment).
	* @return	void
	*
	* @since	12.3
	*/
	public static function setup($enablePsr = true, $enablePrefixes = true, $enableClasses = true, $enableComponentLoader = false)
	{
		if ($enableClasses)
		{
			// Register the class map based autoloader.
			spl_autoload_register(array('JLoader', 'load'));
		}

		if ($enablePrefixes)
		{
			// Register the J prefix and base path for Joomla platform libraries.
			self::registerPrefix('J', JPATH_PLATFORM . '/joomla');

			// Register the prefix autoloader.
			spl_autoload_register(array('JLoader', '_autoload'));
		}

		if ($enablePsr)
		{
			// Register the PSR-0 based autoloader.
			spl_autoload_register(array('JLoader', 'loadByPsr0'));
			spl_autoload_register(array('JLoader', 'loadByAlias'));
		}

		// Cook Self Service : Auto load using the component super class. Not used for the moment
		if ($enableComponentLoader)
			spl_autoload_register(array('ZefaniabibleClassLoader', 'load'));
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassLoader')){ class ZefaniabibleClassLoader extends ZefaniabibleCkClassLoader{} }

