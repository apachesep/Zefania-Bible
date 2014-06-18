<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('BR')) define("BR", "<br />");
if(!defined('LN')) define("LN", "\n");


/*
 * JDom Framework is an abstraction between your component and the HTML renderer (of your choice)
 *
 * 	Rewrite inside the element classes files you want to change, or override them (see below)
 * 	Using JDom in your component, you'll be able to upgrade all your component DOM possibilities in seconds...
 *
 *  See documentation at www.j-cook.pro
 *
 *
 *	OVERRIDES :
 * 	You can place the files you want to override wherever you prefers see the $searches array;
 *
 *	in the app site client	ie : components/com_mycomponent/dom/html/form/input/select.php
 * 	in the template			ie : templates/my_template/html/com_mycomponent/dom/html/form/input/select.php
 *  in the template view	ie : templates/my_template/html/com_mycomponent/my_view/dom/html/form/input/select.php
 *	and more ...
 *
 *	The search array defines the order of priority for overriding
 *
 *  JDom is 100% compatible for all Joomla! versions since 1.5
 *
 */
class JDom extends JObject
{
	var $path;
	var $options;
	var $app;

	var $_pathSite = JPATH_SITE;
	var $_uriJdom;
	
	protected $args;
	protected $fallback;
	
	protected static $loaded = array();
	protected $extension;
	
	
	/*
	* Define the priority order to search the classes
	* TODO : Comment some lines, or change order depending on how you want to use this functionnality.
	* see : searchFile()
	*/
	protected $searches = array(
			'fork',			// 	Files on the fork directory as priority
			'template',		// 	Files on the root directory of the template
			'client',		//	Files on the component root directory -> Search in the current client side (front or back)
			'back',			//	Files on the BACK component root directory (Administrator client)
	);
	
	//Some framworks are invasive, you can activate them manually see registerFrameworks()
	public $frameworks = array();  //can contain : bootstrap, icomoon, chosen
	
	
	/*
	 * Constuctor
	 *
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 */
	public function __construct($args = null)
	{
		$this->arg('namespace'	, 0, $args);
		$this->arg('options'	, 1, $args);

		$this->args = $args;
		
		CkJLoader::registerPrefix('JDom', dirname(__FILE__));
		CkJLoader::discover('JDom', dirname(__FILE__));

		$this->app = JFactory::getApplication();
		
		$this->registerFrameworks();
	}

	
	protected function registerFrameworks()
	{
		$version = new JVersion;

		//Compatible with all frameworks
		if ($version->isCompatible('3.0') || ($this->app->isAdmin()))
		{
			$this->registerFramework('bootstrap');
			//$this->registerFramework('icomoon');	//Conflicts with bootstrap
			$this->registerFramework('chosen');
		}
		
		
		if ($this->app->isSite())
		{
			$this->registerFramework('bootstrap');
			$this->registerFramework('icomoon');		
			$this->registerFramework('chosen');
		}
		
	}


	public function set($property, $value = null)
	{
		$previous = isset($this->$property) ? $this->$property : null;
		
		$this->$property = $value;
		$this->options[$property] = $value;
		return $previous;
	}
	
	
	
	protected function loadClassFile($namespace = null)
	{
		if (!$namespace)
			$namespace = $this->namespace;
		
		$parts = explode('.', $namespace);
		$currentParts = array();
		$className = 'JDom';
		foreach($parts as $part)
		{
			$currentParts[] = $part;
			$className .= ucfirst($part);

			//Load all the parent classes
			if (!$this->includeFile(implode(DS, $currentParts) . '.php', $className))
				return false; //Not found

		}
		return $className;
	}

	protected function includeFile($relativeName, $className)
	{
		$file = $this->searchFile($relativeName);

		//Not founded
		if (!$file)
			return false;

		CkJLoader::register($className, $file);

		return true;
	}

