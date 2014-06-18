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


class JDomHtmlIcon extends JDomHtml
{
	protected $icon;
	protected $tooltip;
	protected $title;
	protected $color;
	protected $size;
	protected $library;
	protected $availableLibraries = array('icomoon', 'glyphicon');
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *	@icon		: Icon name see : http://icomoon.io/#preview-free
	 *  @tooltip	: Has a tooltip
	 *  @tilte		: Title for Tooltip
	 *  @library	: Icon library
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('icon', null, $args);
		$this->arg('tooltip', null, $args);
		$this->arg('title', null, $args);
		$this->arg('color', null, $args);
		$this->arg('size', null, $args);

		$this->arg('library', null, $args, $this->iconLibrary);
			
		$parts = explode('-', $this->icon);
		if ((count($parts) > 1) && in_array($parts[0], $this->availableLibraries))
		{
			$this->library = array_shift($parts);
			$this->icon = implode('-', $parts);
		}
		
	}

	function build()
	{
		$this->addClass($this->getIconClass());

		switch($this->library)
		{				
			case 'icomoon':
				$this->buildFont();			
				break;
		}
		
		$html = '<i <%STYLE%><%CLASS%><%SELECTORS%>></i>';
		return $html;
	}
	
	public function getIconClass()
	{

		return 'icon-' . $this->icon . ($this->library?' ' . $this->library:'');
	}
	
	protected function buildFont()
	{
		if ($color = $this->color)
			switch($color)
			{
				case 'default':
				case 'primary':
				case 'info':
				case 'success':
				case 'warning':
				case 'danger':
					$this->addClass('color-' . $color);	
					break;
						
				default:
					$this->addStyle('color', '#' . ltrim($color, '#'));						
					break;
			}
			
			
		if ($size = $this->size)
		{
			$this->addStyle('font-size', $size);
			$this->addStyle('width', 'inherit');
			$this->addStyle('height', 'inherit');
		}
		
	}
	
	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'STYLE'		=> $this->buildDomStyles(),
			'CLASS'			=> $this->buildDomClass(),		//With attrib name
			'SELECTORS'		=> $this->buildSelectors(),
		), $vars));
	}

}