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


class JDomHtmlGridColor extends JDomHtmlGrid
{
	var $width;
	var $height;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
	 *	@width		: Color zone width
	 *	@height		: Color zone height
	 *
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('width'			, 6, $args, "20");
		$this->arg('height'			, 7, $args, "20");

	}

	function build()
	{
		$this->addStyle('width', $this->width . 'px');
		$this->addStyle('height', $this->height . 'px');
		$this->addStyle('background-color', '#' . ltrim($this->dataValue, '#'));
		$this->addStyle('display', 'inline-block');

		$this->addClass('grid-color');

		$html = '<span <%STYLE%><%CLASS%><%SELECTORS%>>'
			.	'</span>';


		return $html;
	}

}