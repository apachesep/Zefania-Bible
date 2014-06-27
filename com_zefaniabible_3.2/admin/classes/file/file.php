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

defined('ZEFANIABIBLE_UPLOAD_EXTENSIONS_JOINED') or define("ZEFANIABIBLE_UPLOAD_EXTENSIONS_JOINED", '');
jimport('joomla.filesystem.file');


/**
* JFile Class for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Class
*/
class ZefaniabibleCkClassFile extends JFile
{
	/**
	* Create the folders and protect directory with index.html empty file
	*
	* @access	public static
	* @param	string	$base	The path base.
	* @param	string	$dir	The relative path to create.
	* @return	void
	*
	* @since	Cook 2.6.1
	*/
	public static function blankFiles($base, $dir = null)
	{
		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';
		$path = JPath::clean($base.DS);

		if($path && !file_exists($path))
			return;
	
		// Create a blank index.html file to the given base
		if(!file_exists($path . 'index.html'))
			self::write($path . 'index.html', $blankContent);

		if (!$dir)
			return;

		jimport('joomla.filesystem.folder');

		// Create blank index.html files to every sub folder
		$folders = explode(DS, $dir);
		foreach($folders as $folder)
		{
			$path .= $folder . DS;

			if(!is_dir($path))
				JFolder::create($path);

			if(!file_exists($path . 'index.html'))
				self::write($path . 'index.html', $blankContent);	
	
		}
	}

