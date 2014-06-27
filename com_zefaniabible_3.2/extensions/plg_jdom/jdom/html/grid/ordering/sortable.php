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


class JDomHtmlGridOrderingSortable extends JDomHtmlGridOrdering
{
	protected $canChange;	
	protected $showInput;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *	@task		: Task to execute.
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('showInput'	, null, $args, false);
	}


	function build()
	{
		
		$html = '';
	
		$disableClassName = '';
		$disabledLabel	  = '';

		$handlerClass = 'inactive';
		if ($this->canChange)
		{
			$handlerClass = 'sortable-handler hasTooltip';
		}
		
		if (!$this->enabled)
		{
			$disabledLabel    = JText::_('PLG_JDOM_ORDERING_DISABLED');
			$disableClassName = 'inactive tip-top';	
		}
		
		$htmlIcon = JDom::_('html.icon', array(
			'icon' => 'icomoon-menu'
		));
		
		
		$html .= JDom::_('html.fly', array(
			'markup' => 'span',
			'domClass' => $handlerClass . ' ' . $disableClassName,
			'selectors' => array(
				'title' => $disabledLabel
				
			),
			
			'dataValue' => $htmlIcon
		
		));
		
		if ($this->enabled)
		{			
			//TEXT INPUT
			$html .= $this->buildInput($this->showInput);
		}
		
		return $html;
	}
	
}