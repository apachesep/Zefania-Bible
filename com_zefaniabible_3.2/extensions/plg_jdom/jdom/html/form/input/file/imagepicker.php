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


class JDomHtmlFormInputFileImagepicker extends JDomHtmlFormInputFile
{
	var $assetName = 'imagepicker';

	var $attachJs = array(
		'insert.js'
	);

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *	@indirect	: Indirect File access
	 *	@root		: Default folder (alias : ex [DIR_TABLE_FIELD]) -> Need a parser (Cook helper)
	 *	@width		: Thumb width
	 *	@height		: Thumb height
	 *	@attrs		: File attributes ('crop', 'fit', 'center', 'quality')
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}

	function build()
	{
		$html = '';
		
		//Create the preview icon
		$htmlIconPreview = JDom::_('html.icon', array(
			'icon' => 'eye',
		));
		
		//Create the preview	
		$pickerStyle = "";
		if ($this->thumb)
			$pickerStyle = 'border:dashed 3px #ccc; padding:5px; margin:5px;display:inline-block';

		$isNew = (empty($this->dataValue));


		$htmlPreview = "<div id='" . $this->getInputId('preview') . "' style='" . $pickerStyle . "'>";
		$htmlPreview .= JDom::_("html.fly.file", $this->options);
		$htmlPreview .= "</div>";
		$htmlPreview .= '<div class="clearfix"></div>';

		$removeList = $this->removeList();
	
		//Hidden 
		$htmlHiddenInput = JDom::_("html.form.input.hidden", $this->options);
		
		
		//Current value is important for the removing features features ()
		$htmlHiddenCurrent = JDom::_('html.form.input.hidden', array(
			'dataValue' => $this->dataValue,
			'dataKey' => $this->getInputName('current'),
			'formControl' => $this->formControl,
		));
		
		$idRemove = $this->getInputId('remove');
		$nameRemove = $this->getInputName('remove');
		
		//Store and send the 'remove' value
		$htmlHiddenRemove = JDom::_('html.form.input.hidden', array(
			'dataValue' => '',
			'domId' => $idRemove,
			'domName' => $nameRemove,
			'formControl' => $this->formControl,
		));

	
		$html = '';
		
		$html .= $htmlPreview;
		
		//Hidden inputs in top of the control
		$html .= $htmlHiddenCurrent .LN;
		$html .= $htmlHiddenRemove .LN;
		$html .= $htmlHiddenInput .LN;
		
		
		$html .= '<div class="input-prepend input-append">' .LN;	
		$html .= '<div class="btn-group">';
		
		//Prepend
		$html .= '<span class="add-on">'
			.	$htmlIconPreview
			.	'</span>';
		
		$htmlIconRemove = JDom::_('html.icon', array('icon' => 'delete',));
		
		$idRemoveBtn = $this->getInputId('deletebtn');

		// Create the upload button
		$htmlIconBrowse = JDom::_('html.icon', array(
			'icon' => 'picture',
		));

		//Create the button to trigger the input
		$htmlButtonBrowse = JDom::_('html.link.button', array(
			'content' => $htmlIconBrowse,
			'domClass' => 'btn',
			'route_' => array(
				'option' => 'com_media',
				'view' => 'images',
				'layout' => 'default',
				'tmpl' => 'component',
				'e_name' => $this->getInputId()
			),
			'target' => 'modal',
			'handler' => 'iframe',
								
			'href' => 'index.php?option=com_media&view=images&layout=default&tmpl=component&e_name='. $this->getInputId(),
								
		));
		
	

//REMOVE ACTIONS		
		if (!$isNew && count($removeList))
		{
			$html .= '<button class="btn dropdown-toggle" id="' . $idRemoveBtn . '" data-toggle="dropdown">';
			$html .= $htmlIconRemove;
			$html .= '</button>' .LN;
		
			$html .= '<ul class="dropdown-menu">' .LN;
			
			foreach($removeList as $item)
			{
				$icon = $item['icon'];
				$itemIcon = JDom::_('html.icon', array(
					'icon' => $icon,
				));
				
				$htmlLink = '<div class="pull-right">' 
					.  	$itemIcon
					. 	'</div>'
					.	$item['text'];
				
				$html .= '<li>' .LN;
				
				$jsRemove = 'jQuery(\'input[id=' . $idRemove . ']\').val(\'' . $item['value'] . '\');';
				$jsRemove .= 'jQuery(\'button[id=' . $idRemoveBtn . '] i\').attr(\'class\', \'icon-' . $icon . '\');';
				
				$html .= '<a onclick="' . $jsRemove . '">' 
					. 	$htmlLink
					. 	'</a>' .LN;
					
				$html .= '</li>' .LN;
			}
			$html .= '</ul>' .LN;
			
		}

		$html .= $htmlButtonBrowse;
	
	
		//Close the control		
		$html .= '</div>' .LN;
		$html .= '</div>' .LN;
		
								
		return $html;
	}

	function buildJS()
	{
		$size = "";
		$attrs = "";

		$w = (int)$this->width;
		$h = (int)$this->height;

		if ($w || $h)
			$size = $w ."x". $h;

		if ($this->attrs)
			$attrs .= implode(",", $this->attrs);

		$indirectUrl = JURI::base(true) . "/index.php?option=" . $this->getExtension() . "&task=file&path=[IMAGES]";
		$id = $this->getInputId();

		$script = "jInsertFields['" . $id . "'] = {"
				.	"'url': \"" . $indirectUrl . "\","
				.	"'size': \"" . $size . "\","
				.	"'attrs': \"" . $attrs . "\","
				.	"'preview': " . (int)$this->thumb

				.	"}";

		$this->addScriptInline($script);
	}

}