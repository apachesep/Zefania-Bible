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

class JDomHtmlLinkTooltipPopover extends JDomHtmlLinkTooltip
{

	var $title;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *	@href		: Link
	 *	@link_js			: Javascript for the link
	 *	@content	: Content of the link
	 *	@link_title		: Title of the link (default : @content)
	 *	@target		: Target of the link  (added to natives targets : 'modal')
	 *	@domClass	: CSS class of the link
	 *
	 * 
	 *  @title		: Title of the popover
	 *	
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('title', null, $args);
		
		
	}
	
	
	function build()
	{
		//Only for bootstrap
		if (!$this->useFramework('bootstrap'))
		{
			echo $this->error('Bootstrap is required');
			return;
		}
		
		if (!$this->href)
			$this->href = '#';

		JDom::_('framework.bootstrap');

		$this->addSelector('rel', $this->rel);
		
		$this->link_title = $this->title;

		$html = '<a<%ID%><%CLASS%><%STYLE%><%TITLE%><%HREF%><%JS%><%TARGET%><%SELECTORS%>'
			.	' data-title="' . $this->title . '"'
			.	' data-content="' . $this->contents . '"'
			.	'>'
			.	$this->content
			.	'</a>';


		return $html;
	}
	
	
	function buildJs()
	{
		//Only for bootstrap
		if (!$this->useFramework('bootstrap'))
			return;
		
		$options = array(
			'animation' => $this->animation,
			'placement' => $this->placement,
			'trigger' => $this->trigger,
		);
		
		if ($this->raw)
			$options['html'] = true;
		
		if ($this->container)
			$options['container'] = $this->container;
				
		if ($this->delay)
			$options['delay'] = $this->delay;
				
		if ($this->selector)
			$options['selector'] = $this->selector;

		if ($this->delay)
			$options['delay'] = $this->delay;
						
		$options = json_encode($options);
		
		$js = '(function($){
				$("[rel=' . $this->rel . ']").popover(' . $options . ');
			})(jQuery)';
		
		return $this->addScriptInLine($js, true);
	}


}