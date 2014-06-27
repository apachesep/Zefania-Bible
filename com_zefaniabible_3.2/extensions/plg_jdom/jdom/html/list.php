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


class JDomHtmlList extends JDomHtml
{
	var $level = 2;				//Namespace position
	var $fallback = 'default';	//Used for default
	
	protected $dataList;
	protected $dataKey;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 * 	@dataKey	: database field name
	 * 	@dataList	: List
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);


		$this->arg('dataKey'	, null, $args);
		$this->arg('dataList'	, null, $args);

	}



}