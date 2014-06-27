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


class JDomHtmlGridOrderingLegacy extends JDomHtmlGridOrdering
{
	
	protected $list;
	protected $ctrl;
	protected $pagination;
	protected $groupBy;
	

	protected $imagesDir;

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

		$this->arg('list'		, null, $args);
		$this->arg('ctrl'		, null, $args);
		$this->arg('pagination'	, null, $args);
		$this->arg('groupBy'	, null, $args);

		
		
		$this->imagesDir = $this->pathToUrl($this->systemImagesDir(), true);
				
	}

	function build()
	{
		$html = '';

		$disabled = $this->enabled?'':'disabled="disabled"';
		$groupBy = $this->groupBy;
		$num = $this->num;
		$items = $this->list;

		$row = $this->dataObject;

		if ($this->pagination)
		{
			$canUp = $canDown = $this->enabled;
			if ($groupBy && $this->enabled)
			{
				$canUp = ($row->$groupBy == @$items[$num-1]->$groupBy);
				$canDown = ($row->$groupBy == @$items[$num+1]->$groupBy);
			}
	
		//UP ICON
			$html .= "<span>" . $this->orderUpIcon( $num, $canUp,'orderup', 'Move Up', $this->enabled ) . "</span>" .LN;
	
		//DOWN ICON
			$html .= "<span>" . $this->orderDownIcon( $num, count($items), $canDown, 'orderdown', 'Move Down', $this->enabled ) . "</span>" .LN;
			
		}


	//TEXT INPUT
		$html .= $this->buildInput(true);

		return $html;
	}


	function systemImage($name, $width = 16, $height = 16, $enabled = true)
	{
		$style = "";
		$dir = $this->imagesDir;

		if (!$enabled && $this->jVersion('1.6'))
			$style .= 'background-position:0px ' . (int)$height . 'px;';

		$domImage = '<div style="background-image:url(' . $dir . '/' . $name . '); '
							.	'width:' . (int)$width . 'px; '
							.	'height:' . (int)$height . 'px; '
							.	'display:inline-block;' . $style .'">'
					.'</div>';

		return $domImage;
	}

	function orderUpIcon($i, $condition = true, $task = 'orderup', $alt = 'Move Up', $enabled = true)
	{

		if (isset($this->ctrl))
			$task = $this->ctrl . '.' . $task;

		$html = '&nbsp;';
		if (($i > 0 || ($i + $this->pagination->limitstart > 0)) && $condition)
		{
			if($enabled) {
				$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'. htmlentities($alt) .'">';
				$html .= $this->systemImage('uparrow.png', 12, 12, $enabled);
				$html	.= '</a>';
			} else {
				$html = $this->systemImage(($this->jVersion('1.6')?'uparrow.png':'uparrow0.png'), 12, 12, $enabled);
			}
		}

		return $html;
	}


	function orderDownIcon($i, $n, $condition = true, $task = 'orderdown', $alt = 'Move Down', $enabled = true)
	{
		if (isset($this->ctrl))
			$task = $this->ctrl . '.' . $task;

		$html = '&nbsp;';
		if (($i < $n -1 || $i + $this->pagination->limitstart < $this->pagination->total - 1) && $condition)
		{
			if($enabled) {
				$html	= '<a href="#reorder" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'. htmlentities($alt) .'">';
				$html .= $this->systemImage('downarrow.png', 12, 12, $enabled);

				$html	.= '</a>';
			} else {
				$html = $this->systemImage(($this->jVersion('1.6')?'downarrow.png':'downarrow0.png'), 12, 12, $enabled);
			}
		}

		return $html;
	}



}