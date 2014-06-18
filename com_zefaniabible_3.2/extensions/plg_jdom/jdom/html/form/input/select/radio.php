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


class JDomHtmlFormInputSelectRadio extends JDomHtmlFormInputSelect
{
	protected $direction;
	protected $colorKey;
	protected $iconKey;
	protected $viewKey = 'viewType';
	protected $viewType;
	
//	var $assetName = 'bootstrap'; //Dynamic in __construct
	var $attachJs = array(
		'radio.js'
	);

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 * 	@list		: Possibles values list (array of objects)
	 * 	@listKey	: ID key name of the list
	 * 	@labelKey	: Caption key name of the list
	 * 	@direction	: 'horizontal' or 'vertical'  (default: horizontal)
	 * 	@domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('direction'	, null, $args, 'horizontal');

		$this->arg('colorKey', null, $args, 'color');
		$this->arg('iconKey', null, $args, 'icon');
		$this->arg('viewType', null, $args);

		if ($this->useFramework('bootstrap'))
			$this->assetName = 'bootstrap';
	}

	function build()
	{

		//$this->addStyle('float', 'left');

		$html = '';
		$html .= '<fieldset id="<%DOM_ID%>" class="radio btn-group" '
			.	'style="border:0 none;">';

		$i = 0;
		foreach($this->list as $item)
		{
			$id = "<%DOM_ID%>_$i";
			$html .= $this->buildRadio((object)$item, $id);

			if ($this->direction == 'vertical')
				$html .= BR;
			$i++;
		}

		$html .= '</fieldset>';

		return $html;
	}

	function buildRadio($item, $id)
	{
		$listKey = $this->listKey;
		$labelKey = $this->labelKey;
		$colorKey = $this->colorKey;
		$iconKey = $this->iconKey;
		$viewKey = $this->viewKey;

		if (!$viewType = $this->viewType)
		{
			if (isset($item->$viewKey))
				$viewType = $item->$viewKey;
			else
				$viewType = 'both';
			
		}
		
		if (!in_array($viewType, array('icon', 'both')))
			$iconKey = null;
		
		$text = $tooltipText = null;
		if (in_array($viewType, array('text', 'both')))
			$text = $this->parseKeys($item, $labelKey);
		else
			$tooltipText = $this->parseKeys($item, $labelKey);
				
		
		$checked = ($item->$listKey == $this->dataValue);

		$js = '';

		$options = array();
		if ($colorKey && isset($item->$colorKey))
			$options['color'] = $item->$colorKey;


		$html =	'<input type="radio" name="<%INPUT_NAME%>"'
			.	' id="' . $id . '"'
			.	' value="' . $this->parseKeys($item, $listKey) . '"'
			.	' rel="' . htmlspecialchars(json_encode($options)) . '"'
			.	'<%CLASS%>'
			.	' ' . $js
			.	($checked?' checked="checked"':'');

		$html	.=	'/>'.LN;
		
		
		$htmlIcon = '';
		if ($iconKey)
		{
			$htmlIcon .= JDom::_('html.icon', array(
				'icon' => $this->parseKeys($item, $iconKey),
				'styles' => array(
					'margin-right' => '5px'
				)
			));
		}
		
		$html .= JDom::_('html.form.label', array(
			'domId' => $id,
			'label' => $htmlIcon . $text,
			'domClass' => 'btn',
			'tooltip' => ($tooltipText?true:false),
			'title' => $tooltipText
		)) .LN;
	
		
		return $html;
	}
}