<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  JDom Class - Cook librarie    (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.0.0
* @package		Cook
* @subpackage	JDom
* @license		GNU General Public License
* @author		100% Vitamin - Jocelyn HUARD
*
*	-> You can reuse this framework for all purposes. Keep this signature. <-
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormLabel extends JDomHtmlForm
{
	var $level = 3;				//Namespace position
	var $last = true;		//This class is last call

	var $domID;
	var $label;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 * 	@domID		: database field name
	 * 	@label		: label caption (JText)
	 *  @domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('domID'		, 2, $args);
		$this->arg('label'		, 3, $args);
		$this->arg('domClass'	, 4, $args);
		$this->arg('selectors'	, 5, $args);


	}

	function build()
	{

		$html = '<label'
			.	' for="' . $this->domId . '"'
			.	$this->buildDomClass()
			.	$this->buildSelectors()
			.	'>'
			.	$this->JText($this->label)
			.	'</label>';


		return $html;
	}




}