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


class JDomHtmlMenuSubmenu extends JDomHtmlMenu
{
	var $level = 3;				//Namespace position
	var $last = true;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *	@list		: Menu items
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

	}

	function build()
	{
		if (empty($this->list))
			return;
		
		$html = '';
		
		$html .= '<ul id="submenu" class="nav nav-list">';
		
		foreach($this->list as $item)
		{
			$html .= JDom::_('html.link.menu.submenu', array(
				'content' => JText::_($item[0]),
				'href' => $item[1],
				'active' => $item[2]
			));
		}
		
		$html .= '</ul>';
		
		return $html;
	}

}