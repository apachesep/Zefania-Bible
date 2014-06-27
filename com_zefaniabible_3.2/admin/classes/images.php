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


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

if (!defined('ZEFANIABIBLE_IMAGES_MAX_WIDTH')) define("ZEFANIABIBLE_IMAGES_MAX_WIDTH", 1000);
if (!defined('ZEFANIABIBLE_IMAGES_MAX_HEIGHT')) define("ZEFANIABIBLE_IMAGES_MAX_HEIGHT", 1000);
if (!defined('ZEFANIABIBLE_IMAGES_ALOWED_SIZES')) define("ZEFANIABIBLE_IMAGES_ALOWED_SIZES", '');
if (!defined('ZEFANIABIBLE_IMAGES_PHYSICAL_THUMB')) define("ZEFANIABIBLE_IMAGES_PHYSICAL_THUMB", true);		//Create an hidden thumb file with parameters
if (!defined('ZEFANIABIBLE_IMAGES_FALLBACK_NAME')) define("ZEFANIABIBLE_IMAGES_FALLBACK_NAME", ".notfound.png");
if (!defined('ZEFANIABIBLE_IMAGES_FALLBACK_ROOT')) define("ZEFANIABIBLE_IMAGES_FALLBACK_ROOT", JPATH_ADMIN_ZEFANIABIBLE .DS. "images");

if (file_exists(JPATH_ADMIN_ZEFANIABIBLE .DS. "classes" .DS. "file" .DS. "file.php"))
	include_once(JPATH_ADMIN_ZEFANIABIBLE .DS. "classes" .DS. "file" .DS. "file.php");


class ZefaniabibleImages  extends ZefaniabibleClassFile
{


    protected $file;
	protected $dir;
    protected $mime;
    protected $image_width;
    protected $image_height;
    protected $width;
    protected $height;
    protected $ext;
    protected $types = array('', 'gif', 'jpeg', 'png', 'bmp');
    protected $top = 0;
    protected $left = 0;
    protected $type;
	protected $name;
	protected $info;

	//Attributes
	protected $attributes;
	protected $quality = 80;
    protected $crop;
	protected $fit;
	protected $center;
	protected $format;
	protected $color;
	protected $opacity = 100;
	protected $optsFileName =array();



	function __construct($name='', $mime=null)
	{
		if (!self::exists($name))
			return;

		$this->file = $name;

		$this->mime = $mime;

		$info = getimagesize($name);
		$this->image_width = $info[0];
		$this->image_height = $info[1];
		$this->type = $this->types[$info[2]];
		$info = pathinfo($name);
		$this->dir = $info['dirname'];
		$this->name = str_replace('.'.$info['extension'], '', $info['basename']);
		$this->ext = $info['extension'];

		$c = new stdClass();
		$c->r = $c->g = $c->b = 255;
		$this->color = $c;
	}

	function thumbFileName()
	{

		$optsFileName = "";
		if ($this->crop)
			$optsFileName .= "c";

		if ($this->fit)
			$optsFileName .= "f";

		if ($this->center)
			$optsFileName .= "m";

		if ($this->format)
			$ext = ($this->format=='jpeg'?'jpg':$this->format);
		else
			$ext = $this->ext;


		$thumbName = $this->dir .DS.'.'
					. 	$this->name .'.'. $this->ext
					.	(($this->width || $this->height)?'-' . $this->width . 'x' . $this->height:'')
					.	(($optsFileName != "")?'-'. $optsFileName:'')
					.	'.'. $ext;


		return $thumbName;
	}

	function searchFile(&$create)
	{
		$create = false;

		if (in_array($this->ext, array('gif', 'bmp')))
		{
			//Original file (No thumb)
			if ($this->info)
				$this->info->resize = true;
			return $this->dir .DS.$this->name .'.'. $this->ext;
		}


		$thumbFile = $this->thumbFileName();

		//Present thumb
		if ($thumbFile && JFile::exists($thumbFile))
			return $thumbFile;

		//Present original -> Need resample
		if ($this->file && JFile::exists($this->file))
		{
			$create = 'thumb';
			return $thumbFile;
		}

		//Present in dir fallback
		$fallback = $this->dir .DS. ZEFANIABIBLE_IMAGES_FALLBACK_NAME;
		if ($fallback && JFile::exists($fallback))
		{
			$create = 'fly';
			return $fallback;
		}

		//Present in root fallback
		$fallback = ZEFANIABIBLE_IMAGES_FALLBACK_ROOT .DS. ZEFANIABIBLE_IMAGES_FALLBACK_NAME;
		if ($fallback && JFile::exists($fallback))
		{
			$create = 'fly';
			return $fallback;
		}

		return null;
	}

