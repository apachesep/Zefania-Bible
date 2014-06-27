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


class JDomHtmlFormLabel extends JDomHtmlForm
{
	public $domId;
	public $label;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 * 	@domID		: database field name
	 * 	@label		: label caption (JText)
	 *  @domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('domId'		, null, $args);
		$this->arg('label'		, null, $args);
		$this->arg('domClass'	, null, $args);
		$this->arg('selectors'	, null, $args);
	}

	function build()
	{
		$html = '<label'
			.	' for="' . $this->domId . '"'
			.	$this->buildDomClass()
			.	$this->buildSelectors()
			.	'>'
			.	$this->JText($this->label)
			.	'</label>';
		return $html;
	}
}