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


class JDomHtmlGridOrdering extends JDomHtmlGrid
{
	var $fallback = 'sortable';
	
	protected $listOrder;
	protected $listDirn;
	
	protected $showInput;
	protected $enabled;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@num		: Num position in list
	 *
	 *	@list		: Ordering list brothers
	 *	@ctrl		: Current controller for task
	 *	@pagination : Pagination object (for icons Up/Down possibilities)
	 *	@groupBy	: GroupBy key
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('dataKey', null, $args, 'ordering');
		$this->arg('enabled', null, $args, -1);

		$this->arg('listOrder'	, null, $args);
		$this->arg('listDirn'	, null, $args);
		
		if ($this->enabled === -1) //NOT SET
			$this->enabled = ((!isset($this->listOrder)) || (($this->listOrder == 'a.' . $this->dataKey) && ($this->listDirn != 'desc')));
		
	
		$this->canChange = $this->access();
		if (!$this->canChange)
			$this->enabled = false;
				
	}

	function buildInput($show = false)
	{
		
					

		$html = JDom::_('html.form.input', array(
			'domName' => 'order[]',
			'dataValue' => $this->dataValue,
			'size' => 5,
			'styles' => array(
				'text-align' => 'center',
				'display' => ($show?'':'none'),
			
			),
			'selectors' => ($this->enabled?null:array(
				'disabled' => 'disabled'
			)),
			
			'domClass' => 'width-20 text-area-order'
		));

		
		return $html;
	}
}