	protected function searchFile($relativeName)
	{
		$extension = $this->getExtension();

		foreach($this->searches as $search)
		{
			switch($search)
			{
				case 'fork';
					$path = JPATH_ADMINISTRATOR .DS. 'components' .DS. $extension .DS. 'fork';
					break;

				case 'template';
					$tmpl = $this->app->getTemplate();
					$path = JPATH_SITE .DS. 'templates' .DS. $tmpl .DS. 'html';
					break;

				case 'client';
					$path = JPATH_COMPONENT;
					break;

				case 'back';
					$path = JPATH_ADMINISTRATOR .DS. 'components' .DS. $extension;
					break;

				default:
					$path = $search;		//Custom path
					break;
			}

			$path = $path .DS. 'dom' .DS. $relativeName;

			if (file_exists($path))
				return $path;
		}

		//Last Fallback : call a children file from the JDom called Class (First instanced)
		if (!file_exists($path))
		{
			$classFile = __FILE__;
			if (preg_match("/.+dom\.php$/", $classFile))
			{
				$classRoot = substr($classFile, 0, strlen($classFile) - 8);
				$path = $classRoot .DS. $relativeName;

				if (file_exists($path))
					return $path;
			}
		}
		return null;
	}


	public static function getInstance($namespace = null, $options = null)
	{
		$app = JFactory::getApplication();
		if (!isset($app->dom))
			$app->dom = new JDom();

		$dom = $app->dom;
		
		if ($namespace)
		{
			$className = $dom->loadClassFile($namespace);
			if (!class_exists($className))
				return null;
		
			$class = new $className(array($namespace, $options));
			return $class;
		}
		
		return $dom;
	}
	
	protected function getClassInstance()
	{
		$className = $this->loadClassFile();
		
		if (!class_exists($className))
			return null;
		
		$class = new $className($this->args);
		
		return $class;
	}
	
	public static function _()
	{
		$dom = self::getInstance();
		$args = func_get_args();
		
		$dom->set('args', $args);
		
		return $dom->render($args);
	}
	
	public function __()
	{
		$args = func_get_args();
		$this->set('args', $args);
		
		return $this->render($args);
	}
	
	protected function error($msg, $icon = null)
	{
		$html = '<strong>JDom Error</strong> : ' . $msg;
		return $html;
	}
	
	public function render($args = array())
	{
		//Get the namespace
		if (empty($args[0]))
			return $this->error('Namespace is undefined');
		
		$this->namespace = $namespace = $args[0];
		
		$class = $this->getClassInstance();
		if (!$class)
			return $this->error('Not found : <strong>' . $namespace . '</strong>');
		
		$class->loadOptions();
		
		//load the extension name
		$this->getExtension();
				
		return $class->output();
	}
	

	
	//Fallback function
	public function build()
	{
		if (empty($this->fallback))
			return $this->error('build() function not found.');
					
		$this->namespace .= '.' . $this->fallback;
		
		$class = $this->getClassInstance();
		if (!$class)
			return $this->error('Not found : <strong>' . $this->namespace . '</strong>');
		
		return $class->output();
	}
	
	public function output()
	{
		//ACL Access
		if (!$this->access())
			return '';	//Not authorizated
		
		//HTML
		$html = $this->build();
		
		//EMBED LINK
		if (method_exists($this, 'embedLink'))
			$html = $this->embedLink($html);
		
		//Assets implementations
		$this->implementAssets();
		
		if ($this->isAjax())
			$this->ajaxHeader($html);	//Embed javascript and CSS in case of Ajax call
		
		//Parser
		$html = $this->parse($html);   //Was Recursive ?
		
		return $html;
	}
	
	public function registerFramework($framework)
	{
		$this->frameworks[$framework] = true;		
	}
	
	protected function useFramework($framework)
	{
		$dom = JDom::getInstance();
		if (in_array($framework, array_keys($dom->frameworks)))
			return true;
	}

	
	
