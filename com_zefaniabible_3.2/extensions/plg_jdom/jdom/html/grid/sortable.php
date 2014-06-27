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


class JDomHtmlGridSortable extends JDomHtmlGrid
{	
	protected $enabled;
	protected $listOrder;
	protected $listDirn;
	protected $canChange;
	
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
	 * 	@tableId	: Table id to make sortable
	 * 	@formId		: Form id. (default: adminForm)
	 * 	@listOrder	: Current list ordering
	 * 	@listDirn	: Current list ordering direction
	 *  @nestedList	: True when the list is nested
	 * 	@proceedSaveOrderButton : Load the Ajax JS caller
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('listOrder', null, $args);
		$this->arg('listDirn', null, $args);
		
		//Override dataKey with a default
		$this->arg('dataKey', null, $args, 'ordering');
		$this->arg('enabled', null, $args, -1);
		
		if ($this->enabled === -1) //NOT SET
			$this->enabled = ((!isset($this->listOrder)) || (($this->listOrder == 'a.' . $this->dataKey) && ($this->listDirn != 'desc')));
			
	
		$this->canChange = $this->access();
		if (!$this->canChange)
			$this->enabled = false;
		
	}


	function build()
	{
		
		$html = '';
	
		$disableClassName = '';
		$disabledLabel	  = '';

		$handlerClass = 'inactive';
		if ($this->canChange)
		{
			$handlerClass = 'hasTooltip';
		}
		
		if (!$this->enabled)
		{
			$disabledLabel    = JText::_('JORDERINGDISABLED');
			$disableClassName = 'inactive tip-top';
			
		}
				
		
		$htmlIcon = JDom::_('html.icon', array(
			'icon' => ($this->jVersion('3.0')?'menu':'move')
		));
		
		
		$html .= JDom::_('html.fly', array(
			'markup' => 'span',
			'domClass' => 'sortable-handler ' . $handlerClass . ' ' . $disableClassName,
			'selectors' => array(
				'title' => $disabledLabel
				
			),
			
			'dataValue' => $htmlIcon
		
		));
		
		if ($this->enabled)
		{			
			$htmlInput = JDom::_('html.form.input', array(
				'domName' => 'order[]',
				'dataValue' => $this->dataValue,
				'size' => 5,
				'styles' => array(
					'display' => 'none',
				
				),
				'domClass' => 'width-20 text-area-order'
			
			));
		
			$html .= $htmlInput;
		}
		
		return $html;
	}
	
}