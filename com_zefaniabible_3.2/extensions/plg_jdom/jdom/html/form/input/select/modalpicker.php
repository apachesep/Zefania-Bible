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


class JDomHtmlFormInputSelectModalpicker extends JDomHtmlFormInputSelect
{

	//Does not embed automatically as link 
	var $allowWrapLink = false;

	protected $title;
	protected $tasks = array();
	protected $display;
	
	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 * 	@size		: Input Size
	 *	
	 *  @title		: Current selected string
	 *  @width		: Modal width
	 *  @height		: Modal height
	 * 	@tasks		: Extra tasks 
	 * 	@display	: Determines how to show the extra tasks (icon,text,both)
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('title'		, null, $args);
		$this->arg('width'		, null, $args);
		$this->arg('height'		, null, $args);	
		$this->arg('tasks'		, null, $args);	
		$this->arg('display'	, null, $args, 'icon');	
	}
	
	function build()
	{
		
		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		$id = $this->dataKey;
		$name = $this->name;
		$label = JText::_($this->nullLabel);

		$link = $this->getRoute($this->route, 'modal');
		$rel = '{handler: \'iframe\', size: {x: ' . $this->width . ', y: ' . $this->height . '}}';
		
		
		$title = $this->title;
		if (empty($title)) {
			$title = $label;
		}

		$scriptReset = "document.id('" . $id . "_id').value = '';";
		$scriptReset .= "document.id('" . $id . "_name').value = '" . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . "';";
				
		$html	= array();
	
		//Remove button
		$htmlReset = '	<a class="btn"'
				.	' title="' .htmlspecialchars($label, ENT_QUOTES, 'UTF-8') .'"'
				.	' onclick="' . htmlspecialchars($scriptReset, ENT_QUOTES, 'UTF-8') . '"'
				.	'>' . LN;
				
		$htmlReset .= ' <i class="icon-delete"></i>' . LN;
		$htmlReset .= ' </a>' . LN;
		
		//Input area (readonly)
		$htmlInput = JDom::_('html.form.input.text', array(
			'dataKey' => $id . '_name',
			'dataValue' => $title,
			'domClass' => 'inputbox input-medium',
			'selectors' => array(
				'disabled' => 'disabled',
				'readonly' => 'readonly'
			
			),			
			'size' => 40
		));

		//Select button
		$htmlSelect = '	<a class="modal btn btn-primary" title="'.$label.'"  href="'.$link.'" rel="' . $rel . '">' .LN;
		$htmlSelect .= ' <i class="icon-list icon-white"></i> ' . JText::_('JSELECT') .LN;
		$htmlSelect .= ' </a>' .LN;


		// Hidden field
		$htmlHidden = JDom::_('html.form.input.hidden', array(
			'dataKey' => $this->dataKey . '_id',
			'domName' => $this->domName,
			'dataValue' => $this->dataValue
		));
		
	
		$htmlTasks = '';
		//Extra tasks buttons
		if (count($this->tasks))
		{
			foreach($this->tasks as $taskName => $task)
			{
				$label = (isset($task['label'])?JText::_($task['label']):'');
				$desc = (isset($task['description'])?JText::_($task['description']):$label);
				$jsCommand = (isset($task['jsCommand'])?$task['jsCommand']:null);
				$icon = (isset($task['icon'])?$task['icon']:$taskName);
				
				
				$caption = '';
				if (in_array($this->display, array('icon', 'both')))
					$caption .= ' <i class="icon-' . $icon . '"></i> ';
				
				if (in_array($this->display, array('text', 'both')))
					$caption .= $label;
				
				
				$htmlTasks .= '	<a class="btn hasTooltip" title="'.htmlspecialchars($desc, ENT_QUOTES, 'UTF-8').'"'
						.	($jsCommand?' onclick="'.htmlspecialchars($jsCommand, ENT_QUOTES, 'UTF-8').'"':'')
						.	'>' .LN;
				$htmlTasks .= $caption;
				$htmlTasks .= ' </a>' .LN;				
				
				
			}

		}
	
		
		// Construct the control
		$html = $htmlReset . $htmlInput . $htmlSelect . $htmlTasks;
		
		// Embed in bootsrap btn-group
		$html = '<span class="input-append input-prepend">' . $html . '</span>';
		
		// Add the hidden field (storing the value)
		$html.= $htmlHidden;
		
		return $html;		
	}


	function buildJs()
	{
		$script = array();
		$script[] = '	function jSelectItem(id, title, object) {';
		$script[] = '		document.id(object + "_id").value = id;';
		$script[] = '		document.id(object + "_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		//Not working ??	
//		$this->addScriptInline(implode("\n", $script));	

		//workaround
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));	
	}

}