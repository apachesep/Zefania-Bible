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


class JDomFrameworkJqueryCookie extends JDomFrameworkJquery
{
	var $assetName = 'cookie';
	var $attachJs = array(
		'jquery.cookie.js'
	);	
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *  @lib		: Array - jQuery UI libraries
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		JDom::_('framework.jquery');
	}
	
	function build()
	{

	}

}