	protected function loadOptions()
	{
		if (!$this->args)
			return;
		
		$options = array();
		if (!empty($this->args[1]))
			$options = $this->args[1];
		
		$this->options = $options;
	}
	
	protected function arg($name, $i = null, $args = array(), $fallback = null)
	{
		$optionValue = $this->getOption($name);

		if ($optionValue !== null)
			$this->$name = $this->options[$name];
		else if (($i !== null) && (count($args) > $i))
			if ($args[$i] !== null)
				$this->$name = $args[$i];
			else
				$this->$name = $fallback;

		if (!isset($this->$name) && ($fallback !== null))
			$this->$name = $fallback;


		if ($optionValue)
			$this->options[$name] = $this->$name;

	}

	protected function isArg($varname)
	{
		if (isset($this->$varname) || (is_array($this->options) && (in_array($varname, array_keys($this->options)))))
			return true;
		else
			return false;
	}

	public function getOption($name)
	{
		if ($name == 'options')
			return;
		if (!is_array($this->options))
			return;
		
		if (!(in_array($name, array_keys($this->options))))
			return;
		
		if (!isset($this->options[$name]))
			return;
		
		return $this->options[$name];
	}

	public function getExtension()
	{
		$dom = JDom::getInstance();
		if ($extension = $dom->get('extension'))
			return $extension;
		
		$extension = $this->getOption('extension');
		if (!$extension)
		{
			$jinput = new JInput;
			$extension = $jinput->get('option', null, 'CMD');
		}
		
		$dom->set('extension', $extension);

		return $extension;
	}
	
	function getComponentHelper()
	{
		$helperClass = ucfirst(substr($this->getExtension(), 4)) . 'Helper';
		
		if (!class_exists($helperClass))
		{
			echo('Class <strong>' . $helperClass . '<strong> not found');
			return;
		}
		return $helperClass;
	}
	
	public function isAjax()
	{
		$jinput = new JInput;
		$layout = $jinput->get('layout', null, 'CMD');
		if ($layout == 'ajax')
			return true;
		
		return false;
	}
	
	
//DEPRECATED
	public function getView()
	{
		$view = $this->getOption('view');

		if (!$view)
		{
			$jinput = new JInput;
			$view = $jinput->get('view', null, 'CMD');
		}

		return $view;
	}

	protected function implementAssets()
	{
		//Javascript
		$this->buildJs();
		$this->attachJsFiles();

		//CSS
		$this->buildCss();
		$this->attachCssFiles();
	}

	public function buildJs()	{}
	
	protected function attachJsFiles()
	{
		//Javascript
		if (!isset($this->attachJs))
			return;

		$attachJs = $this->attachJs;

		if (!is_array($attachJs))
			$attachJs = array($attachJs);

		$fileBase = ""; // dom Root
		if (isset($this->assetName) && ($this->assetName != null))
			$fileBase = 'assets' .DS. $this->assetName .DS. 'js' .DS;

		foreach($attachJs as $jsFileName)
		{
			if (preg_match("/^http/", $jsFileName))
				JFactory::getDocument()->addScript($jsFileName);
			else
				$this->addScript($fileBase . $jsFileName);
		}
	}

	protected function addScript($assetPath = null)
	{
		if ((!$assetPath) && (!isset($this->assetName)))
			return;

		if ($assetPath)
			$relativeName = $assetPath;
		else
			return;

		$jsFile = $this->searchFile($relativeName, false);
		if ($jsFile)
		{
			$jsFile = self::pathToUrl($jsFile);
			if (isset(self::$loaded[__METHOD__][$relativeName]))
				return;
			
			if ($this->isAjax())
			{
				$jsScript = LN . '<script type="text/javascript">'
				.	LN . 'jQuery.getScripts(' . json_encode(array($jsFile)). ', function(){});'
				.	LN . '</script>';
				
				echo $jsScript;
			}
			else
			{
				$doc = JFactory::getDocument();
				$doc->addScript($jsFile);				
			}
			
			
			self::$loaded[__METHOD__][$relativeName] = true;
		}

	}

