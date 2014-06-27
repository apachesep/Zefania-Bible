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

class JDomHtmlLabel extends JDomHtml
{
	protected $content;
	protected $color;
	protected $size;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *	@content	: Content of the badge
	 *  @color		: Badge color
	 *  @size		: Badge size
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('content', null, $args);
		$this->arg('color', null, $args);
		$this->arg('size', null, $args);
	}

	function build()
	{
		if (empty($this->content))
			return '';
	
		$this->addClass('label');
		
		if ($this->color)
			$this->addClass('label-' . $this->color);
				
		$html = '<span <%STYLE%><%CLASS%><%SELECTORS%>>'
			.	$this->content
			.	'</span>';
			
		return $html;
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