	function typeFromMime($mime)
	{
		switch($mime)
		{
			case 'image/jpg':
			case 'image/jpeg':	$type = 'jpeg';	break;

			case 'image/png':	$type = 'png';	break;
			case 'image/gif':	$type = 'gif';	break;
			case 'image/wbmp':	$type = 'bmp';	break;

		}
		return $type;
	}

	function info()
	{
		$this->info = new stdClass();
		$this->get();

		return $this->info;
	}

	function outputInfo($file = null)
	{
		if (!$file)
			$file = $this->file;

		if ($this->info)
		{
			if ((self::exists($file)) && ($imageSize = getimagesize($file)))
			{
				$this->info->imagesize = new stdClass();
				$this->info->imagesize->width = $imageSize[0];
				$this->info->imagesize->height = $imageSize[1];
				$this->info->imagesize->bits = $imageSize['bits'];
				$this->info->imagesize->channels = isset($imageSize['channels'])?$imageSize['channels']:null;
				$this->info->imagesize->mime = $imageSize['mime'];
			}

			if ((self::exists($file)) && ($fileSize = filesize($file)))
				$this->info->filesize = $fileSize;
		}
	}

	function get()
	{
		$create = null;
		$file = $this->searchFile($create);

		if (!$this->mime)
			if ($file && JFile::exists($file))
			{
				$this->mime = self::getMime($file);
				$this->type = $this->typeFromMime($this->mime);
			}

		if (is_null($file))
		{
			$this->notFound();
			return;
		}

		if (!$this->isSupported())
		{
			$this->notSupported();
			return;
		}

		switch($create)
		{
			case 'thumb':

				if (!$this->width && !$this->height && (count($this->attributes) == 0))
				{
					$image = $this->load($this->file);
					$this->outputInfo();
					$this->render($image);
					return;
				}
				else
				{
					$size = (int)$this->width . "x" . (int)$this->height;
					//CREATE PHYSICAL THUMB
					if (($size == "0x0") || in_array($size, explode(",", ZEFANIABIBLE_IMAGES_ALOWED_SIZES)))
					{
						$image = $this->load();
						$image = $this->save($image, $file, true);
						return;
					}
					else
					{
						$this->notSupported();
						return;
					}
				}

			break;


			case 'fly':

				$this->outputInfo($file);
				$image = $this->load($file);
				$this->render($image);

				return;

				break;


			default:
				if ($this->info)
				{
					$this->calculateSize($w, $h, $widthCanvas, $heightCanvas, $top, $left, $scale);
					$this->outputInfo();
					return;
				}

				$this->output($file);
				return;

/*  SECOND METHOD - IMAGE OBJECT
				$data = file_get_contents($file);
	        	$image = imagecreatefromstring($data);
				$this->render($image);
				exit();
*/
				break;
		}

	}

	function notFound()
	{
		if ($this->info)
		{
			$this->info->error = 'not_found';
			return;
		}

		$image = $this->fromText("Not found");
		$this->render($image);
	}

	function notSupported()
	{
		if ($this->info)
		{
			$this->info->error = 'not_supported';
			return;
		}

		$image = $this->fromText("Not supported");
		$this->render($image);
	}

	function load($file = null)
	{

		if (!$file)
			$file = $this->file;

		if (!$this->type)
			$this->type = $this->typeFromMime(self::getMime($file));

		if($this->type=='jpeg') $image = imagecreatefromjpeg($file);
        if($this->type=='png') $image = imagecreatefrompng($file);
		if($this->type=='bmp') $image = imagecreatefromwbmp($file);

        if($this->type=='gif')
        {
        	$data = file_get_contents($file);
        	$image = imagecreatefromstring($data);
        }

        return $image;
	}

