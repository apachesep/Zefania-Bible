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


class JDomHtmlFlyImage extends JDomHtmlFly
{
	var $level = 3;			//Namespace position
	var $last = true;

	protected $width;
	protected $height;
	protected $markup;
	protected $src;
	protected $indirect;
	protected $root;
	protected $title;
	protected $alt;
	

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 * 	@width		: Width of the image
	 *	@height		: Height of the image
	 *  @markup		: Image HTML Markup (div, span, img)
	 *  @src		: Source of the image (can be empty if domClass defined
	 *  @indirect	: Indirect File access
	 *  @root		: root directory (used in indirect file access)
	 *  @title		: Title text for this image
	 *  @alt		: Alternative text for this image (default : title)
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('width' 		, null, $args);
		$this->arg('height' 	, null, $args);
		$this->arg('markup' 	, null, $args, 'img');
		$this->arg('src' 		, null, $args);
		$this->arg('indirect'	, null, $args);
		$this->arg('root'		, null, $args);
		$this->arg('title'		, null, $args);
		$this->arg('alt' 		, null, $args, $this->title);


		if (!$this->width || !$this->height)
			$this->markup = 'img';
		
		if ($this->indirect)
		{
			$this->src = $this->indirectUrl();
		}
		else if ($this->root)
		{
			//If indirect is set, root is a marker
			$this->src = $this->fileUrl($this->root, $this->src);
		}

// Image loaded through	a class name in a simple markup (div per default)
		if ($this->domClass)
		{
			if ($this->markup == 'img') //Change the default marker
				$this->markup = 'div';
		}
		
// Default IMG markup (physical image)
		if ($this->markup == 'img')
		{
			if ($this->src)
				$this->addSelector('src', $this->src);
			
			if ($this->width)
				$this->addSelector('width', $this->width . 'px');

			if ($this->height)
				$this->addSelector('height', $this->height . 'px');
				
			if ($this->alt)
				$this->addSelector('alt', $this->JText($this->alt));
		}
		
		
// Any other markup : instance CSS styles in the tag.
		else
		{				
			if ($this->src)
				$this->addStyle('background-image', 'url(' . $this->src . ')');	

			if ($this->width)
				$this->addStyle('width', $this->width . 'px');
	
			if ($this->height)
				$this->addStyle('height', $this->height . 'px');

			$this->addStyle('background-repeat', 'no-repeat');	
			$this->addStyle('background-position', 'center');
			$this->addStyle('display', 'inline-block');
		}

		if ($this->title)
			$this->addSelector('title', $this->JText($this->title));

	}
	
	function indirectUrl()
	{
		$indirectUrl = "";
		if ($this->indirect)
		{
			$path = $this->dataValue;
			if (!empty($this->url))
				$path = $this->url;
			
			if (!preg_match("/\[.+\]/", $this->dataValue))
				$path = $this->root . $path;

			$indirectUrl = JURI::base(true) . "/index.php?option=" . $this->getExtension()
						. "&task=file&path=" . $path;

			if ($this->width && $this->height)
				$indirectUrl .= "&size=" . $this->width ."x". $this->height;
	
		}
		
		return $indirectUrl;
	
	}

	function fileUrl($root, $file)
	{
		//Manage forks
		if (file_exists(JPATH_SITE . DS. $root .DS. 'fork' .DS. $file))
			$file = 'fork' .DS. $file;

		//Convert File in url
		$fileUrl = $this->pathToUrl(JPATH_SITE .DS. $root .DS. $file);		
		
		return $fileUrl;
	}
	
	function build()
	{
		$html = '';
		
		if ($this->markup == 'img')
			$html = '<img<%CLASS%><%STYLE%><%SELECTORS%>/>';
		else
			$html = '<<%MARKUP%><%CLASS%><%STYLE%><%SELECTORS%>>' . $html . '</<%MARKUP%>>';

		return $html;
	}
}