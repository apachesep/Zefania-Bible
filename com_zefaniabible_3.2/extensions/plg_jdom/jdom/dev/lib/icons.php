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


class JDomDevLibIcons extends JDomDevLib
{
	/*
	 * Constuctor
	 */
	function __construct($args)
	{
		parent::__construct($args);
	}

	function build()
	{
		$html = array();
		$icon = JDom::_('html.icon', array(
			'icon' => 'icomoon-edit'
		));
		$html[] = $this->renderLibButton('', 'Default', $icon);


		$icon = JDom::_('html.icon', array(
			'icon' => 'icomoon-pencil-2'
		));
		$html[] = $this->renderLibButton('icomoon', 'Icomoon', $icon);

		$icon = JDom::_('html.icon', array(
			'icon' => 'glyphicon-th'
		));
		$html[] = $this->renderLibButton('glyphicon', 'Bootstrap glyphicon', $icon);

		return implode(' - ', $html);
	}

	function renderLibButton($library, $title, $content)
	{
		$html = $this->buildIconsLib($library);

		if ($this->display == 'button')
		{
			$html = JDom::_('html.link.tooltip.popover', array(
				'raw' => true,
				
				'content' => $content,
				'contents' => htmlspecialchars($html),
				'title' => $title,
				'placement' => 'top',
				'rel' => 'myToolTip', //Distinct from others tooltip options
				
				'delay' => array(
					'show' => 0,
					'hide' => 3000
				),
				
			));	
		}

	
		
		return $html;
	}
	
	function buildCss()
	{
		$css = '.iconlib .square-icon{
			background-color:#EEE;
			margin:1px;
			float:left;
			line-height:0px;
		}';
		$this->addStyleDeclaration($css);
	}


	function buildIconsLib($library)
	{
		//Get the icons availability
		$icons = array();
		switch($library)
		{	
			case '':
				$icons = $this->getIcons();
				break;
				
				
			case 'icomoon':
				$jdom = JDom::getInstance('html.icon.icomoon');
				$icons = $jdom->getIcons();
				break;
				
			case 'glyphicon':
				$jdom = JDom::getInstance('html.icon.glyphicon');
				$icons = $jdom->getIcons();				
				break;
		}
		
		if (empty($icons))
			return;
		
		return $this->renderIcons($library, $icons);
	}

	function getIcons()
	{
		//Default icons possibly used by Cook
		$default = array(
			'edit', 'delete', 'publish', 'unpublish', 'trash', 'archive'
		);
		
		return $default;	
	}
	
	function renderIcons($library, $icons)
	{
		$html = array();
		foreach($icons as $icon)
		{
			$htmlIcon = JDom::_('html.icon', array(
				'icon' => $icon,
				'tooltip' => true,
				'title' => ($library?'.'.$library:'') . '.icon-' . $icon,
				'library' => $library,
			));
						
			$html[] = '<div class="square-icon">'
				.	$htmlIcon
				.	'</div>';
		}
		
		$html = '<div class="iconlib">'
			.	implode(LN, $html)
			.	'</div>';
		
		return $html;
	}

}