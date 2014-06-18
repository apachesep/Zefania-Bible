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


class JDomHtmlHook extends JDomHtml
{
	var $fallback = 'node';

	var $assetName = 'ajax';

	var $attachJs = array(
		'ajax.js'
	);

	var $attachCss = array(
		'ajax.css'
	);



	protected $context;		//REQUIRED
	protected $domId;
	protected $data;
	protected $onReady;
	
	protected $method;
	protected $format;
	protected $states;
	
	protected $hookPlugin;
	
	

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
	 * 	@ajaxContext: Ajax context (extension.view.layout.render) extension without 'com_'
	 * 	@ajaxWrapper: Ajax Dom div wich will be filled with the result
	 * 	@ajaxVars	: Extends of override the ajax query
	 *  @onReady	: Javascript to execute when Ajax succeed and DOM is ready
	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);
		$this->arg('context'	, null, $args, '');

		$this->context = explode(".", $this->context);

		$this->arg('domId'	, null, $args, (count($this->context) >= 4?"_ajax_" . $this->context[1] . "_" . $this->context[3]:rand(1111111111, 8888888888)));
		
$this->arg('onReady'	, null, $args);
	
//HERE		
		$this->arg('data'	, null, $args, array());
		$this->arg('method'	, null, $args, 'GET');
		
		
		$this->arg('format'	, null, $args, 'HTML');
		$this->arg('states'	, null, $args, array());
		$this->arg('hookPlugin'	, null, $args);

	}

	function buildJs()
	{
		$jsPluginName = str_replace('.', '_', $this->hookPlugin);
		
		
		
		// Attach all plugin files
		$parts = explode('.', $this->hookPlugin);
		$base = array();
		for($i = 0 ; $i < count($parts); $i++)
		{
			$base[] = $parts[$i];
			$jsFile = 'plugins/' . implode('/', $base) . '.js';
			$this->attachJs[] = $jsFile;
		}
		

		$namespace = implode(".", $this->context);
		
		
		$params = array(
			"namespace" => $namespace,
		);
				
		
		$params['vars'] = $this->data;

		if (count($this->states))
			$params['vars']['__states'] = $this->states;
		
		
		// Only if different of default (GET)
		if ($this->method != 'GET')
			$params['method'] = $this->method;

		// Only if different of default (GET)
		if ($jsPluginName)
			$params['plugin'] = $jsPluginName;



		// Only if different of default (html) - result is deprecated.
		if ($this->format != 'html')
			$params['result'] = $params['format'] = $this->format;		


	// Possible fallbacks
	// TODO : Use them directly in JS in the plugin
	//	$params['success'] = 'function(){alert("OK");}';
	//	$params['error'] = 'function(){}';
	//	$params['loading'] = 'function(){}';


		
		$script = 'var hook = jQuery("#' . $this->domId . '").jdomAjax(' . json_encode($params) . ');';
		
//DEBUG		
//		$script .= ' alert(hook.url);';
		
		$this->addScriptInline($script, true);

		
		
		if ($this->onReady)
			$this->addScriptInline($this->onReady, true);

	}



}