	/**
	* Transform a litteral bytes formated string to bytes
	*
	* @access	public static
	* @param	string	$val	Foramted value.
	*
	* @return	integer	Bytes.
	*/
	public static function bytes($val)
	{
		$val = trim($val);
		if(empty($val))
		{
			return 0;
		}
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
			$val *= 1024;
			case 'm':
			$val *= 1024;
			case 'k':
			$val *= 1024;
		}
		return (int)$val;
	}

	/**
	* Stringify a bytes value
	*
	* @access	public static
	* @param	integer	$bytes	Bytes.
	*
	* @return	string	Formated bytes string.
	*/
	public static function bytesToString($bytes)
	{
		$suffix = "";
		$units = array('K', 'M', 'G', 'T');

		$i = 0;
		while ($bytes >= 1024)
		{
			$bytes = $bytes / 1024;
			$suffix = $units[$i];
			$i++;
		}

		return round($bytes, 2) . $suffix;
	}

	/**
	* Delete a file and possibly its thumbs
	*
	* @access	public static
	* @param	string	$path	The file pattern path or plain path.
	* @param	string	$remove	Method to use (thumbs, trash, delete).
	*
	* @return	boolean	True on success, False otherwise.
	*
	* @since	Cook 1.1
	*/
	public static function deleteFile($path, $remove = 'delete')
	{
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$op = array('thumbs', 'trash', 'delete');
		$filePath = self::getPhysical($path);

		if (self::exists($filePath))
		{
			if (in_array($remove, array('trash')))
			{
				$trashPath = self::getPhysical("[DIR_TRASH]");
				if (!JFolder::exists($trashPath))
					JFolder::create($trashPath);

				if (!self::move($filePath, $trashPath .DS. self::getName($filePath)))
					return false;
			}
			else if (in_array($remove, array('delete')))
			{
				if (!self::delete($filePath))
					return false;
			}
		}


		$thumbs = in_array($remove, array('thumbs', 'trash', 'delete'));

		//DELETE THUMBS
		if (!$thumbs)
			return true;

		$dir = dirname($filePath);
		if (!JFolder::exists($dir))
			return true;


		$fileName = self::getName($filePath);
		$len = strlen($fileName);
		foreach(JFolder::files($dir,'.',false,false,array('.svn', 'CVS','.DS_Store','__MACOSX'),array()) as $file)
			if (substr($file, 0, $len +1) == "." . $fileName)
				self::delete($dir .DS. $file);



		return true;
	}

	/**
	* Prepare an indirect Url to access the file.
	*
	* @access	public
	* @param	string	$path	FileName (Short name from dir).
	* @param	string	$dirPattern	Base directory (receive a pattern).
	* @param	array	$options	File options (used for images).
	*
	* @return	string	Indirect file access URL.
	*/
	public function downloadUrl($path, $dirPattern, $options = array())
	{
		if (!preg_match("/^\[.+\]/", $path))
			$path = "[" . $dirPattern . "]" .DS. $path;

		$url = JURI::base(true) . "/index.php?option=com_zefaniabible&task=file"
			.	"&path=" . $path
			. 	"&action=download";

		foreach($options as $key => $value)
			$url .= "&" . $key . "=" . $value;

		return $url;
	}

	/**
	* Return the file base name
	*
	* @access	public static
	* @param	string	$file	Filename.
	*
	* @return	string	File base.
	*/
	public static function fileBase($file)
	{
		$base = self::stripExt($file);
		$ext3 = self::getExt($base);

		foreach(explode(",", ZEFANIABIBLE_UPLOAD_EXTENSIONS_JOINED) as $joinedExt)
		{
			$parts = explode(".", trim($joinedExt));
			for($i = count($parts)-2 ; $i >= 0 ; $i--)
			{
				if ($ext3 == $parts[$i])
				{
					$base = self::stripExt($base);
					$ext2 = self::getExt($base);

					if ($ext2 == $parts[$i-1])
						$ext = $ext2 .".". $ext;

					$ext3 = $ext2;
				}
			}
		}

		return self::stripExt($file);
	}

	/**
	* Improve the detection in case of doubled extension (ex : tar.gz).
	*
	* @access	public static
	* @param	string	$file	Filename.
	*
	* @return	string	File extension.
	*/
	public static function fileExtension($file)
	{
		$ext = $ext3 = JFile::getExt($file);
		foreach(explode(",", ZEFANIABIBLE_UPLOAD_EXTENSIONS_JOINED) as $joinedExt)
		{
			$parts = explode(".", trim($joinedExt));
			for($i = count($parts)-1 ; $i > 0 ; $i--)
			{
				if ($ext3 == $parts[$i])
				{
					$file = self::stripExt($file);
					$ext2 = self::getExt($file);

					if ($ext2 == $parts[$i-1])
						$ext = $ext2 .".". $ext;

					$ext3 = $ext2;
				}

			}
		}
		return strtolower($ext);
	}

	/**
	* Parse the directory aliases.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	*
	* @return	string	Litteral unaliased file path or url.
	*
	* @since	Cook 2.6.4
	*/
	public static function getDirectory($path)
	{
		$markers = ZefaniabibleHelper::getDirectories();

		// Parse the folders aliases
		foreach($markers as $marker => $pathStr)
			$path = preg_replace("/\[" . $marker . "\]/", $pathStr, $path);

		// Protect against back folder
		$path = preg_replace("/\.\.+/", "", $path);

		return $path;

	}

	/**
	* Generate the aliased file path from database index and ACLs.
	*
	* @access	public static
	* @param	string	$view	List model name
	* @param	string	$key	Field name where is stored the filename
	* @param	string	$id	Item id
	*
	* @return	string	File path with directory alias.
	*
	* @since	Cook 2.6.1
	*/
	public static function getFromIndex($view, $key, $id)
	{
		$dir = '[DIR_' . strtoupper($view) . '_' . strtoupper($key) . ']';

		$model = CkJModel::getInstance($view, 'ZefaniabibleModel');
		if (!$model)
			return;

		if (empty($key))
			return;

		$model->addWhere('a.id = ' . (int)$id);
		$model->addSelect('a.' . $key);

		// Model is appling accesses restrictions for this file
		$model->setState('file');
		$items = $model->getItems();
	
		if (!count($items))
			return;

		if (!$img = $items[0]->$key)
			return;

		return $dir .DS. $img;
	}

	/**
	* Generate the Url to access the file from database index and ACLs.
	*
	* @access	public static
	* @param	string	$view	List model name
	* @param	string	$key	Field name where is stored the filename
	* @param	string	$id	Item id
	* @param	array	$options	File parameters.
	*
	* @return	string	Indirect file url.
	*
	* @since	Cook 2.6.1
	*/
	public static function getIndexUrl($view, $key, $id, $options = null)
	{
		$url = JURI::base(true) . "/index.php?option=com_zefaniabible&task=file";
		$url .= "&view=" . $view;
		$url .= "&key=" . $key;
		$url .= "&cid=" . $id;

		// Concat some extra parameters for images thumbs
		$url .= self::getUrlThumb($options);

		return $url;
	}

	/**
	* Generate the Url to access the file through controller.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	string	Indirect file url.
	*
	* @since	Cook 2.6.1
	*/
	public static function getIndirectUrl($path, $options = null)
	{
		$url = JURI::base(true) . "/index.php?option=com_zefaniabible&task=file";

		if ($options && isset($options['download']) && $options['download'])
			$url .= "&action=download";

		$url .= self::getUrlThumb($options);

		//File name always at the end
		$url .= "&path=" . $path;

		return $url;
	}

	/**
	* Deprecated. see Helper::getDirectories()
	*
	* @access	public static
	*
	* @return	array	Directories shortcuts.
	*
	* @since	Cook 1.1
	*/
	public static function getMarkers()
	{
		return ZefaniabibleHelper::getDirectories();

	}

	/**
	* Return the mime type of a file
	*
	* @access	public static
	* @param	string	$file	FileName.
	*
	* @return	string	Mime type.
	*/
	public static function getMime($file)
	{
		if (!self::exists($file))
			return null;

		$mime = null;

		//prefered order methods to call the mime decodage
		$mimeMethods = array(
			'mime_content_type',
			'finfo_file',
			'image_check',
			'system',
			'shell_exec',
		);

		foreach($mimeMethods as $method)
		{
			if (!$mime)
			switch($method)
			{

				case 'system':
					if (!function_exists('system'))
						continue;

					$mime = system("file -i -b " . $file);
					break;

				case 'shell_exec':
					if (!function_exists('shell_exec'))
						continue;

					$mime = trim( @shell_exec( 'file -bi ' . escapeshellarg( $file ) ) );
					break;

				case 'mime_content_type':
					if (!function_exists('mime_content_type'))
						continue;
					$mime = mime_content_type($file);
					break;


				case 'finfo_file':
					if (!function_exists('finfo_file'))
						continue;
					$finfo = finfo_open(FILEINFO_MIME);
					$mime = finfo_file($finfo, $file);
					finfo_close($finfo);
					break;


				case 'image_check':
					$file_info = getimagesize($file);
					$mime = $file_info['mime'];
					break;

			}

		}

		//DEFAULT MIME
		if (!$mime)
			$mime = "application/force-download";

		return $mime;
	}

	/**
	* Generate the physical file path.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	string	Physical file location.
	*
	* @since	Cook 2.6.1
	*/
	public static function getPhysical($path, $options = null)
	{
		if ($options)
			$path = self::getThumbName($path, $options);

		return JPATH_ROOT .DS. self::getDirectory($path);
	}

	/**
	* Generate the physical thumb filename.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	string	Indirect file url.
	*
	* @since	Cook 2.6.1
	*/
	public static function getThumbName($path, $options = null)
	{
		$info = pathinfo($path);
		$dir = $info['dirname'];
		$name = str_replace('.'.$info['extension'], '', $info['basename']);
		$ext = $info['extension'];

		$opts = '';

		$attrs = isset($options['attrs'])?$options['attrs']:null;
		$w = isset($options['width'])?(int)$options['width']:0;
		$h = isset($options['height'])?(int)$options['height']:0;

		$size = (($w || $h)?'-'.$w.'x'.$h:'');
	
		if ($attrs)
		{

			if (in_array('crop', $attrs)) $opts .= 'c';
			if (in_array('fit', $attrs)) $opts .= 'f';
			if (in_array('center', $attrs)) $opts .= 'm';


		//TODO : special parameters with value
		//	if ($format) $ext = ($format=='jpeg'?'jpg':$format);
	
			if ($opts)
				$opts = '-' . $opts;
		}

		$thumbName = $dir .DS
			. '.' // Hidden file
			. $name .'.'. $ext . $size . $opts . '.' . $ext;


		return $thumbName;
	}

	/**
	* Generate the direct file url.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	string	Url to access directly the file.
	*
	* @since	Cook 2.6.1
	*/
	public static function getUrl($path, $options = null)
	{
		$path = self::getDirectory($path);

		if ($options)
			$path = self::getThumbName($path, $options);

		if (JFactory::getApplication()->isAdmin())
			$path = '..' .DS . $path;

		return $path;
	}

	/**
	* Generate some extra parameters for images thumbs.
	*
	* @access	public static
	* @param	array	$options	File parameters.
	*
	* @return	string	Url suffix parameters.
	*
	* @since	Cook 2.6.1
	*/
	public static function getUrlThumb($options = null)
	{
		if (!$options)
			return;

		$params = '';

		$w = isset($options['width'])?(int)$options['width']:0;
		$h = isset($options['height'])?(int)$options['height']:0;

		if ($w || $h)
			$params .= "&size=" . $w ."x". $h;

		if (isset($options['attrs']))
			$params .= "&attrs=" . implode(",", $options['attrs']);

		return $params;
	}

	/**
	* Deprecated. see getPhysical()
	*
	* @access	public static
	* @param	string	$path	A pattern path with shortcuts aliases.
	*
	* @return	string	The full rooted path.
	*
	* @since	Cook 1.1
	*/
	public static function parsePath($path)
	{
		return self::getPhysical($path);
	}

	/**
	* Indirect File Access. Output a file on request.
	*
	* @access	public static
	* @param	string	$mode	optional mode (Not used yet).
	*
	* @return	void	Stop the execution of PHP. Output new header and file content.
	* @return	void
	*
	* @since	Cook 1.1
	*/
	public static function returnFile($mode = null)
	{
		$jinput = JFactory::getApplication()->input;
		$path = $jinput->get('path', null, 'STRING');
		$size = $jinput->get('size', null, 'CMD');
		$action = $jinput->get('action', null, 'CMD');
		$attrs = $jinput->get('attrs', null, 'STRING');

		$filePath = null;
		if (!$path)
		{
			// Read through database index
			$view = $jinput->get('view', null, 'CMD');
			$key = $jinput->get('key', null, 'CMD');
			$cid = $jinput->get('cid', null, 'INT');
	
			if ($view && $key && $cid)
				$path = self::getFromIndex($view, $key, $cid);

			//Fallback
			if (!$path)
				$filePath = ZEFANIABIBLE_IMAGES_FALLBACK_ROOT .DS. ZEFANIABIBLE_IMAGES_FALLBACK_NAME;
		}

		$options = null;
		if (!$filePath && $path)
			$filePath = self::getPhysical($path, $options);


		$ext = self::getExt($filePath);



		jimport('joomla.filesystem.file');

		// Files recognized as images, thumbs are availables
		$imagesExt = array('jpg', 'jpeg', 'gif', 'png', 'bmp');

		// System files forbidden to download
		$forbiddenExt = array('php', 'xml', 'ini', 'sql', 'js');


		$mime = null;
		if ($action == 'download')
			$mime = 'application/force-download';  // OU    application/octet-stream
		else if (self::exists($filePath))
			$mime = self::getMime($filePath);


		//Is image ?
		if (($action != 'download') &&
		(in_array($ext, $imagesExt)		//Check on extension
		|| ($mime && preg_match("/^image/", $mime)))			//Check on mime
		)
		{
			require_once(JPATH_ADMIN_ZEFANIABIBLE .DS. 'classes' .DS. 'images.php');
			$thumb = new ZefaniabibleImages($filePath, $mime);

			if ($attrs)
				$thumb->attrs($attrs);


			if ($size && preg_match("/([0-9]+)x([0-9]+)/", $size, $matches))
			{
				$thumb->width($matches[1]);
				$thumb->height($matches[2]);;
			}

			$thumb->get();

			exit();
		}

		// File not founded or not allowed to download.
		else if (!JFile::exists($filePath) || in_array($ext, $forbiddenExt))
		{
			$msg = JText::sprintf( "ZEFANIABIBLE_UPLOAD_FILE_NOT_FOUND", $path);
			jexit($msg);
		}


		//Non image and non outputable mimes : Force download
		if (!in_array($mime, array(
								'application/x-shockwave-flash'
							)))
		{
			header('Content-Description: File Transfer');
		    header("Content-Disposition: attachment; filename=\"".basename($filePath) . "\"");
		}

		//Read and return file contents with original mime header
		header('Content-Type: ' . $mime);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filePath));
		ob_clean();
		flush();

		readfile($filePath);

		jexit();

	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassFile')){ class ZefaniabibleClassFile extends ZefaniabibleCkClassFile{} }

