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


class JDomHtmlFormInputSelect extends JDomHtmlFormInput
{
	var $level = 4;			//Namespace position : function

	var $fallback = 'combo';		//Used for default

	var $list;
	var $listKey;
	var $labelKey;
	var $nullLabel;
	var $size;
	var $groupBy;
	var $domClass;
	var $selectors;


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
	 * 	@list		: Possibles values list (array of objects)
	 * 	@listKey	: ID key name of the list
	 * 	@labelKey	: Caption key name of the list
	 * 	@size		: Size in rows ( 0,null = combobox, > 0 = list)
	 * 	@nullLabel	: First choice label for value = '' (no null value if null)
	 * 	@groupBy	: Group values on key(s)  (Complex Array Struct)
	 * 	@domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('list'		, 6, $args);
		$this->arg('listKey'	, 7, $args, 'id');
		$this->arg('labelKey'	, 8, $args);
		$this->arg('size'		, 9, $args);
		$this->arg('nullLabel'	, 10, $args);
		$this->arg('groupBy'	, 11, $args);
		$this->arg('domClass'	, 12, $args);
		$this->arg('selectors'	, 13, $args);


		//Reformate items
		$i = 0;
		$newArray = array();
		if (count($this->list))
		foreach($this->list as $item)
		{
			if (is_array($item))
			{
				$newItem = new stdClass();
				foreach($item as $key => $value)
				{
					$newItem->$key = $value;
				}

				$newArray[$i] = $newItem;

			}
			$i++;
		}

		if (count($newArray))
			$this->list = $newArray;


	}




}