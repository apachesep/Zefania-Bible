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

class JDomHtmlLinkMenuSubmenu extends JDomHtmlLinkMenu
{
	var $level = 4;				//Namespace position
	var $last = true;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		if ($this->active)
			$this->addClass('active');	
	}
	
	function build()
	{
		$html = '<li<%CLASS%>>' 
			.	 $this->buildLink()
			. 	'</li>';
		
		return $html;
	}

	//Override to make it simplier
	function buildLink()
	{
		$this->addStyle('cursor', 'pointer');
		
		$html = "<a<%STYLE%><%TITLE%><%HREF%><%SELECTORS%>>"
			.	$this->content
			.	"</a>";

		return $html;
	}

}