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


class JDomHtmlLinkTooltip extends JDomHtmlLink
{
	var $allowWrapLink = false;	//To avoid infinite recursivity
	
	var $rel;
	var $contents;
	var $raw;

	var $animation;
	var $placement;
	var $selector;
	var $trigger;
	var $delay;
	var $container;
	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *	@href		: Link
	 *	@link_js	: Javascript for the link
	 *	@content	: Content of the link
	 *	@domClass	: CSS class of the link
	 *
	 *	@rel		: The word used to find tooltips (default:tooltip)
	 *	@title		: The content of the tooltip message. (Do not confuse with 'content')
	 *	@raw		: Allow raw html output
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		
		$this->arg('rel', null, $args, 'tooltip');
		$this->arg('raw', null, $args, false);
		
		//Bootstrap parameters
		$this->arg('animation', null, $args, true);
		$this->arg('placement', null, $args, 'top');
		$this->arg('selector', null, $args);
		$this->arg('trigger', null, $args, 'hover');
		$this->arg('delay', null, $args, 0);
		$this->arg('container', null, $args);
		
		$this->arg('contents', null, $args);
		
		
	}

	function build()
	{
		if (!$this->href)
			$this->href = '#';

		JDom::_('framework.bootstrap');

		if ($this->useFramework('bootstrap'))
		{
			$this->addSelector('rel', $this->rel);
			$this->link_title = $this->contents;
		}
		else
		{
			//Legacy MooTools tooltips
			$this->addClass('hasTip');
			
			$this->addSelector('rel', $this->contents);
		}

		$html = '<a<%ID%><%CLASS%><%STYLE%><%TITLE%><%HREF%><%JS%><%TARGET%><%SELECTORS%>>'
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
				
				
		$options = json_encode($options);
		
		$js = '(function($){
				$("[rel=' . $this->rel . ']").tooltip(' . $options . ');
			})(jQuery)';
		
		return $this->addScriptInLine($js, true);
	}



}