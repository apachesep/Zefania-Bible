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


class JDomHtmlFormInputAjaxLoad extends JDomHtmlFormInputAjax
{
	var $assetName = 'ajax';

	var $attachJs = array(
		'ajax.js'
	);

	var $attachCss = array(
		'ajax.css'
	);

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *	@ajaxContext: Ajax context (extension.view.layout.render) extension without 'com_'
	 * 	@ajaxWrapper: Ajax Dom div wich will be filled with the result
	 * 	@ajaxVars	: Extends of override the ajax query
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('ajaxToken'	, null, $args);
	}

	function build()
	{



		$html = "\n" . "<div id='" . $this->ajaxWrapper . "'"
			.	($this->required?" class='ajax-required'":"")
			.	"></div>";

		$html .= LN
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>';


		$html .= JDom::_('html.form.input.hidden', array_merge($this->options, array(
												'dataValue' => ($this->dataValue == 0?"":$this->dataValue),
												)));

		return $html;

	}

	function buildJs()
	{

		$vars= "{}";
		if ($this->ajaxVars)
			$vars = json_encode($this->ajaxVars);

		$script = 'jQuery("#' . $this->ajaxWrapper . '").jdomAjax({'
		//	"result":"JSON",		//TO COME
		//	"data":{},				//NOT USED HERE

		.	'"namespace":"' . implode(".", $this->ajaxContext) . '",
			"vars":' . $vars . '
		});';

		$this->addScriptInline($script, true);


		parent::buildJs();
	}
}