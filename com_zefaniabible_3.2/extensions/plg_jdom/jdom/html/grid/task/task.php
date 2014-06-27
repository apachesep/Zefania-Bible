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


class JDomHtmlGridTaskTask extends JDomHtmlGridTask
{
	
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
	 *  @target 	: target when a link
	 * 
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
	}

	function build()
	{
		if ($this->viewType == 'text')
		{
			//Text alone
			
			$html = JDom::_('html.fly', array(
				'dataValue' => JText::_($this->label),
				'task' => $this->task,
				'num' => $this->num,
				'tooltip' => $this->tooltip,
				'title' => JText::_($this->label),
				'description' => $this->description
			));
			
			return $html;
		}
		
		
		return JHTML::_('jgrid.action', 
			$this->num,
			$this->task,
			array(
				'active_title' => JText::_($this->label) . '::' . JText::_($this->description),
				'inactive_title' => JText::_($this->label) . '::' . JText::_($this->description),
				'tip' => $this->tooltip,
				'active_class' => $this->taskIcon,
				'inactive_class' => $this->taskIcon,
				'enabled' => $this->enabled,
				'translate' => true,
				'checkbox' => 'cb',
				'prefix' => ($this->ctrl?$this->ctrl.'.':''),
			)
		);

	}
	
	function buildJs()
	{
		
		switch ($this->getTaskExec())
		{
			case 'delete':
				$js = 'const PLG_JDOM_ALERT_ASK_BEFORE_REMOVE = "' . JText::_('PLG_JDOM_ALERT_ASK_BEFORE_REMOVE') .'";';
				break;				

			case 'trash':
				$js = 'const PLG_JDOM_ALERT_ASK_BEFORE_TRASH = "' . JText::_('PLG_JDOM_ALERT_ASK_BEFORE_TRASH') .'";';
				break;				

		}

		if (isset($js))
			$this->addScriptInline($js, false, null);
	}
}