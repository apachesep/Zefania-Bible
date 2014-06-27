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


class JDomHtmlGridTaskStateDefault extends JDomHtmlGridTaskState
{
	protected $task;

	protected $strNO;
	protected $strYES;

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
	 *	@viewType	: View mode (icon/text/both) default: icon
	 *
	 *	@taskYes		: task to execute when value is true
	 *	@taskNo			: task to execute when value is no
	 *	@strYES			: text to show when value is true
	 *	@strNO			: text to show when value is no
	 *	@strUndefined	: text to show when value is undefined
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('task'				, null, $args, 'default');
	
		$this->arg('strNO'				, null, $args);
		$this->arg('strYES'				, null, $args);
		
		if ((bool)$this->dataValue)
			$this->enabled = false;
		
		if (!$this->enabled)
			$this->tooltip = false;
		
		$this->taskYes = null;
		
		$lib = ' ' . $this->iconLibrary;
		$icons = array(0=> 'star-empty'. $lib, 1=> 'star'. $lib);
		if (!$this->jVersion('3.0'))
			$icons = array(0=> 'notdefault'. $lib, 1=> 'default'. $lib);
	
		if ($this->togglable)
		{
			if (empty($this->strYES))
				$this->strYES = '';

			if (empty($this->strNO))
				$this->strNO = 'JDEFAULT';
		}
		
		
		//Add the descriptions in the tooltip. Same description is used for each state
		if (!empty($this->strYES))
			$this->strYES = JText::_($this->strYES) . '::' . $this->description;
	
		if (!empty($this->strNO))
			$this->strNO = JText::_($this->strNO) . '::' . $this->description;
		
		
		// TODO : You can eventually customize your behaviors icons and strings here
		$this->states	= array(

			// When value = 1
			1	=> array(
			
				null,	// Task to execute
				$this->strYES,	// Text
				$this->strYES,	// Tooltip description when boolean is enabled
				$this->strYES,	// Tooltip description when boolean is disabled
				$this->tooltip,	// Show tooltip ?
				$icons[1],		// Css class when active (enabled)
				$icons[1]),		// Css class when inactive (disabled)
				
			// When value = 0 (see before)			
			0	=> array($this->task,		$this->strNO,	$this->strNO,	$this->strNO,	$this->tooltip,	$icons[0],	$icons[0]),
			''	=> array($this->task,		$this->strNO,	$this->strNO,	$this->strNO,	$this->tooltip,	$icons[0],	$icons[0]),
		);
		
		
	}
	
	function build()
	{
		$html = $this->buildHtml();
		return $html;
	}
}