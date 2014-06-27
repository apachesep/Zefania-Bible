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


class JDomFrameworkBootstrapCore extends JDomFrameworkBootstrap
{	
	protected $loadCss;
	protected $loadJs;
	
	protected $cssRtl;
	
	
	protected $hostedSource = '';

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}
	
	function build()
	{
		//Bootstrap should not be used
		if (!$this->useFramework('bootstrap'))
			return;
		
		//Bootstrap is already in the core since Joomla 3.0. And already loaded.
		if ($this->jVersion('3.0'))
			return;
		
		$jsFile = 'bootstrap.min.js';
		$assetFile = $this->assetFilePath('js', $jsFile);
		
		if (file_exists($assetFile))
			$this->attachJs[] = $jsFile;
		
		//Fallback
		else
			$this->addScript($this->hostedSource);
	}
	
	function buildCss()
	{
		//Bootstrap CSS is already in the core since Joomla 3.2
		if ($this->jVersion('3.2'))
			return;

		//Bootstrap should not be used
		if (!$this->useFramework('bootstrap'))
			return;
			
		$this->attachCss[] = 'bootstrap.min.css';
		$this->attachCss[] = 'bootstrap-responsive.min.css';
		$this->attachCss[] = 'bootstrap-extended.css';
		
		//Some fixes and compatibility
		$this->attachCss[] = 'bootstrap-legacy.css';
	}
}