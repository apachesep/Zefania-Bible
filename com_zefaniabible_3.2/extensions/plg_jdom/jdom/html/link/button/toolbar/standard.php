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


class JDomHtmlLinkButtonToolbarStandard extends JDomHtmlLinkButtonToolbar
{
	/*
	 * Constuctor
	 * 	@namespace 	: Requested class
	 *  @options	: Parameters
	 *  @item		: Joomla Toolbar Item arguments (array)
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		//Dispatch arguments
		$this->name = $this->item[1];
		$this->text = $this->item[2];
		$this->task = $this->item[3];
		$this->list = $this->item[4];

		//Class
		if (isset($this->item[5]))
			$this->addClass($this->item[5]);
	}

}