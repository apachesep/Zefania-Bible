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


class JDomHtmlFormInputSearch extends JDomHtmlFormInput
{
	var $assetName = 'joomla';

	var $attachJs = array(
		'joomla.js'
	);
	
	var $size;
	var $label;

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
	 * 	@size		: Input Size
	 *  @label		: Filter label
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('size'		, null, $args);
		$this->arg('label'		, null, $args);
	}

	function build()
	{

		$htmlInput = JDom::_('html.form.input', array_merge($this->options, array(
			'placeholder' => $this->label,
			'title' => $this->label,
		)));
		
		
		$html = '<div class=" form-search btn-group">';
		
			//Button group
			$html .= '<div class="input-append">';
	
				$html .= $htmlInput;
				
				//Search Button
				$html .= JDom::_('html.link.button.icon', array(
					'icon' => 'search',
					'link_title' => JText::_('JSEARCH_FILTER_SUBMIT'),
					'submitEventName' => 'onclick',
				));
						
			$html .= '</div>';

			//Clear Button
			$html .= '<div class="btn-group">';
						
				$html .= JDom::_('html.link.button.icon', array(
					'icon' => 'remove',
					'link_title' => JText::_('JSEARCH_FILTER_CLEAR'),
					'link_js' => 'Joomla.resetFilters();',
					'domClass' => ''
				));
			
			$html .= '</div>';
		
		$html .= '</div>';

		return $html;
	}
	

}