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


class JDomHtmlGridBoolDefault extends JDomHtmlGridBool
{
	var $level = 4;			//Namespace position
	var $last = true;		//This class is last call
	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *	@commandAcl : ACL rights to toggle
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->togglable = ($this->togglable?empty($this->dataValue):null);
		$ctrl = ($this->ctrl?$this->ctrl.'.':'');
		$this->task = ($this->togglable?$ctrl . 'default':null);
		
	}
	
	function build()
	{
		$html = JDom::_('html.grid.bool', array_merge($this->options, array(
											'strNO' => ($this->togglable?'JTOOLBAR_DEFAULT':'JNO'),
											'strYES' => 'JYES',
											'iconClass' => 'grid-task-icon '
													.	'icon-16 ' . ($this->dataValue?'default':'default_no')
											)));
		return $html;
	}
}