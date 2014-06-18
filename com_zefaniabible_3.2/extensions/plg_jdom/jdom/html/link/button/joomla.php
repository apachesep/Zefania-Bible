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


class JDomHtmlLinkButtonJoomla extends JDomHtmlLinkButton
{
	var $level = 4;			//Namespace position
	var $last = true;

	protected $icon;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *	@href		: Link
	 *	@link_js			: Javascript for the link
	 *	@content	: Content of the link
	 *	@link_title		: Title of the link (default : @content)
	 *	@target		: Target of the link  (added to natives targets : 'modal')
	 *	@domClass	: CSS class of the link
	 *	@modal_width	: Modal width
	 *	@modal_height	: Modal height
	 *
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('icon'		, null, $args);
	}

	function build()
	{

		$html = "<div class='button2-left'>"
			.	"<div class='" . $this->icon . "'>"

			.	$this->buildLink()

			.	"</div>"
			.	"</div>\n";


		return $html;
	}

}