    function save($image, $thumbRelFile, $return=false, $write=true)
	{

		$this->calculateSize($w, $h, $widthCanvas, $heightCanvas, $top, $left, $scale);


    	$new_image = imagecreatetruecolor($widthCanvas, $heightCanvas);

    	imagealphablending($new_image, false);
		imagesavealpha($new_image, true);
		$color = $transparent = imagecolorallocatealpha($new_image, $this->color->r, $this->color->g, $this->color->b, 127);

		$offsetLeft = $offsetTop = 0;

		if ($this->format)
			$this->type = $this->format;

		if ($this->center)
		{
			$offsetLeft = -$left;
			$offsetTop = -$top;
		}

		//Image wrapper : Fill the border if needed
		imagefilledrectangle($new_image, 0, 0,
					$w + $offsetLeft*2,
					$h + $offsetTop*2,
					$color);



		//Resample the pixels
		$resampled = imagecreatetruecolor($w, $h);

		// Thanks blue-canoe ;-)
		imagesavealpha($resampled, true);
		imagealphablending($resampled, false);
		//

		imagecopyresampled($resampled, $image, 0, 0,
						0,
						0,
						$w,
						$h,
						$this->image_width,
						$this->image_height);


		//Instance the pixels on the image
		if ($this->opacity == 100)
			imagecopy($new_image, $resampled, $offsetLeft,$offsetTop,0,0,$w,$h);
		else
			imagecopymerge($new_image, $resampled, $offsetLeft,$offsetTop,0,0,$w,$h, $this->opacity);


		if ($write)
			$this->writeThumb($new_image, $thumbRelFile);

		if ($this->info)
			$this->outputInfo($thumbRelFile);
		else if ($return)
			$this->render($new_image);


        imagedestroy($image);
        imagedestroy($resampled);



        return $new_image;
    }

	function output($file = null)
	{
		if (!$file)
			$file = $this->file;

		//Read and return file contents without modification
	    header('Content-Type: ' . $this->mime);
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
	    ob_clean();
	    flush();

	    readfile($file);
		exit();
	}


    function render($image)
    {
    	if ($this->info)
    		return;

    	if (!$this->type)
    		exit();


    	header('Content-Type: image/'.$this->type);

        imagealphablending($image, false);
		imagesavealpha($image, true);


		if($this->type=='jpeg') imagejpeg($image, null, $this->quality);
		if($this->type=='png') imagepng($image, null);
		if($this->type=='bmp') imagewbmp($image, null);
		if($this->type=='gif') imagegif($image, null);

		imagedestroy($image);
		exit();

    }

    function writeThumb($image, $name=null)
    {

    	if (!ZEFANIABIBLE_IMAGES_PHYSICAL_THUMB)
    		return; //Skip writing physical thumb


    	if (!$name)
    		$name = $this->thumbFileName();


		imagealphablending($image, false);
		imagesavealpha($image, true);


		if($this->type=='jpeg') imagejpeg($image, $name, $this->quality);
		if($this->type=='png') imagepng($image, $name);
		if($this->type=='bmp') imagewbmp($image, $name);
		if($this->type=='gif') imagegif($image, $name);


		//Protect against execution
		@chmod($name, 0444);

    }

	function dir($dir='') {
		if(!$dir) return $this->dir;
		$this->dir = $dir;
	}

	function isSupported($mime = null)
	{
		if (!$mime)
			$mime = $this->mime;

		foreach($this->types as $type)
			if ($mime == "image/" . $type)
				return true;


		return false;
    }

    function attrs($att)
    {
    	if (!is_array($att))
    		$att = explode(",", $att);

    	$this->attributes = $att;

		foreach($att as $attribute)
		{
			if (preg_match("/^(.+):(.+)$/", $attribute, $matches))
			{
				$attribute = $matches[1];
				array_shift($matches);
				array_shift($matches);
				$params = $matches;

			}

			switch($attribute)
			{
				case 'fit':
				case 'crop':
				case 'center':
				case 'quality':
				case 'color':
				case 'format':
				case 'opacity':
					$this->$attribute(isset($params[0])?$params[0]:null);
					break;
			}
		}

    }

	function name($name='')
	{
		if(!$name) return $this->name;
		$this->name = $name;
	}

	function width($width='')
	{
		$this->width = min($width, ZEFANIABIBLE_IMAGES_MAX_WIDTH);
	}

    function height($height='') {
        $this->height = min($height, ZEFANIABIBLE_IMAGES_MAX_HEIGHT);
    }


	//	Adjust image size in order to fill all thumb size, cropping image
    function crop() {
    	$this->crop = true;
    }


	//	Allow positive zoom image to fit with the given size
	function fit() {
    	$this->fit = true;
    }

	//	Center image completing with transparent or white borders
	function center() {
		$this->center = true;
	}

	function quality($quality=80) {
    	$this->quality = $quality;
	}