	protected function addScriptInline($script, $embedReady = false, $embedFramework = 'jQuery')
	{
		if (isset(self::$loaded[__METHOD__][$script]))
			return;

		if ($embedFramework)
			$script = $this->jsEmbedFramework($script, $embedFramework);

		//Do not embed ajax. Handled by the Ajax class callback
		if ($embedReady && !$this->isAjax())
			$script = $this->jsEmbedReady($script);

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($script);

		self::$loaded[__METHOD__][$script] = true;

	}
	
	protected function jsEmbedReady($script)
	{
		//Do not embed. Handled by the Ajax class callback
		if ($this->isAjax())
			return $script;
		
		$js = "jQuery('document').ready(function(){" . LN;
		$js .= $this->indent($script, 1);
		$js .= LN. "});";

		return $js;
	}
	
	protected function jsEmbedFramework($script, $embedFramework = 'jQuery')
	{

		$js = '(function($){' . LN;
		$js .= $this->indent($script, 1);
		$js .= LN. "})($embedFramework);";

		return $js;
	}

	public function buildCss()	{}

	protected function attachCssFiles()
	{
		if (!isset($this->attachCss))
			return;

		$attachCss = $this->attachCss;

		if (!is_array($attachCss))
			$attachCss = array($attachCss);

		$fileBase = ""; // dom Root
		if (isset($this->assetName) && ($this->assetName != null))
			$fileBase = 'assets' .DS. $this->assetName .DS. 'css' .DS;


		foreach($attachCss as $cssFileName)
		{
			$relativeName = $fileBase . $cssFileName;
			$this->addStyleSheet($relativeName);
		}
	}



	protected function addStyleSheet($assetPath = null)
	{
		if ((!$assetPath) && (!isset($this->assetName)))
			return;

		if ($assetPath)
			$relativeName = $assetPath;
		else
		{
			$name = $this->assetName;
			$relativeName = 'assets' .DS. $name . DS. 'css' .DS . $name . '.css';
		}

		$cssFile = $this->searchFile($relativeName, false);
		if ($cssFile)
		{
			$cssFile = self::pathToUrl($cssFile);
			if (isset(self::$loaded[__METHOD__][$relativeName]))
				return;

			JFactory::getDocument()->addStyleSheet($cssFile);

			self::$loaded[__METHOD__][$relativeName] = true;
		}
	}

	protected function addStyleDeclaration($css)
	{
		if (isset(self::$loaded[__METHOD__][$relativeName]))
				return;
	
		JFactory::getDocument()->addStyleDeclaration($css);
		self::$loaded[__METHOD__][$css] = true;
		
	}


	protected function ajaxHeader(&$html)
	{
		if (!$this->isAjax())
			return;
		
		$js = $this->ajaxCallbackOnLoad();
		$css = $this->ajaxAttachCss();
		$html = $css . $js . $html;
	}


	/**
	 * Embed the scripts inside a temporary function called after the domReady event
	 */
	protected function ajaxCallbackOnLoad()
	{
		if (!$this->isAjax())
			return;
	
		//Ajax token is the unique fallback function name
		$jinput = new JInput;
		$token = $jinput->get('token', null, 'CMD');
		if (!$token)
			return;
			
	// Get script declarations
		$scripts = array();		
		if (!empty(self::$loaded['JDom::addScriptInline']))
			foreach(self::$loaded['JDom::addScriptInline'] as $content => $foo)
				$scripts[] = $content;

		if (!count($scripts))
			return '';
		
		$jsScriptCallback =  'registerCallback("' . $token . '", function(){' 
			. implode(";\n", $scripts) 
			. '});';

		$jsScript = '<script type="text/javascript">'
			.	$jsScriptCallback
			. 	'</script>';
		
		return $jsScript;
	}


