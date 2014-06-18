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


class JDomHtmlFormInputFile extends JDomHtmlFormInput
{
	var $fallback = 'default';		//Used for default

	protected $indirect;
	protected $root;
	protected $width;
	protected $height;
	protected $attrs;
	protected $cid;
	protected $view;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 *	@indirect	: Indirect File access
	 *	@root		: Default folder (alias : ex [DIR_TABLE_FIELD]) -> Need a parser (Cook helper)
	 *	@width		: Thumb width
	 *	@height		: Thumb height
	 *	@attrs		: File attributes ('crop', 'fit', 'center', 'quality')
	 *
	 *
	 *	-> Token - db mode : Require the cid of the image to show it.
	 *	@cid		: Cid of the image item (Token DB file check)
	 *  @view		: Table from which this image is from
	 */
	function __construct($args)
	{

		parent::__construct($args);
		$this->arg('indirect'	, null, $args);
		$this->arg('root'		, null, $args);
		$this->arg('width'		, null, $args, 0);
		$this->arg('height'		, null, $args, 0);
		$this->arg('attrs'		, null, $args);

		$this->arg('cid'		, null, $args);
		$this->arg('view'		, null, $args);
		
		$this->arg('actions'		, null, $args);

		$this->thumb = ($this->width || $this->height);

	}


	function removeList()
	{
		if (!isset($this->actions) || empty($this->actions))
			return;
		
		$list = array();

		$list[] = array('value' => '', 'text' => $this->JText("PLG_JDOM_FILE_REMOVE_KEEP"), 'icon' => 'icomoon-cancel');

		
		if (in_array('remove', $this->actions))
			$list[] = array('value' => 'remove', 'text' => $this->JText("PLG_JDOM_FILE_REMOVE_EJECT"), 'icon' => 'icomoon-out');

		if (in_array('thumbs', $this->actions))
			$list[] = array('value' => 'thumbs', 'text' => $this->JText("PLG_JDOM_FILE_REMOVE_THUMBS_ONLY"), 'icon' => 'icomoon-pictures');

		if (in_array('trash', $this->actions))
			$list[] = array('value' => 'trash', 'text' => $this->JText("PLG_JDOM_FILE_REMOVE_TRASH"), 'icon' => 'icomoon-trash');

		if (in_array('delete', $this->actions))
			$list[] = array('value' => 'delete', 'text' => $this->JText("PLG_JDOM_FILE_REMOVE_DELETE"), 'icon' => 'icomoon-remove');

		return $list;
	}
	
}