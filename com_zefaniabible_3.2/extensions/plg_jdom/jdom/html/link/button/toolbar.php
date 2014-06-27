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


class JDomHtmlLinkButtonToolbar extends JDomHtmlLinkButton
{
	var $fallback = 'standard';

	protected $item;
	protected $name;
	protected $text;
	protected $task;
	protected $message;
	protected $checkList;
	protected $align;
	protected $ui;
	protected $display;
	
	protected $alertConfirm;

	/*
	 * Constuctor
	 * 	@namespace 	: Requested class
	 *  @options	: Parameters
	 *  //@bar		: Joomla Toolbar
	 *
	 *
	 *  @item		: Joomla Toolbar Item arguments (array)  (Overwrite $bar parameter)
	 *	@align		: Item alignement  (float)
	 *  @alertConfirm : Alert a Confirm box
	 *  @checkList	: Check in list
	 *  @ui			: Rendering type (default : bootstrap)
	 *  @display	: Display of the button (all/icon/text)
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('item'	, null, $args);
		$this->arg('checkList'	, null, $args);
		$this->arg('align'	, null, $args);
		$this->arg('ui'	, null, $args, 'bootstrap');
		$this->arg('display', null, $args, 'all');

	}

	function getIconFromTask()
	{
		if ($this->icon)
			return $this->icon;
		
		$icon = $this->name;
		
		if ($this->ui == 'bootstrap')
		{
			switch($icon)
			{
				case 'preview': return 'eye';
			}	
		}
		return $icon;
	}
	
	function setStylesFromTask()
	{
		//Define the button styles (color, width)
		if ($this->ui == 'bootstrap')
		{
			switch($this->name)
			{
				case 'new':
				case 'apply':
					$this->addClass('btn-success');
					$this->addClass('input-medium');
					break;
			}			
		}
		
		
	}
	
	function build()
	{
		$this->setStylesFromTask();
		
		
		if ($this->ui == 'bootstrap')
		{
			$this->addClass('btn btn-small');
		}
		else
		{		
			$this->addClass('button');
		}
		
		if ($this->href || $this->route)
		{
			//Special treatment when the item is a direct link
			$this->content = $this->buildContent();
			
			//Handle the click in a 'a' markup. li transfers its styles to the link markup
			$html =		'<li id="<%LI_ID%>">'.LN
					.	$this->buildLink()
					.	'</li>' .LN;
	
			return $html;
		}
		
		// Handle the click in the li element
		$html =		'<li<%CLASS%> style="<%LI_STYLE%>" id="<%LI_ID%>"  onclick="<%COMMAND%>">'.LN
				.	$this->buildContent()
				.	'</li>' .LN;

		return $html;
	}
	
	function buildContent()
	{
		$html =	'<div class="<%CONTENT_CLASS%>" style="cursor:pointer">'.LN
			.	$this->buildIconText() .LN
			.	'</div>';
		
		return $html;
	}
	
	function buildIconText()
	{
		$icon = $this->getIconFromTask();
		
		$html = '';
			
		if ($this->ui == 'bootstrap')
		{
			// Icon
			if (in_array($this->display, array('all', 'icon')))
				$html .= '<i class="icon-' . $icon . ($this->iconLibrary?' ' . $this->iconLibrary :'') . '"></i>'.LN;

			// Caption
			if (in_array($this->display, array('all', 'text')))
				$html .= '<span class="text" style="white-space:nowrap"><%TEXT%></span>'.LN;
		}
		else
		{
			// Icon
			if (in_array($this->display, array('all', 'icon')))
				$html .=	'<span class="' . 'icon-16 ' . $icon . '"></span>' .LN;
			
			// Caption
			if (in_array($this->display, array('all', 'text')))
				$html .= '<span class="text" style="white-space:nowrap"><%TEXT%></span>' .LN;
			
		}
		return $html;		
	}
	
	
	protected function parseVars($vars)
	{
		$alignStyle = null;
				
		switch($this->align)
		{
			case 'left':
			case 'right':
				$alignStyle = "float: " . $this->align . ";";
				break;

			case 'center':
				$alignStyle = "display: inline-block;";
				break;
		}

		$this->jsCommand();
		
		$contentClass = 'task';
		if ($this->ui == 'bootstrap')
			$contentClass = '';
		
		return parent::parseVars(array_merge(array(
			'LI_STYLE' 		=> "list-style:none; " . $alignStyle,
			'LI_ID' 		=> 'toolbar-' . $this->task,
			'CONTENT_CLASS' 	=> $contentClass,
			'COMMAND' 		=> ($this->link_js?htmlspecialchars($this->link_js):""),
			'TEXT' 			=> $this->JText($this->text),
			'CLASS'		=> $this->buildDomClass(),
		), $vars));
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