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


class JDomHtmlListDefault extends JDomHtmlList
{
	var $level = 3;			//Namespace position
	var $last = true;		//Used for default
	
	/*
	 * 	@dataKey	: database field name
	 * 	@dataList	: List

	 *
	 */
	function __construct($args)
	{

		parent::__construct($args);

	}

	function build()
	{


		$html = '';

		return $html;
	}
	

}