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


class JDomFrameworkJqueryUi extends JDomFrameworkJquery
{
	protected $hostedSource = 'http://code.jquery.com/ui/1.10.3/jquery-ui.js';
	protected $live = false;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *  @lib		: jQuery UI library to load
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('live', 		null, $args, false);

	}
	
	function build()
	{	
		//Requires jQuery
		JDom::_('framework.jquery');
	
		if ($this->live)
			$this->addScript($this->hostedSource);
		else
			$this->attachJs[] = 'jquery.ui.min.js';
	}

}