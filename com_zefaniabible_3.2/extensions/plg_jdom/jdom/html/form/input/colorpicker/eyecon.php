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


class JDomHtmlFormInputColorpickerEyecon extends JDomHtmlFormInputColorpicker
{
	var $assetName = 'colorpicker-eyecon';

	var $attachJs = array(
		'colorpicker.js'
	);

	var $attachCss = array(
		'colorpicker.css',
	);

	function __construct($args)
	{
		parent::__construct($args);
	}

	function build()
	{
		$html = $this->buildControl();
		return $html;
	}

	function buildJs()
	{
		$id = $this->getInputId();

		$js = 'jQuery("#' . $id . '-btn").ColorPicker({' .

				'onSubmit: function(hsb, hex, rgb, el) {
					jQuery("#' . $id . '").val(hex);
					jQuery(el).ColorPickerHide();
					jQuery("#' . $id . '-pick").css("backgroundColor", "#" + hex)
				},
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(200);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(200);
					return false;
				},
				onBeforeShow: function () {
					var hex = "#";
					var input = jQuery("#' . $id . '");
					
					if (typeof(input.val()) != "undefined")
						hex += input.val();
					else
					{
						//from CSS property of the color preview
						var color = jQuery("#' . $id . '-pick").css("backgroundColor");
					
						var rgb = color.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
						hex = "#" + 
						  ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
						  ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
						  ("0" + parseInt(rgb[3],10).toString(16)).slice(-2);
					}
					 
					jQuery(this).ColorPickerSetColor(hex);
				}' .
		'});';

		$this->addScriptInline($js, true);
	}


}