	protected function ajaxAttachCss()
	{
		$html = '';
		// Generate stylesheet links
		if (!empty(self::$loaded['JDom::addStyleSheet']))
			foreach (self::$loaded['JDom::addStyleSheet'] as $url => $foo)
			{
				$cssFile = $this->searchFile($url, false);
				if ($cssFile)
					$cssFile = self::pathToUrl($cssFile);
	
				$html .= '<link rel="stylesheet" href="' . $cssFile . '" type="text/css"/>';
			}

		return $html;
	}

	protected function assetsDir()
	{
		if (!$this->assetName)
			return;
		
		return dirname(__FILE__) .DS. 'assets' .DS. $this->assetName;
	}

	protected function assetFilePath($type, $name)
	{
		if (!$this->assetName)
			return;
		
		if (!in_array($type, array('js', 'css', 'images', 'fonts')))
			return;
		
		return $this->assetsDir() .DS. $type .DS. $name;
	}


	protected function jsonArgs($args = array())
	{
		return json_encode($args);
	}

	protected function indent($contents, $indent)
	{
		if (is_int($indent))
		{
			$indentStr = "";
			for($i = 0 ; $i < $indent ; $i++)
				$indentStr .= "	";
		}
		else
			$indentStr = $indent;

		$lines = explode("\n", $contents);
		$indentedLines = array();

		foreach($lines as $line)
		{
			if (trim($line) != "") //Don't indent line if empty
				$line = $indentStr . $line;

			$indentedLines[] =  $line;
		}

		return implode("\n", $indentedLines);
	}

	protected function parseVars($vars)
	{
		return array_merge(array(
		
		), $vars);
		
	}

	protected function parse($pattern)
	{
		$vars = $this->parseVars(array());

		$html = $pattern;

		if (isset($vars) && count($vars))
		foreach($vars as $key => $value)
		{
			//Escape $ char
			$value = str_replace("$", "\\$", $value);
			//Replace values
			$html = preg_replace("/<%" . strtoupper($key) . "%>/", $value, $html);
		}

		return $html;
	}

	/*
	 * object	@object	: Object value source
	 * string 	@pattern : pattern composed by object keys:
	 * 					ie : "<%name%> <%surname%> <%_user_email%>" (DEPRECATED)
	 * 					ie : "{name} {surname} {_user_email}" (NEW FORMAT)
	 * 					note : theses values must be available in $object
	 */
	protected function parseKeys($object, $pattern)
	{
		if (is_array($pattern))
		{
			$namespace = $pattern[0];
			array_shift($pattern);
			$options['labelKey'] = null; // No recursivity

			$options = array_merge($this->options, $pattern);
			$labelKey = $options['labelKey'];

			$options['list'] = null;
			$options['dataValue'] = $this->parseKeys($object, $labelKey);

			return JDom::_($namespace, $options);
		}
		
		//Tags <% % > are deprecated use { } instead
		$tag1 = '[<,{]%?';
		$tag2 = '%?[>,}]';

		
		$matches = array();
		if (preg_match_all("/" . $tag1 . "([a-zA-Z0-9_]+:)?([a-zA-Z0-9_]+)" . $tag2 . "/", $pattern, $matches))
		{

			$label = $pattern;

			$index = 0;
			foreach($matches[0] as $match)
			{
				$key = $matches[2][$index];

				if ($type = $matches[1][$index])
				{
					//JDOM FLY DEFINE
					$type = substr($type, 0, strlen($type) - 1);

					$namespace = "html.fly." . $type;
					$options['dataValue'] = $this->parseKeys($object, $key);

					$value = JDom::_($namespace, $options);
				}
				else
				{
					$value = (isset($object->$key)?$object->$key:"");
				}
				$label = preg_replace("/" . $tag1 . "([a-zA-Z0-9_]+:)?" . $key . "" . $tag2 . "/", $value, $label);
				$index++;

			}

		}
		else
		{
			$key = $pattern;  //No patterns
			$label = (isset($object->$key)?$object->$key:"");
		}

		return $label;
	}


