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


class JDomHtmlFormInputColorpicker extends JDomHtmlFormInput
{
	var $fallback = 'eyecon';		//Used for default

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 * 	@domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}

	function buildControl()
	{
		$id = $this->getInputId();

		$html = '';

		//Create the input
		$dom = JDom::getInstance('html.form.input.text', array_merge($this->options, array(
		)));

		$dom->addClass('input-mini');
		$htmlInput = $dom->output();
		
		//Create the color
		$htmlColor = JDom::_('html.fly.color', array_merge($this->options, array(
			'width' => 12,
			'height' => 12,
			'selectors' => array(
				'id' => $id . '-pick'
			)
		)));
		
		//Create the icon
		$htmlIcon = JDom::_('html.icon', array(
			'icon' => 'glyphicon-tint',
		));

		//Create the button (suffix -btn is to trigger the calendar)
		$htmlButton = JDom::_('html.link.button', array(
			'content' => $htmlIcon,
			'domClass' => 'btn',
			'domId' => $id . '-btn',
		));

		$html = '';

		//Render the control
		if ($this->hidden)
			$html .= $htmlInput .LN; //Place the hidden input out of the control

			
		$html .= '<div class="btn-group">' .LN;					
		$html .= '<div class="input-prepend input-append">' .LN;
			
		if (!$this->hidden)
		{
			//Prepend
			$html .= '<span class="add-on">#</span>';
			
			//Input mini
			$html .= $htmlInput .LN;	
		}
			
		//Append
		$html .= '<span class="add-on">' . $htmlColor . '</span>' .LN;
		$html .= $htmlButton .LN;
		
		//Close the control		
		$html .= '</div>' .LN;
		$html .= '</div>' .LN;
		

		return $html;

	}

}