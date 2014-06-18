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

class JDomHtmlFlyFile extends JDomHtmlFly
{
	var $fallback = 'default';		//Used for default

	

	protected $indirect;
	protected $width;
	protected $height;
	protected $root;
	protected $attrs;

	protected $view;
	protected $cid;
	protected $listKey;
	
	protected $thumb;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *	@root		: Default folder (alias : ex [DIR_TABLE_FIELD]) -> Need a parser (Cook helper)
	 *	@width		: Thumb width
	 *	@height		: Thumb height
	 *	@attrs		: File attributes ('crop', 'fit', 'center', 'quality')
	 * 
	 *	@indirect	: Indirect File access
	 * 		indirect : access through controler to decode path
	 * 		direct 	: access through direct URL
	 * 		physical: retreive real file path (No preview, return string)
	 * 		index	: through database index : Filter item accesses to protect files
	 * 				Required for 'index' :
	 * 					@view 		: Table name
	 * 					@dataKey 	: Image path field
	 * 					@listKey 	: Dynamic Item id (with dataObject)
	 * 					@cid		: Static Item id
	 */
	function __construct($args)
	{

		parent::__construct($args);


		$this->arg('indirect'	, null, $args, 'indirect');
		$this->arg('root'		, null, $args);
		$this->arg('width'		, null, $args, 0);
		$this->arg('height'		, null, $args, 0);
		$this->arg('attrs'		, null, $args);

		$this->arg('listKey'	, null, $args, 'id');
		$this->arg('view'		, null, $args);
		$this->arg('cid'		, null, $args);

		$this->thumb = ($this->width || $this->height);

		if ($this->indirect === true)
			$this->indirect = 'indirect';
		else if ($this->indirect === false)
			$this->indirect = 'direct';

	}

	function getFileUrl($thumb = false, $link = false)
	{
		$helperClass = $this->getComponentHelper();		
		if (!$helperClass)
			return;

		if (($this->indirect != 'index') && empty($this->dataValue))
			return;
		
		if (empty($path))
			$path = $this->root .DS. $this->dataValue;


		// $link = false when creating the image thumb. 'download' not allowed in this case.
		// Then, pass a second time to eventually create the download URL	
		$options = array();
		if ($thumb)
			$options = array(
				'width' => $this->width,
				'height' => $this->height,
				'attrs' => $this->attrs,			
			);
		else if ($link)
		{
			$options = array(
				'download' => ($this->target == 'download')
			
			);
		}

		switch ($this->indirect)
		{
			case 'index':		// Indexed image url
				if ((!$cid = $this->cid) && $this->dataObject && ($listKey = $this->listKey))
					$cid = $this->dataObject->$listKey;
					
				$url = $helperClass::getIndexedFile($this->view, $this->dataKey, $cid, $options);
				break;
				
			case 'indirect':	// Indirect file access
			case 'physical':	// Physical file on the drive (url is a path here)
			case 'direct':		// Direct url
			default:
				$url = $helperClass::getFile($path, $this->indirect, $options);
				break;
		}	
		
			
		/* Uncomment to see the returned url */
		//echo('<pre>');print_r($url);echo('</pre>');

		return $url;
	}
}