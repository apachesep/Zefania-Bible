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


class JDomHtmlIconGlyphicon extends JDomHtmlIcon
{
	var $assetName = 'glyphicon';

	var $attachCss = array(
		'icons.css',
	);
		
	/*
	 * Constuctor
	 */
	 
	function __construct($args)
	{
		parent::__construct($args);
	}
	
	//Get glyphicons icons
	public function getIcons()
	{
		$default = array(
			'glass','music','search','envelope','heart','star','star-empty','user'
			,'film','th-large','th','th-list','ok','remove','zoom-in','zoom-out'
			,'off','signal','cog','trash','home','file','time','road','download-alt'
			,'download','upload','inbox','play-circle','repeat','refresh','list-alt'
			,'lock','flag','headphones','volume-off','volume-down','volume-up'
			,'qrcode','barcode','tag','tags','book','bookmark','print','camera'
			,'font','bold','italic','text-height','text-width','align-left'
			,'align-center','align-right','align-justify','list','indent-left'
			,'indent-right','facetime-video','picture','pencil','map-marker'
			,'adjust','tint','edit','share','check','move','step-backward'
			,'fast-backward','backward','play','pause','stop','forward','fast-forward'
			,'step-forward','eject','chevron-left','chevron-right','plus-sign'
			,'minus-sign','remove-sign','ok-sign','question-sign','info-sign'
			,'screenshot','remove-circle','ok-circle','ban-circle','arrow-left'
			,'arrow-right','arrow-up','arrow-down','share-alt','resize-full'
			,'resize-small','plus','minus','asterisk','exclamation-sign','gift'
			,'leaf','fire','eye-open','eye-close','warning-sign','plane'
			,'calendar','random','comment','magnet','chevron-up','chevron-down'
			,'retweet','shopping-cart','folder-close','folder-open','resize-vertical'
			,'resize-horizontal','hdd','bullhorn','bell','certificate','thumbs-up'
			,'thumbs-down','hand-right','hand-left','hand-up','hand-down'
			,'circle-arrow-right','circle-arrow-left','circle-arrow-up'
			,'circle-arrow-down','globe','wrench','tasks','filter','briefcase'
			,'fullscreen'		
		);
		
		return $default;
		
	}
}