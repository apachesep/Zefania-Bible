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


class JDomHtmlLinkButtonIcon extends JDomHtmlLinkButton
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
	 *	@icon		: Icon for the button
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('icon', null, $args);
	}

	function build()
	{
		$htmlIcon = JDom::_('html.icon', array(
			'icon' => $this->icon
		));
	
		$html = JDom::_('html.link.button', array_merge($this->options, array(
			'domClass' => 'btn hasTooltip',
			'content' => $htmlIcon
		)));
		
		return $html;
	}
			
}