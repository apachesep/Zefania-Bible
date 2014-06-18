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


class JDomHtmlGridHeaderSaveorder extends JDomHtmlGridHeader
{
	protected $ctrl;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 */
	function __construct($args)
	{
		$this->arg('ctrl'		, null, $args);
		parent::__construct($args);
	}

	function build()
	{
		$dir = $this->pathToUrl($this->systemImagesDir(), true);
		$task = $this->ctrl . '.saveorder';
		
		$alt = JText::_( 'JGRID_HEADING_ORDERING' );

		$htmlIcon = '<div style="background-image:url(' . $dir . '/filesave.png); width:16px; height:16px; display:inline-block"></div>';			

		$html = JDom::_('html.link', array(
			'content' => $htmlIcon,
//			'domClass' => 'saveorder',
			'title' => JText::_( 'Save Order' ),
			
			'task' => $task,
		
		));
		
		return $html;
	}



}