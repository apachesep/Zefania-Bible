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

defined('ZEFANIABIBLE_UPLOAD_RANDOM_CHARS') or define("ZEFANIABIBLE_UPLOAD_RANDOM_CHARS", 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
defined('ZEFANIABIBLE_UPLOAD_CHMOD_FOLDER') or define("ZEFANIABIBLE_UPLOAD_CHMOD_FOLDER", 0744);
defined('ZEFANIABIBLE_UPLOAD_CHMOD_FILE') or define("ZEFANIABIBLE_UPLOAD_CHMOD_FILE", 0644);


/**
* Uploader Class for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Class
*/
class ZefaniabibleCkClassFileUpload extends ZefaniabibleClassFile
{
	/**
	* Allowed Files types
	*
	* @var array
	*/
	protected $allowedTypes;

	/**
	* File informations
	*
	* @var stdClass
	*/
	protected $file;

	/**
	* Max uploadable file size
	*
	* @var integer
	*/
	protected $maxSize;

	/**
	* Upload Options
	*
	* @var array
	*/
	protected $options;

	/**
	* Upload Folder
	*
	* @var string
	*/
	protected $uploadFolder;

	/**
	* Constructor
	*
	* @access	public
	* @param	string	$uploadFolder	Upload folder.
	* @return	void
	*/
	public function __construct($uploadFolder)
	{
		$this->setUploadFolder($uploadFolder);
		$this->maxSize = $this->getMaxSize();


	}

	/**
	* Return a safe file name
	*
	* @access	protected
	* @param	string	$str	file name to alias.
	* @param	boolean	$toCase	Change case.
	*
	* @return	string	Aliased string.
	*/
	protected function alias($str, $toCase = 'lower')
	{
		//ACCENTS
		$accents = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$replacements = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		$str = str_replace($accents, $replacements, $str);

		//SPACES
		$str = preg_replace("/\s+/", "-", $str);

		switch($toCase)
		{
			case 'lower':
				$str = strtolower($str);
				break;

			case 'upper':
				$str = strtoupper($str);
				break;

			case 'ucfirst':
				$str = ucfirst($str);
				break;

			case 'ucwords':
				$str = ucwords($str);
				break;

			default:
				break;

		}


		$str = JFile::makeSafe($str);

		return $str;
	}

	/**
	* Check a file extension
	*
	* @access	protected
	* @param	string	$fileExt	File extension.
	*
	* @return	boolean	True if allowed, False otherwise.
	*/
	protected function checkExtension($fileExt)
	{
		$valid = false;

		foreach($this->allowedTypes as $mime => $ext)
			if (in_array($fileExt, explode(",", $ext)))
				$valid = true;

		return $valid;
	}

	/**
	* Check if the file is already present.
	*
	* @access	protected
	*
	* @return	boolean	True is already present, False otherwise.
	*/
	protected function checkFilePresence()
	{
		if ($this->fileExists())
		{
			switch($this->options["overwrite"])
			{
				case 'no':		// Error file already present
					return false;
					break;

				case 'yes':
					return true; //Override
					break;

				default:
				case 'suffix':
								// Add a file suffix
					$this->renameIfExists();
					break;
			}
		}

		return true;
	}

	/**
	* Check a mime type
	*
	* @access	protected
	* @param	string	$fileMime	Mime type.
	*
	* @return	boolean	True if allowed, false otherwise.
	*/
	protected function checkMime($fileMime)
	{
		$valid = false;
		if (isset($this->allowedTypes) && count($this->allowedTypes))
		foreach($this->allowedTypes as $mime => $ext)
		{
			$mime = preg_replace("#\/#", "\\\/", $mime);
			if (preg_match("/" . $mime . "/", $fileMime))
				$valid = true;
		}
		return $valid;
	}

	/**
	* Get the extension from the mime type
	*
	* @access	protected
	*
	* @return	string	file extension, null if not found.
	*/
	protected function extensionFromMime()
	{
		foreach($this->allowedTypes as $mime => $ext)
			if ($mime == $this->file->mime)
			{
				$exts = explode(",", $ext);
				return $exts[0];
			}
	}

	/**
	* Check presence of a file
	*
	* @access	public
	* @param	string	$suffix	File suffix.
	*
	* @return	boolean	True if exists, False otherwise.
	*/
	public function fileExists($suffix = null)
	{
		$s = (isset($suffix)?"-" . $suffix:"");

		return file_exists($this->uploadFolder .DS. $this->file->base . $s . '.' . $this->file->extension);
	}

	/**
	* Get allowed files extensions
	*
	* @access	public
	*
	* @return	string	List of allowed extensions.
	*/
	public function getAllowedExtensions()
	{
		return implode(",", $this->allowedTypes);
	}

	/**
	* Get allowed files mimes types
	*
	* @access	public
	*
	* @return	string	List of allowed mimes.
	*/
	public function getAllowedMimes()
	{
		return implode(" - ", array_keys($this->allowedTypes));
	}

	/**
	* Return the authorized max upload size.
	*
	* @access	public static
	* @param	boolean	$string	Add the final unit.
	* @param	integer	$maxSizeCustom	Restrict the max file size upload.
	*
	* @return	mixed	max file size.
	*/
	public static function getMaxSize($string = false, $maxSizeCustom = null)
	{
		$maxSize = intval(ini_get('upload_max_filesize')) * 1024 * 1024;
		$config	= JComponentHelper::getParams( 'com_zefaniabible' );
		$maxSizeConfig = (int)$config->get('upload_maxsize') * 1024 * 1024;

		if ($maxSizeConfig)
			$maxSize = min($maxSize, $maxSizeConfig);

		if ($maxSizeCustom)
			$maxSize = min($maxSize, $maxSizeCustom);

		
		if ($string)
			$maxSize = JText::sprintf("ZEFANIABIBLE_UPLOAD_MAX_B", self::bytesToString($maxSize));

		return $maxSize;
	}

	/**
	* Get the maximum upload size
	*
	* @access	protected
	*
	* @return	integer	Max file upload size in bytes.
	*/
	protected function getMaxUpload()
	{
		$max = $this->maxSize;


		//PHP.INI (upload_max_filesize)
		$iniMaxUpload = self::bytes(ini_get('upload_max_filesize'));
		if ((int)$iniMaxUpload && ($iniMaxUpload < $max))
			$max = $iniMaxUpload;



		//PHP.INI (post_max_size)
		$iniMaxPost = self::bytes(ini_get('post_max_size'));
		if ((int)$iniMaxPost && ($iniMaxPost < $max))
			$max = $iniMaxPost;

		return $max;
	}

	/**
	* Parse the renaming patterns
	*
	* @access	protected
	* @param	string	&$pattern	File name pattern to override.
	* @param	string	$name	Name of the pattern tag.
	* @param	string	$value	Value.
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function parsePattern(&$pattern, $name, $value)
	{
		$name = strtoupper($name);

		if (preg_match("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $pattern))
		{
			//Trim to length
			if (preg_match("/{" . $name . "(\(.+\))?\#?[0-9]+}/", $pattern))
			{
				$length = $this->patternLength($name, $pattern);

				$value = substr($value, 0, $length);
			}

			$pattern = preg_replace("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $value, $pattern);

		}
	}

	/**
	* Limit the length if the length modifier is defined
	*
	* @access	protected
	* @param	string	$name	Tag name.
	* @param	string	$pattern	Pattern.
	*
	* @return	integer	Length.
	*
	* @since	Cook 1.1
	*/
	protected function patternLength($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\#[0-9]+}/", $pattern))
			return;

		$length = preg_replace("/^(.+)?{" . $name . "(\(.+\))?\#?([0-9]+)(}(.+)?)$/", '$'.'3', $pattern);

		return $length;
	}

	/**
	* Get the params of a tag pattern
	*
	* @access	protected
	* @param	string	$name	Name of the tag.
	* @param	string	$pattern	Pattern.
	*
	* @return	string	Tag params.
	*/
	protected function patternParam($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\(.+\)(\#[0-9]+)?}/", $pattern))
			return null;

		$param = preg_replace("/^(.+)?{" . $name . "\((.+)?\)\#?([0-9]+)?(}(.+)?)$/", '$'.'2', $pattern);

		return $param;
	}

	/**
	* Process the upload
	*
	* @access	public
	*
	* @return	boolean	True on success, False otherwise.
	*/
	public function process()
	{
		//Clean the (eventually renamed) path
		$this->file->filename = JPath::clean($this->file->filename);


		//Check if upload autocreate directory exists + Create index.html
		$dir = dirname($this->file->filename);
		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';

		//Create the directories and protect with index.html empty file
		self::blankFiles($this->uploadFolder, $dir);
	
		//Upload file
		$fileDest = $this->uploadFolder . $this->file->filename;
		if (!move_uploaded_file($this->file->tmp, $fileDest))
			if(!JFile::upload($this->file->tmp, $fileDest))
				return false;

		//Protect file against execution
		@chmod($fileDest, ZEFANIABIBLE_UPLOAD_CHMOD_FILE);

		return true;
	}

	/**
	* Return a random alias from composed from a list of chars.
	*
	* @access	protected
	* @param	integer	$length	Length of the random.
	*
	* @return	string	Random string.
	*/
	protected function randomAlias($length)
	{
		$lenChars = strlen(ZEFANIABIBLE_UPLOAD_RANDOM_CHARS);
		$random = "";

		if ((int)$length == 0)
			$length = 8;

		for($i = 0 ; $i < $length ; $i++)
		{
			$pos = rand(0, $lenChars);
			$random .= substr(ZEFANIABIBLE_UPLOAD_RANDOM_CHARS, $pos, 1);
		}

		return $random;
	}

	/**
	* Rewrite the file name before upload
	* PATTERNS :
	* 	{EXT}				: Original extension
	* 	{MIMEXT} 			: Corrected extension from Mime-header
	* 	{BASE}				: Original file name without extension
	* 	{ALIAS}				: Safe aliased original file name
	* 	{RAND}				: Randomized value
	* 	{DATE(Y-m-d)} 		: formated date
	* 	{ID}				: Current item id
	* 
	* MODIFIERS :
	* 	{[PATTERN]#6} 		: Limit to 6 chars
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function renameFile()
	{
		$file = $this->file;
		if ($this->options["rename"])
			$pattern = $this->options["rename"];
		else
			$pattern = "{ALIAS}.{MIMEXT}";

		if (isset($this->options['id']))
		{
			//Original extension
			$this->parsePattern($pattern, "ID", $this->options['id']);
		}

		//Original extension
		$this->parsePattern($pattern, "EXT", $file->extension);

		//Corrected extension from Mime-header
		$this->parsePattern($pattern, "MIMEXT", $this->extensionFromMime());

		//Original file name without extension
		$this->parsePattern($pattern, "BASE", $file->base);


		//Safe aliased original file name
		$this->parsePattern($pattern, "ALIAS", $this->alias($file->base, 'lower'));


		//Randomized value
		$length = $this->patternLength("RAND", $pattern);
		$this->parsePattern($pattern, "RAND", $this->randomAlias($length));

		//formated date
		$format = $this->patternParam("DATE", $pattern);
		if (!$format)
			$format = "Y-m-d";
		$this->parsePattern($pattern, "DATE", JFactory::getDate()->format($format));


		//remove spaces
		$pattern = preg_replace("/\s+/", "", $pattern);

		//remove backdir
		$pattern = preg_replace("/\.\./", "", $pattern);

		//Non empty string
		if (trim($pattern) == "")
			$pattern = $this->randomAlias(8);

		$file->filename = $pattern;
		$file->base = $this->fileBase($file->filename);
		$file->extension = $this->fileExtension($file->filename);


		$this->file = $file;
	}

	/**
	* Rename the file if it already exists
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function renameIfExists()
	{
		$file = $this->file;

		if ($this->fileExists())
		{
			$suffix = 1;
			while($this->fileExists($suffix))
				$suffix++;

			$file->base = $file->base . "-" . $suffix;
			$file->filename = $file->base . "." . $file->extension;

		}
	}

	/**
	* Set the allowed files types
	*
	* @access	public
	* @param	array	$allowedTypes	Allowed types.
	* @return	void
	*/
	public function setAllowed($allowedTypes)
	{
		$this->allowedTypes = $allowedTypes;
	}

	/**
	* Set the upload folder
	*
	* @access	public
	* @param	string	$uploadFolder	Upload folder.
	* @return	void
	*/
	public function setUploadFolder($uploadFolder)
	{
		$uploadFolder = $this->getPhysical($uploadFolder);
		$app = JFactory::getApplication();

		jimport('joomla.filesystem.folder');

		//Clean upload path
		$uploadFolder = JPath::clean(html_entity_decode($uploadFolder . DS));
		$uploadPath = JPath::clean($uploadFolder);




		//Check if upload directory exists
		if(!is_dir($uploadPath))
			JFolder::create($uploadPath);

		if (!is_dir($uploadPath))
			return false;

		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';
		if (!self::exists($uploadPath.'index.html'))
			self::write($uploadPath.'index.html', $blankContent);


		//Protect against execution and set writable
		@chmod($uploadPath, ZEFANIABIBLE_UPLOAD_CHMOD_FOLDER);
		if(!is_writable($uploadPath))
		{
			$app->enqueueMessage(JText::sprintf( "ZEFANIABIBLE_UPLOAD_PLEASE_MAKE_SURE_THE_FOLDER_IS_WRITABLE",$uploadPath), 'notice');
			return false;
		}

		$this->uploadFolder = $uploadFolder;
	}

	/**
	* Upload a file. Main process.
	*
	* @access	public
	* @param	array	$uploadFile	Array of informations of the file (From $_FILES).
	* @param	array	$options	Upload options.
	*
	* @return	mixed	file informations on success, False otherwise.
	*
	* @since	Cook 1.1
	*/
	public function uploadFile($uploadFile, $options = array())
	{
		$this->options = $options;

		if (isset($this->options["maxSize"]))  //Overwrite maxSize
			$this->maxSize = $this->options["maxSize"];

		$uploadFolder = $this->uploadFolder;
		$app = JFactory::getApplication();

		//Check file name
		if(empty($uploadFile['name'])){
			$app->enqueueMessage(JText::_("ZEFANIABIBLE_UPLOAD_PLEASE_BROWSE_A_FILE"),'notice');
			return false;
		}

		$file = null;
		$file->filename = $uploadFile['name'];
		$file->tmp = $uploadFile['tmp_name'];
		$file->size = $uploadFile['size'];

		$file->extension = $this->fileExtension($file->filename);
		$file->base = $this->fileBase($file->filename);

		$this->file = $file;

		//CHECK EXTENSION
		if (!$this->checkExtension($file->extension))
		{
			$app->enqueueMessage(JText::sprintf( "ZEFANIABIBLE_UPLOAD_THIS_FILE_EXTENSION_IS_NOT_ACCEPTED_THE_ACCEPTED_FILES_ARE",
												$file->extension,
												$this->getAllowedExtensions()
												), 'notice');
			return false;
		}


		//CHECK MIME HEADER
		$this->file->mime = $this->getMime($this->file->tmp);
		if (!$this->checkMime($this->file->mime))
		{
			$app->enqueueMessage(JText::sprintf( "ZEFANIABIBLE_UPLOAD_MIME_TYPE_NOT_VALID_ALLOWED_MIMES_ARE",
												$this->file->mime,
												$this->getAllowedMimes()), 'error');
			return false;
		}

		//CHECK SIZE
		$maxSize = self::getMaxUpload();
		if ($this->file->size > $maxSize)
		{
			$app->enqueueMessage(JText::sprintf( "ZEFANIABIBLE_UPLOAD_TOO_BIG_FILE_BYTES_MAX_ALLOWED_SIZE_BYTES",
											self::bytesToString($this->file->size),
											self::bytesToString($maxSize)), 'error');
			return false;
		}



		//CHECK PHP INJECTION
		$contents = JFile::read($file->tmp);
		if (preg_match("/\<\?php\s/", $contents))
		{
			$app->enqueueMessage(JText::_( "ZEFANIABIBLE_UPLOAD_THE_FILE_CONTAINS_ERRORS"), 'error');
			return false;
		}

		//CORRECT FILENAME
		$this->renameFile();

		//CHECK FILE PRESENCE
		if (!$this->checkFilePresence())  //And rename if allowed
		{
			$app->enqueueMessage(JText::sprintf( "ZEFANIABIBLE_UPLOAD_THIS_FILE_ALREADY_EXIST",$file->filename), 'notice');
			return false;
		}

		//PROCESS UPLOAD
		if (!$this->process())
		{
			if ($app->isSite())
				$msg = JText::sprintf( "ZEFANIABIBLE_UPLOAD_COULD_NOT_UPLOAD_THE_FILE", $file->filename);	// Don't show the complete directory in front-end
			else if ($app->isAdmin())
				$msg = JText::sprintf( "ZEFANIABIBLE_UPLOAD_COULD_NOT_UPLOAD_THE_FILE_TO",$file->tmp,$this->uploadFolder . $file->filename);
	
			$app->enqueueMessage($msg, 'error');
			return false;
		}

		return $file;
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassFileUpload')){ class ZefaniabibleClassFileUpload extends ZefaniabibleCkClassFileUpload{} }

