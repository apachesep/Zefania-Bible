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


class JDomHtmlGridTaskState extends JDomHtmlGridTask
{
	var $fallback = 'bool';	//Used for default
	
	protected $states = array();
	protected $togglable;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *  @commandAcl : ACL Command to see or not this
	 *  @enabled	: Determines if enabled
	 *  @tooltip	: Show the description in tooltip
	 *  @ctrl		: controller to call (prefix)
	 *	@task		: Task name (used also for the icon class if taskIcon is empty)
	 *	@taskIcon	: Task icon
	 *	@label		: Button title, Label text description
	 *  @description : Description of the task. (in tooltip)
	 *	@viewType	: View mode (icon/text/both) default: icon
	 *
	 *  @states		: States to config the JHtml states buttons
	 *	@togglable	: When not togglable, output a fly (no action)
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('states'		, null, $args, array());
		$this->arg('togglable'		, null, $args, true);

		if (!$this->togglable)
			$this->enabled = false;
	}
	
	function buildHtml()
	{
		$html = JHtml::_('jgrid.state', 
			$this->states, 
			$this->dataValue,
			$this->num,
			array(
				'enabled' => $this->enabled,
				'translate' => true,
				'checkbox' => 'cb',
				'prefix' => ($this->ctrl?$this->ctrl . '.':'')
			)
		);
		
		return $html;
	}
}