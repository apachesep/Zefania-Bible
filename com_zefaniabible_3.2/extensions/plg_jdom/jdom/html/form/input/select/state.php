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


class JDomHtmlFormInputSelectState extends JDomHtmlFormInputSelect
{
	protected $display;
	protected $states;
	
	protected $colorKey;
	protected $iconKey;
	protected $viewKey = 'viewType';
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 * 
	 *  @display	: How to display the state (radio, combo)
	 *  @states		: Define a static list
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('states', null, $args);
		$this->arg('display', null, $args, 'radio');

		$this->arg('listKey', null, $args, 'value');
		$this->arg('labelKey', null, $args, 'text');
		$this->arg('colorKey', null, $args, 'color');
		$this->arg('iconKey', null, $args, 'icon');
		
		$labelKey = $this->labelKey;
		$listKey = $this->listKey;
		$colorKey = $this->colorKey;
		$iconKey = $this->iconKey;
		$viewKey = $this->viewKey;
		
		if (!$this->list)
		{
			//Default states are PUBLISHED state
			if (!$this->states)
			{
				$this->states = array(
					0 => array('icomoon-unpublish', 'JUNPUBLISHED', 'both', 'danger'),
					1 => array('icomoon-publish', 'JPUBLISHED', 'both', 'success'),
					2 => array('icomoon-archive', 'JARCHIVED', 'both', 'info'),
					-2 => array('icomoon-trash', 'JTRASHED', 'both', 'warning')
				);
			}

			//Convert static list to object list
			$this->items = array();	
			foreach($this->states as $value => $state)
			{
				$item = new stdClass();
				$item->$listKey = (string)$value;
				if ($c = count($state))
					$item->$iconKey = $state[0];
				if ($c > 1)
					$item->$labelKey = JText::_($state[1]);
				if ($c > 2)
					$item->$viewKey = $state[2];
				if ($c > 3)
					$item->$colorKey = $state[3];
				
				$this->list[] = $item;
			}
		}
	}

	function build()
	{
		$namespace = '';
		switch($this->display)
		{
			case 'combo':
				$namespace = 'html.form.input.select.combo';
				break;


			case 'radio':
			default:
				$namespace = 'html.form.input.select.radio';
				break;		
		}

		$html = JDom::_($namespace, array_merge($this->options, array(
			'list' => $this->list
		)));
		
		return $html;
	}

}