	function color($hex='FFF')
	{
		$color = new stdClass();
		if(strlen($hex) == 3)
		{
			$color->r = hexdec(substr($hex, 0, 1)) * 16 + 15;
			$color->g = hexdec(substr($hex, 1, 1)) * 16 + 15;
			$color->b = hexdec(substr($hex, 2, 1)) * 16 + 15;
		}
		else if(strlen($hex) == 6)
		{
			$color->r = hexdec(substr($hex, 0, 2));
			$color->g = hexdec(substr($hex, 2, 2));
			$color->b = hexdec(substr($hex, 4, 2));
		}

        $this->color = $color;
    }

	function format($format = null)
	{
		if (in_array($format, $this->types))
	        $this->format = $format;
    }

    function show() {
        $this->save('', true);
    }

    function opacity($opacity = 100)
    {
    	$this->opacity = min((int)$opacity, 100);
    }

	// The engine is here ...
	function calculateSize(&$w, &$h, &$widthCanvas, &$heightCanvas, &$top, &$left, &$scale)
	{
		$propImage = $this->image_width/$this->image_height;
		$width = $this->width;
		$height = $this->height;
		if (($this->width) && ($this->height))
			$propThumb = $this->width/$this->height;
		else
		{
			$propThumb = $propImage;
			if ((!$this->width) && (!$this->height))
			{
				$width = $this->image_width;
				$height = $this->image_height;
			}
			else if ($this->width)
				$height = min($this->image_height, $this->width / $propImage);
			else if ($this->height)
				$width = min($this->image_width, $this->height * $propImage);
		}
		if (!$this->fit)
		{
			if (($propImage > $propThumb) && ($width > $this->image_width))
			{
				$width = $this->image_width;
				$height = $width / $propImage;
			}
			if (($propImage <= $propThumb) && ($height > $this->image_height))
			{
				$height = $this->image_height;
				$width = $height * $propImage;
			}
		}
		$scale = 1;
		if ((($propImage > $propThumb) && (!$this->crop))
			|| (($propImage < $propThumb) && ($this->crop)))
		{
			$refersTo = 'w';
			$w = $width;
			$h = round($width / $propImage);
			$scale = $width / $this->image_width;
		}
		else
		{
			$refersTo = 'h';
			$h = $height;
			$w = round($height * $propImage);
			$scale = $height / $this->image_height;
		}
		if ($this->fit)
		{
			if (($width > $this->image_width) && (!$this->height))
			{
				$w = $width;
				$hFit = round($width / $propImage);
				$h = $hFit;
			}
			else if (($height > $this->image_height) && (!$this->width))
			{
				$h = $height;
				$wFit = round($height * $propImage);
				$w = $wFit;
			}
		}
		$top = $this->top;
		$left = $this->left;
		if ($this->crop)
		{
			$widthCanvas = $width;
			$heightCanvas = $height;
			if (isset($wFit))
				$widthCanvas = $wFit;
			if (isset($hFit))
				$heightCanvas = $hFit;
			if ($refersTo == 'w')
			{
				$imgH = $widthCanvas / $propImage;
				$top = round((($imgH - $heightCanvas) / 2));
			}
			else
			{
				$imgW = $heightCanvas * $propImage;
				$left = round((($imgW - $widthCanvas) / 2));
			}
		}
		else
		{
			$widthCanvas = $w;
			$heightCanvas = $h;
		}
		if ($this->center)
		{
			if ($widthCanvas < $this->width)
			{
				$left = ($widthCanvas - $this->width)/2;
				$widthCanvas = $this->width;
			}
			if ($heightCanvas < $this->height)
			{
				$top = ($heightCanvas - $this->height)/2;
				$heightCanvas = $this->height;
			}
		}

		//FILE INFO
		if ($this->info)
		{
			$this->info->w = $w;
			$this->info->h = $h;
			$this->info->widthCanvas = $widthCanvas;
			$this->info->heightCanvas = $heightCanvas;
			$this->info->top = -$top;
			$this->info->left = -$left;
			$this->info->scale = $scale;
		}
	}

	function convertHEX($hex)
	{
		if($hex == "000000"){
		return(array('r'=>102,'g'=>153,'b'=>102));
		}
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
		$rs = array('r'=>$r,'g'=>$g,'b'=>$b);
		return($rs);
	}

	function fromText($txt)
	{
		$this->type = "gif";

		$fontSize = 4;
		$h = $fontSize * 4.5;
		$w = strlen($txt) * $h / 1.6;
		$img = imagecreate($w, $h);
		$background_color = imagecolorallocate ($img, 0, 0, 0);
		$transparent_color = imagecolortransparent($img,$background_color);
		$textColor = imagecolorallocate($img,0,0,0);
		imagestring($img, $fontSize,0,0, $txt,$textColor);

		return $img;
	}

}