	/*
	 * Parse a string with JText
	 * Accepts a composed string ie : "[MY_FIRST_STRING], [MY_SECOND_STRING] : "
	 */
	protected function JText($text)
	{
		//Fix a little Joomla bug
		if ((strtolower($text) == 'true') || (strtolower($text) == 'false'))
			return $text;

		if (preg_match("/\[([A-Z0-9_]+)\]/", $text))
		{
			preg_match_all("/\[([A-Z0-9_]+)\]/", $text, $results);
			foreach($results[1] as $string)
			{
				$translated = JText::_($string);
				$text = preg_replace("/\[(" . $string . ")\]/", JText::_($string), $text);
			}
		}
		else
			$text = JText::_($text);

		return $text;

	}

	public function setPathSite($path)
	{
		JDom::getInstance()->_pathSite = $path;
	}

	public function getPathSite()
	{
		return JDom::getInstance()->_pathSite;
	}

	public function setUriJDomBase($uri)
	{
		JDom::getInstance()->_uriJdom = $uri;
	}
	
	public function getUriJDomBase()
	{
		return JDom::getInstance()->_uriJdom;
	}

	protected function pathToUrl($path, $raw = false)
	{
		$base = JDom::getInstance()->getPathSite();
		$uri = JDom::getInstance()->getUriJDomBase();
		
		$path = str_replace("\\", "/", $path);
		$base = str_replace("\\", "/", $base);

		$escaped = preg_replace("/\//", "\/", $base);
		$relUrl = $uri . preg_replace("/^" . $escaped . "/", "", $path);

		if ($raw)
			return $relUrl;

		return JURI::root(true) . $relUrl;
	}

	protected function strftime2regex($format)
	{
		$d2 = "(\d{2})";
		$d4 = "([1-9]\d{3})";

		$patterns =
array(	"\\", 	"/", 	"#",	"!", 	"^", "$", "(", ")", "[", "]", "{", "}", "|", "?", "+", "*", ".",
		"%Y", 	"%y",	"%m",	"%d", 	"%H", 	"%M", 	"%S", 	" ");
		$replacements =
array(	"\\", "\/", 	"\#",	"\!", 	"\^", "$", "\(", "\)", "\[", "\]", "\{", "\}", "\|", "\?", "\+", "\*", "\.",
		$d4,	$d2,	$d2,	$d2,	$d2,	$d2,	$d2,	"\s");

		$regex = str_replace($patterns, $replacements, $format);

		return "/^" . $regex . "$/";
	}


	protected function jVersion($ver, $comp = '>=')
	{		
		jimport('joomla.version');
		$version = new JVersion();

		return version_compare($version->RELEASE, $ver, $comp);
	}

	protected function adminTemplate()
	{
		if ($this->jVersion('3.0'))
			return 'isis';
		else
			return 'bluestork';	
	}

	protected function systemImagesDir()
	{
		$dir = 'templates' .DS. $this->adminTemplate() .DS. 'images' .DS. 'admin';
		
		if ($this->app->isSite())
			$dir = "administrator" .DS . $dir;
		
		return $dir;
	}


	protected function extensionDir()
	{
		return JPATH_ADMINISTRATOR .DS. 'components' .DS. $this->getExtension();
	}

	protected function domUrl()
	{
		$url = self::pathToUrl($this->extensionDir() . '/dom');
		return $url;
	}

	protected function assetImage($imageName, $assetName = null)
	{
		if (!$assetName)
			return;

		$urlImage = self::domUrl().'/assets/'. $assetName . '/images/' . $imageName;

		return $urlImage;
	}

