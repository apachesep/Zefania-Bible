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


class JDomHtmlFlyPublish extends JDomHtmlFly
{
	protected $icon;
	protected $text;
	protected $viewType;
	protected $color;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 *
	 *
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('viewType'	, null, $args);

		$states = array(
			'' => array('icomoon-question-sign', 'PLG_JDOM_UNDEFINED', 'both', 'default'),
			1 => array('icomoon-publish', 'JPUBLISHED', 'both', 'success'),
			0 => array('icomoon-unpublish', 'JUNPUBLISHED', 'both', 'danger'),
			2 => array('icomoon-archive', 'JARCHIVED', 'both', 'info'),
			-2 => array('icomoon-trash', 'JTRASHED', 'both', 'warning'));

		if ($this->dataValue === null)
			$this->dataValue = '';
		
		$state = $states[$this->dataValue];
		$this->icon = $state[0];
		$this->text = $this->JText($state[1]);
		if (empty($this->viewType))
			$this->viewType = $state[2];
		
		$this->color = $state[3];
	}
	
	function build()
	{
		//Icon alone
		if ($this->viewType == 'icon')
		{
			$html .= JDom::_('html.icon', array(
				'icon' => $this->icon,
				'tooltip' => true,
				'title' => $this->text
			));
			
			return $html;
		}
		
		
		//Icon
		if ($this->viewType == 'both')
		{
			$html .= JDom::_('html.icon', array(
				'icon' => $this->icon,
			));
						
		}

		$html .= $this->text;

		//Embed in label
		$html = JDom::_('html.label', array(
			'content' => $html,
			'color' => $this->color		
		));

		return $html;
	}

	protected function parseVars($vars)
	{
		return parent::parseVars(array_merge(array(
			'IMAGE_SOURCE' 	=> $this->image,
			'ALT' 			=> htmlspecialchars($this->text, ENT_COMPAT, 'UTF-8'),
		), $vars));
	}

}