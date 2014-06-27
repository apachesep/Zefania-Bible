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


class JDomHtmlLink extends JDomHtml
{
	var $fallback = 'default';	//Used for default

	protected $href;
	protected $task;
	protected $num;
	protected $link_js;
	protected $enabled;
	protected $content;
	protected $target; // Can be also 'modal'
	protected $handler; // Can be 'iframe'
	protected $alertConfirm;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *	@href		: Link
	 *	@task		: Task
	 *	@num		: Row num (for grid tasks)
	 *	@link_js	: Javascript for the link
	 *	@content	: Content of the link
	 *	@link_title	: Title of the link (default : @content)
	 *  @tooltip	: Create a tooltip on this link
	 *	@target		: Target of the link  (added to natives targets : 'modal')
	 *	@handler	: Modal handler type (ex:iframe)
	 *	@domClass	: CSS class of the link
	 *	@modal_width	: Modal width
	 *	@modal_height	: Modal height
	 *  @alertConfirm	: will prompt an alert box message to confirm 
	 *  @enabled	: Default true. Can disable the link
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('href'			, null, $args);
		$this->arg('task'			, null, $args);
		$this->arg('num'			, null, $args);
		$this->arg('link_js'		, null, $args); //Deprecated
		$this->arg('content'		, null, $args);
		$this->arg('link_title'		, null, $args);
		$this->arg('enabled'		, null, $args, true);
		$this->arg('tooltip'		, null, $args);
		$this->arg('target'			, null, $args);
		$this->arg('handler'		, null, $args, 'iframe');
		$this->arg('domClass'		, null, $args);
		$this->arg('alertConfirm'	, null, $args);
		
	}

	function buildLink()
	{
		
		if ($this->enabled)
		{
			$this->createRoute();
				
			if ($this->target == 'modal')
				$this->modalLink();
	
			if ($this->task || isset($this->submit))
				$this->jsCommand();
			
			$this->addStyle('cursor', 'pointer');
			
		}

		if ($this->tooltip)
			$this->addClass('hasTooltip hasTip');
		
		$html = "<a<%ID%><%CLASS%><%STYLE%><%TITLE%><%HREF%><%JS%><%TARGET%><%SELECTORS%>>"
			.	$this->content
			.	"</a>";

		return $html;
	}


	

	function getTaskExec($ctrl = false)
	{

		//Get the task behind the controller alias (since Joomla 2.5)
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

	function modalLink()
	{
		JHTML::_('behavior.modal');
		
		$this->addClass('modal');
		
		$rel = "{";
		$rel.= "handler: '" . ($this->handler?$this->handler:'') . "'";

		if ($this->modalWidth && $this->modalHeight)
		{
			$w = (int)$this->modalWidth;
			$h = (int)$this->modalHeight;
			
			$rel .=	", size: {x: " . ($w?$w:"null")
						. 	", y: " . ($h?$h:"null")
						. "}";
		}
		if ($this->modalScrolling)
		{
			$options = array("auto", "no", "yes");
			if (!in_array(strtolower($this->modalScrolling), $options))
				$this->modalScrolling = "auto";

			$rel .=	", iframeOptions: {scrolling:'" . $this->modalScrolling
						. "'}";
		}
		if ($this->modalOnclose)
		{
			$rel .=	", onClose: function() {" . $this->modalOnclose . "}";
		}
		
		$rel.=	"}";
		
		$this->addSelector('rel', $rel);
	}

	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'TITLE' 	=> ($this->link_title?" title=\"".htmlspecialchars($this->link_title)."\"":""),
			'HREF' 		=> ($this->href?" href=\"" .htmlspecialchars($this->href) . "\"":""),
			'JS' 		=> ($this->link_js?" onclick=\"" .htmlspecialchars($this->link_js) . "\"":""),
			'TARGET' 	=> ($this->target?" target='" . $this->target . "'":""),
			'SELECTORS'	=> $this->buildSelectors(),
			'CLASS'		=> $this->buildDomClass(),
			'STYLE'		=> $this->buildDomStyles(),
			'ID'		=> (isset($this->domId)?" id=\"" . $this->domId . "\"":'')
		), $vars));
	}
	
}