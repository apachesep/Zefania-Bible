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


class JDomHtmlMenuCpanel extends JDomHtmlMenu
{
	
	var $assetName = 'cpanel';

	var $attachJs = array(
	);

	var $attachCss = array(
		'cpanel.css'
	);
	

	protected $iconSize;
	protected $buttonWidth;
	protected $buttonHeight;
	protected $maxLength;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *	@list		: Menu items
	 * 
	 * 
	 *  @iconSize : IconSize
	 *  @buttonWidth : Button width
	 *  @buttonHeight : Button height
	 *  @maxLength : Limit the size of the Title
	 */
	function __construct($args)
	{
		parent::__construct($args);
		
		$this->arg('iconSize'	, null, $args, 48);
		$this->arg('buttonWidth'	, null, $args, 100);
		$this->arg('buttonHeight'	, null, $args, 100);
		$this->arg('maxLength'	, null, $args);
		
	}

	function build()
	{
		if (empty($this->list))
			return;
	
		$html = '';
		$html .= '<div class="cpanel">';
	
		foreach($this->list as $item)
		{
			
			$title = JText::_($item[0]);
			if ($this->maxLength && (strlen($title) > $this->maxLength))
				$title = substr($title, 0, $this->maxLength - 1) . '...';
			
			$class = 'ico-' . $this->iconSize . '-' . (isset($item[3])?$item[3]:null);
			$html .= JDom::_('html.link.menu.cpanel', array(
				'content' => $title,
				'href' => $item[1],
				'link_title' => $item[0],
				'imageClass' => $class,
				
				'iconSize' => $this->iconSize,
				'buttonWidth' => $this->buttonWidth,
				'buttonHeight' => $this->buttonHeight,
				
			));
		}
		
		$html .= '</div>';
		
		return $html;
	}
}