	protected function htmlAssetSpriteImage($urlImage, $d)
	{
		$image = "<div style='background-image: url(" . $urlImage . ");"
			.	"width:" . $d->w . "px;"
			.	"height:" . $d->h . "px;"
			.	"background-position:-" . $d->x . "px -" . $d->y . "px;'>"
			.	"</div>";

		return $image;
	}

	protected function accessTask($task)
	{
		$aclAccess = $this->getOption('aclAccess');

		if ($aclAccess)
			return $this->access();

		switch ($task)
		{
			case 'new':
				$access = 'core.create';
				break;

			case 'edit':
			case 'save':
			case 'apply':
				$access = 'core.edit';
				break;

			case 'publish':
			case 'unpublish':
			case 'trash':
			case 'default_it':
				$access = 'core.edit.state';
				break;

			case 'delete':
			case 'empty_trash':
				$access = 'core.delete';
				break;

			case 'config':
				$access = 'core.manage';
				break;

			default:
				return true;
				break;
		}

		return $this->access($access);
	}


	protected function getRoute($route, $target = null)
	{
		if (($target == 'modal') && empty($route['tmpl']))
			$route['tmpl'] = 'component';
			
		$jinput = $this->app->input;
						
		$vars = array_merge(array_keys($route), array('option', 'view', 'layout', 'task', 'cid[]', 'tmpl'));
		$followers = array('lang', 'Itemid', 'tmpl');

		$queryVars = array();

		foreach($vars as $var)
		{
			if (isset($route[$var]))
			{
				if (!empty($route[$var]))
					$queryVars[$var] = $route[$var];
			}
			else
			{
				$value = $jinput->get($var, null, 'STRING');
				if ($value !== null)
					$queryVars[$var] = $value;
			}
		}

		foreach($followers as $follower)
		{
			$value = $jinput->get($follower, null, 'CMD');
			if ($value !== null)
				$queryVars[$follower] = $value;
		}

		$parts = array();

		if (count($queryVars))
		foreach($queryVars as $key => $value)
			$parts[] = $key . '=' . $value;

		$url = JRoute::_("index.php?" . implode("&", $parts), false);

		return $url;			
		
	}

	protected function access($aclAccess = null)
	{
		if (!$aclAccess)
			$aclAccess = $this->getOption('aclAccess');

		if (!$aclAccess)
			return true;

		if (!is_array($aclAccess))
			$aclAccess = array($aclAccess);

		$aclAsset = $this->getOption('aclAsset');
		if (!$aclAsset)
			$aclAsset = $this->getExtension();

		$user 	= JFactory::getUser();

		$authorize = false;
		foreach($aclAccess as $acl)
		{
			$auth = $user->authorise($acl, $aclAsset);

			if ($auth)
				$authorize = true;
		}

		return $authorize;
	}


	function regexFromDateFormat($dateFormat)
	{
		$d2 = '[0-9]{2}';
		$d4 = '[1-9][0-9]{3}';

		$patterns = array(
			'\\','/','#','!','^','$','(',')','[',']','{','}','|','?','+','*','.',
			'%?Y','%?y','%?m','%?d', '%?H', '%?I', 'i', '%?l', '%?M', '%?S', ' '
		);
		
		$replacements = array(
			'\\\\', '\\/','\\#','\\!','\\^','\\$','\\(','\\)','\\[','\\]','\\{','\\}','\\|','\\?','\\+','\\*','\\.',
			$d4,$d2,$d2,$d2,$d2,$d2,$d2,$d2,$d2,$d2,'\s'	
		);

		return "^" . str_replace($patterns, $replacements, $dateFormat) . "$";
	}
	
	function legacyDateFormat($dateFormat)
	{
		$patterns = array(	
		'Y','y','m','d', 'H', 'i', 'l', 's');

		$replacements =	array(	
		'%Y','%y','%m','%d', '%H', '%M', '%l', '%S');

		return str_replace($patterns, $replacements, $dateFormat);

	}
	
	
}