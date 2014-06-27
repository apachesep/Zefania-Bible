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


class JDomHtmlFormInputSelect extends JDomHtmlFormInput
{
	var $fallback = 'combo';		//Used for default

	var $domClass;
	var $selectors;

	protected $list;
	protected $listKey;
	protected $labelKey;
	protected $iconKey;
	protected $colorKey;
	protected $nullLabel;
	protected $size;
	protected $groupBy;


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

		$this->arg('list'		, null, $args);
		$this->arg('listKey'	, null, $args, 'id');
		$this->arg('labelKey'	, null, $args, 'text');
		$this->arg('colorKey'	, null, $args);
		$this->arg('iconKey'	, null, $args);
		$this->arg('size'		, null, $args);
		$this->arg('nullLabel'	, null, $args);
		$this->arg('groupBy'	, null, $args);
		$this->arg('domClass'	, null, $args);
		$this->arg('selectors'	, null, $args);

		//Reformate items
		
		$newArray = array();
		if (count($this->list))
		{
			$a = array_keys($this->list);
			if ($a == array_keys($a))//Not associative
			{
				$i = 0;
				foreach($this->list as $item)
				{
					if (is_array($item))
					{
						$newItem = new stdClass();
						foreach($item as $key => $value)
							$newItem->$key = $value;
		
						$newArray[$i] = $newItem;
					}
					$i++;
				}
			}
			else 
			{
				
				foreach($this->list as $key => $value)
				{
					if (is_string($value))
					{
				
						$newItem = new stdClass();
						$newItem->id = $key;
						$newItem->text = $value;
						
						$newArray[] = $newItem;
					}
					
				}
				
			}

			
		}

		if (count($newArray))
			$this->list = $newArray;
	}
}