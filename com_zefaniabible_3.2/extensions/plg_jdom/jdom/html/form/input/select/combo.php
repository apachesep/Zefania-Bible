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


class JDomHtmlFormInputSelectCombo extends JDomHtmlFormInputSelect
{
	var $canEmbed = true;
	
	protected $ui;
	protected $multiple;
	protected $valueKey;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 * 	@list		: Possibles values list (array of objects)
	 * 	@listKey	: ID key name of the list
	 * 	@labelKey	: Caption key name of the list
	 * 	@size		: Size in rows ( 0,null = combobox, > 0 = list)
	 * 	@nullLabel	: First choice label for value = '' (no null value if null)
	 * 	@groupBy	: Group values on key(s)  (Complex Array Struct)
	 * 	@domClass	: CSS class
	 * 	@selectors	: raw selectors (Array) ie: javascript events
	 *
	 *
	 *	@ui			: rendering effect (User Interface). (possible values : 'chosen')
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);
		$this->arg('ui', 	null, $args);
		$this->arg('multiple', 	null, $args);
		$this->arg('valueKey', 	null, $args, $this->dataKey);
		
		if ($this->multiple)
			$this->domName .= '[]';
		
	}

	function build()
	{
		
		if ($this->groupBy)
			$options = $this->buildOptionsGroup();
		else
			$options = $this->buildOptions();


		if ($this->ui == 'chosen')
		{
			JDom::_('framework.jquery.chosen');			
			$this->addClass('chzn-select');
		}

		$html =	'<select id="<%DOM_ID%>" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>'
			. 	($this->multiple?' multiple':'')
			.	((int)$this->size > 1?' size="' . (int)$this->size . '"':'') . '>' .LN
			.	$this->indent($this->buildDefault(), 1)
			.	$this->indent($options, 1)
			.	'</select>'.	LN
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>';

		return $html;

	}
	
	function buildJs()
	{
		if ($this->useFramework('chosen') && $this->ui == 'chosen')
		{
			$js = 'jQuery(".chzn-select").chosen({
				disable_search_threshold : 10,
				allow_single_deselect : true
			});';
			$this->addScriptInline($js, !$this->isAjax());
		}
	}

	function buildDefault()
	{
		if (!$this->nullLabel)
			return '';

		$item = new stdClass();
		$item->id = '';
		$item->text = JText::_($this->nullLabel);

		return $this->buildOption($item, 'id', 'text');

	}

	function buildOptions()
	{
		$html =	'';

		if ($this->list)
		foreach($this->list as $item)
		{
			$html .= $this->buildOption($item, $this->listKey, $this->labelKey);
		}

		return $html;

	}

	function buildOptionsGroup()
	{
		$indentStr = 		'&nbsp;&nbsp;&nbsp;';
		$indentStrGroup = 	'&nbsp;&nbsp;&nbsp;';


		$html =	'';

		$groupBy = array_reverse($this->groupBy);
		$group = array();

		$indent = 0;

		if ($this->list)
		foreach($this->list as $item)
		{
			// Close OPTGROUP
			foreach(array_reverse($groupBy) as $groupKey => $groupLabelKey)
			{
				if (isset($group[$groupKey]) && $group[$groupKey] != $item->$groupKey)
				{
					if ($group[$groupKey] != null)
					{
						$indent --;
						$html .= $this->indent('</optgroup>', $indent) .LN;
					}

				}
			}

			// Open OPTGROUP
			foreach($groupBy as $groupKey => $groupLabelKey)
			{
				if (!isset($group[$groupKey]) || $group[$groupKey] != $item->$groupKey)
				{

					$prefixGroup = str_repeat($indentStrGroup, $indent);

					$html .= $this->indent(
							'<optgroup label="'
							. $prefixGroup . htmlspecialchars($this->parseKeys($item, $groupLabelKey), ENT_COMPAT, 'UTF-8')
							. '">' .LN
							, $indent);

					$indent ++;
					$group[$groupKey] = $item->$groupKey;

				}
			}

			// build the OPTION
			$prefix = str_repeat($indentStr, $indent);
			$html .= $this->indent($this->buildOption($item, $this->listKey, $this->labelKey, $prefix), $indent);

		}

		//Close last GROUPS
		foreach(array_reverse($groupBy) as $groupKey => $groupLabelKey)
		{
			if (isset($group[$groupKey]) && $group[$groupKey] != null)
			{
				$indent --;
				$html .= $this->indent('</optgroup>', $indent) .LN;
			}
		}

		return $html;
	}

	function buildOption($item, $listKey, $labelKey, $prefix = '')
	{
		//If item is an array, convert it to an object
		if (!is_object($item))
			$item = JArrayHelper::toObject($item);

		if ($item === null)
			$item = new stdClass();
		
		
		if (!isset($item->$listKey))
			$item->$listKey = null;

		// In case of multi select
		if (is_array($this->dataValue) && count($this->dataValue))
		{
			// When a list is send as value : reduce array to the dataKey only.
			if (is_object($this->dataValue[0]))
			{
				$valueKey = $this->valueKey;
				$values = array();
				foreach($this->dataValue as $row)
				{
					$values[] = $row->$valueKey;
				}				
			}
			else
				$values = $this->dataValue;
			
			$selected = in_array((int)($item->$listKey), $values);
		}
		
		//Integer compatibility when possible
		else if (is_integer($this->dataValue))			
			$selected = ((int)$item->$listKey === $this->dataValue);
		else
			$selected = ($item->$listKey === $this->dataValue);
			

		$html =	'<option value="'
			.	htmlspecialchars($item->$listKey, ENT_COMPAT, 'UTF-8')
			. 	'"'
			.	($selected?' selected="selected"':'')
			.	'>'
			.	$prefix . $this->parseKeys($item, $labelKey)
			. 	'</option>'.LN;

		return $html;
	}

}