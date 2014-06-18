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


class JDomHtmlFiltersDefault extends JDomHtmlFilters
{
	var $level = 3;				//Namespace position
	var $last = true;

	var $position;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *	@list		: Menu items
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('position'	, null, $args);
	}

	function build()
	{
		
		if ($this->position == 'top')
		{
			//Must reverse the order of filters of the right side
			$filtersLeft = array();
			$filtersRight = array();
			if (!empty($this->list))
			foreach($this->list as $filter)
			{
				if ($filter->align && ($filter->align == 'right'))
					$filtersRight[] = $filter;
				else
					$filtersLeft[] = $filter;
			}
			
			$filtersRight = array_reverse($filtersRight);
			$this->list = array_merge($filtersLeft, $filtersRight);
		}
		
		
		if (empty($this->list))
			return;
	
		$html = array();

		
		$i = 0;
		foreach($this->list as $filter)
		{
			// Horizontal separator
			if (($i > 0) && ($this->position == 'sidebar'))
				$html[] = '<hr class="hr-condensed" />';

			$htmlControl = '';

			// Label is invisible
			$htmlControl .= '<label class="element-invisible">';
			$htmlControl .= $filter->label;
			$htmlControl .= '</label>';

			// Render the input
			$htmlControl .= $filter->input;

			$classes = array();
			//Wrap in a div for horizontal alignement
			if ($filter->align && in_array($filter->align, array('left', 'right')))
			{
				$classes[] = 'btn-group';
				$classes[] = 'pull-' . $filter->align;
			}

			if ($responsive = $filter->responsive)
				$classes[] = $this->getResponsiveClass($responsive);
			
			if (count($classes))
			{
				$htmlControl = JDom::_('html.fly', array(
					'domClass' => implode(' ', $classes),
					'dataValue' => $htmlControl,
					'markup' => 'div'
				));
			}
			
			$html[] = $htmlControl;
			
			$i++;
		}
		
		return implode('', $html);
	}

}