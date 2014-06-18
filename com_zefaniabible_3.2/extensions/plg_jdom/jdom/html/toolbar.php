<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.6.4
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

class JDomHtmlToolbar extends JDomHtml
{
	var $assetName = 'toolbar';

	var $attachJs = array(
		'toolbar.js'
	);

	var $attachCss = array(
		'toolbar.css'
	);

	var $bar;
	var $items;
	
	protected $ui;
	protected $display;
	protected $list;

	/*
	 * Constuctor
	 * 	@namespace 	: Requested class
	 *  @options	: Parameters
	 *
	 *	@bar		: Joomla Toolbar
	 *  @align		: Toolbar alignement  (float)
	 *  @ui			: Rendering type (default : bootstrap)
	 *  @display	: Display of the button (all/icon/text)
	 *  @list		: List of items (grid)
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('bar'	, null, $args);
		$this->arg('align'	, null, $args, 'left');
		$this->arg('ui'	, null, $args, 'bootstrap');
		$this->arg('display', null, $args, 'all');
		$this->arg('list', null, $args);			
			
		//Convert to lowercase because class name has changed since J!3.0
		if (is_object($this->bar) && strtolower(get_class($this->bar)) == 'jtoolbar')
			$items = $this->bar->getItems();
		else
			$items = $this->bar;

		$this->items = $items;
		
		//Require the Joomla native javascript class
		JHtml::_('behavior.framework');
	}


	function build()
	{
		if (!$this->items || !count($this->items))
			return '';

		$this->domClass .= ' toolbar-list';

		$html =	'<div class="cktoolbar"'
			.	$this->buildDomClass()
			.	$this->buildSelectors()
			.	'>' .LN
			.	$this->indent($this->buildItems(), 1)
			.	'</div>';

		return $html;
	}

	function buildItems()
	{
		$htmlItems = '';


		if ($this->align == 'right')
			$this->items = array_reverse($this->items);


		foreach($this->items as $item)
		{
			if (!count($item))
				continue;

			$itemNameSpace = 'html.link.button.toolbar.' . strtolower($item[0]);
			$htmlItems .= JDom::_($itemNameSpace, array(
				'item' => $item,
				'checkList' => ($this->list != null),
				'ui' => $this->ui,
				'display' => $this->display
			)) .LN;
		}

		$html = '<ul<%STYLE%><%CLASS%>>'
			.		$htmlItems
			.	'</ul>';

		$html .= '<br clear="all"/>';

		return $html;
	}


	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'STYLE' 		=> ($this->align == 'center'?' style="text-align:center;"':''),
			'CLASS'		=> $this->buildDomClass(),
		), $vars));
	}	
}
