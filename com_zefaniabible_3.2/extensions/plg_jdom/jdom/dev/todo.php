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


class JDomDevTodo extends JDomDev
{
	//TODO : You can disable the feature here
	protected $enabled = true;	//false

	var $assetName = 'dev';

	var $attachCss = array(
		'dev.css',
	);

	protected $align = null;
	protected $message = null;
	protected $file = null;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 * 	@align		: Decide where to place the image
	 * 	@message	: facultative message to show
	 * 	@file		: facultative file to trim
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('align' , null, $args, 'right');
		$this->arg('message' , null, $args);
		$this->arg('file' , null, $args);
	}

	function build()
	{
		if (!$this->enabled)
			return;

		$html = '';

		$align = $this->align;

		if (!in_array($align, array('left', 'right')))
			$align = 'right';

		$other = ($align == 'left'?'right':'left');

		$htmlCisors = "<span class='cook-cisors cook-cisors-" . $align . "' style='float:" . $align . "'></span>";
		$htmlLine = "<div class='cook-dashed' style='margin-" . $align . ":80px'></div>";

		$html .= "<div style='height:45px;clear:both;'>";

		$message = null;
		if (!empty($this->message))
			$message = $this->message;

		$file = null;
		if (!empty($this->file))
			$file = '[ROOT]' . substr($this->file, strlen(JPATH_SITE));

		$html .= $htmlCisors;
		if ($message)
			$html .= "<span style='float:" . $other . "'>"
			. 	$this->message
			. "</span>";

		$html .= $htmlLine;

		if ($file)
			$html .= "<span style='float:" . $other . "'>"
			.  '<span class="cook-todo-file">' . $file . '</span>'
			. "</span>";

		$html .= '</div>';

//TODO : You can see icon libraries
//Icons library
/*
		$html .= JDom::_('dev.lib.icons', array(
			'display' => 'raw'
		));
*/
	
		return $html;
	}

}