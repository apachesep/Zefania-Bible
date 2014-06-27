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


class JDomHtmlGridTask extends JDomHtmlGrid
{
	var $fallback = 'task';


	protected $commandAcl;
	protected $enabled;
	protected $tooltip;
	protected $ctrl;
	protected $task;
	protected $taskIcon;
	protected $label;
	protected $viewType;
	protected $target;
	protected $description;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
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

		$this->arg('commandAcl'		, null, $args);
		$this->arg('enabled'		, null, $args, true);
		$this->arg('tooltip'		, null, $args);
		$this->arg('ctrl'			, null, $args);
		$this->arg('task'			, null, $args);
		$this->arg('taskIcon'		, null, $args);
		$this->arg('label'			, null, $args);
		$this->arg('description'	, null, $args);
		$this->arg('viewType'		, null, $args, 'both');
		$this->arg('target'			, null, $args);

		//Default tooltip value (depending on viewType)
		if ($this->tooltip === null)
		{
			if ($this->viewType == 'icon')
				$this->tooltip = false;
			else
				$this->tooltip = true;
		}

		//Uset tasks if ACL does not permit
		if ($this->commandAcl && !$this->access($this->commandAcl))
			$this->enabled = false;

		if (!$this->enabled)
			$this->tooltip = false;
		
		$this->getIconName();
		
	}

	function getIconName()
	{	
		if (empty($this->taskIcon)){
			$this->taskIcon = $this->getTaskExec();
		}
		
		$name = $this->taskIcon;
		$this->taskIcon = $name . ' ' . $this->iconLibrary;

		return $name;
	}
	

	function getTaskExec($ctrl = false)
	{

		//Get the task behind the controller alias (Joomla 2.5)
		if (!$task = $this->task)
			return;

		$ctrlName = "";

		$parts = explode(".", $task);
		$len = count($parts);
		$taskName = $parts[$len - 1]; //Last
		if ($len > 1)
			$ctrlName = $parts[0];


		if ($ctrl)
			return $ctrlName . "." . $taskName;

		return $taskName;
	}

}