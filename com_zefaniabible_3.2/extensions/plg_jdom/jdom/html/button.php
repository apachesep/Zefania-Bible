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


class JDomHtmlButton extends JDomHtml
{
	var $fallback = 'default';	//Used for default

	protected $href;
	protected $js;
	protected $text;
	protected $title;
	protected $target;
	protected $icon;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *	@href		: Link of the button
	 *	@js			: Javascript for the button
	 *	@text		: Text of the button
	 *	@title		: Title of the button (default : @text)
	 *	@target		: Target of the link
	 *	@icon		: Icon of the button (Joomla class name)
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('href'		, 2, $args);
		$this->arg('js'			, 3, $args);
		$this->arg('text'		, 4, $args);
		$this->arg('title'		, 5, $args, $this->text);

		$this->arg('target'		, 6, $args);
		$this->arg('icon'		, 7, $args);


	}

	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'TITLE' => " title=\"".htmlspecialchars($this->text)."\"",
			'HREF' => ($this->href?" href=\"" .htmlspecialchars($this->href) . "\"":""),
			'JS' => ($this->js?" onclick=\"" .htmlspecialchars($this->js) . "\"":""),
			'TARGET' => ($this->target?" target='" . $this->target . "'":""),
			'BUTTON_ICON' => $this->icon,
		), $vars));
	}
	

}