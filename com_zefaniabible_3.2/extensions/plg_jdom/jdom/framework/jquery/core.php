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


class JDomFrameworkJqueryCore extends JDomFrameworkJquery
{	
	protected $hostedSource = 'http://code.jquery.com/jquery.min.js';
	protected $live = false;
	
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
		$this->arg('live', 		null, $args, false);
	}
	
	function build()
	{
		//jQuery already in the core since Joomla 3.0. Load it with the native class.
		if ($this->jVersion('3.0'))
		{
			JHtml::_('jquery.framework', false);
			return;
		}

		if ($this->live)
			$this->addScript($this->hostedSource);
		else
			$this->attachJs[] = 'jquery.min.js';
		
				
		if (!$this->isAjax())
		{
			$jQueryNoConflict = 'jQuery.noConflict();';
			$this->addScriptInline($jQueryNoConflict);			
		}

	}

}