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

class JDomHtmlLinkMenuCpanel extends JDomHtmlLinkMenu
{
	protected $imageSrc;
	protected $imageClass;
	protected $iconSize;
	protected $buttonWidth;
	protected $buttonHeight;
	
	
	//Static configuration
	protected $marginW = 60;
	protected $marginH = 45;
	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *	@preview	: Preview type
	 *	@href		: Link
	 *	@link_title	: Title on the link
	 *	@target		: Target of the link  ('download', '_blank', 'modal', ...)
	 * 	
	 * 
	 * 	@imageSrc	: Image source
	 * 	@imageClass : Image CSS class
	 *  @iconSize : IconSize
	 *  @buttonWidth : Button width
	 *  @buttonHeight : Button height
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('imageSrc'		, null, $args);
		$this->arg('imageClass'		, null, $args);
		$this->arg('iconSize'	, null, $args, 48);
		$this->arg('buttonWidth'	, null, $args, 100);
		$this->arg('buttonHeight'	, null, $args, 100);
		
		if ($this->target == 'modal')
			$this->modalLink();
		
	}


	function build()
	{
		$html = '';
		
		//Image
		$image = JDom::_('html.fly.image', array(
			'domClass' => $this->imageClass,
			'width' => $this->iconSize,
			'height' => $this->iconSize,
			'title' => $this->link_title
		));
		$html .= $image;
		
		
		//Label
		$html .= "<span>". $this->content ."</span>";
		
		
		//Too keep icon in middle, and little bit up. Javascript could be better here
		$paddingTop = ($this->buttonHeight - $this->iconSize)/2 - ($this->buttonHeight/8);
	
	$paddingBottom = 0;

		//Embed html in a link
		$html = JDom::_('html.link', array_merge($this->options, array(
			'content' => $html,
			'styles' => array(
				'width' => $this->buttonWidth . 'px',
				'height' => $this->buttonHeight - $paddingTop . 'px',
				'padding-top' => $paddingTop . 'px',
				'padding-bottom' => $paddingBottom . 'px'
				
			)
		)));
		
		//Embed html in button
		$html = JDom::_('html.fly', array(
			'markup' => 'div',
			'domClass' => 'button',
			'dataValue' => $html,
			
		));
		
		//Embed html in floating
		$html = '<div style="float:left;">'
			.	$html
			.	'</div>';

		return $html;
	}
}