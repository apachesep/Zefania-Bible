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


class JDomHtmlGridTaskStateBool extends JDomHtmlGridTaskState
{
	
	protected $taskYes;
	protected $taskNo;

	protected $strUndefined;
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
	 *  @description : Description of the task. (in tooltip)
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


		$this->arg('taskYes'			, null, $args);
		$this->arg('taskNo'				, null, $args, $this->taskYes);
	
		$this->arg('strUndefined'		, null, $args, '');
		$this->arg('strNO'				, null, $args, 'JNO');
		$this->arg('strYES'				, null, $args, 'JYES');
		
				
		// TODO : You can customize the behaviors icons and strings here
		$this->states	= array(

			// When value = 1
			1	=> array(
			
				$this->taskYes,	// Task to execute
				$this->strYES,	// Text
				$this->strYES,	// Tooltip description when boolean is enabled
				$this->strYES,	// Tooltip description when boolean is disabled
				$this->tooltip,	// Show tooltip ?
				'publish',		// Css class when active (enabled)
				'publish'),		// Css class when inactive (disabled)
				
			// When value = 0 (see before)			
			0	=> array($this->taskNo,		$this->strNO,	$this->strNO,	$this->strNO,	$this->tooltip,	'unpublish',	'unpublish'),
			''	=> array($this->taskNo,		$this->strUndefined,	$this->strUndefined,	$this->strUndefined,	$this->tooltip,	'warning',	'warning'),
		);
		
		
	}
	
	function build()
	{
		$html = $this->buildHtml();
		return $html;
	}
	

}