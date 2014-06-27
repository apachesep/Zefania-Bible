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


class JDomHtmlGridBoolPublish extends JDomHtmlGridBool
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
	 *
	 *	@togglable	: if you want this bool execute a task on click
	 *	@commandAcl : ACL rights to toggle
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		if ($this->togglable)
		{
			$ctrl = ($this->ctrl?$this->ctrl.'.':'');
			$this->task = $ctrl . (empty($this->dataValue)?'publish':'unpublish');			
		}
	}

	function build()
	{
		$html = '';

		$ctrl = ($this->ctrl?$this->ctrl.'.':'');
		$html = JDom::_('html.grid.bool', array_merge($this->options, array(
											'strNO' => ($this->togglable?'JTOOLBAR_PUBLISH':'JNO'),
											'strYES' => ($this->togglable?'JTOOLBAR_UNPUBLISH':'JYES'),
											)));
		return $html;
	}

}