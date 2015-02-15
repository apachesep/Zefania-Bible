<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
{
	JError::raiseWarning('5', 'ZefaniaBible - Editor Button Plugin - ZefaniaBible component is not installed or not enabled.');
	return;
}

class plgButtonZefaniabible extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onDisplay($name)
	{
		$js = "
		function jSelectScripture(tag) {
			jInsertEditorText(tag, '".$name."');
			SqueezeBox.close();
		}";
		
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);		
		/*
		 * Use the built-in element view to select the sermon.
		 * Currently uses blank class for Jooml 2.5 compatibility.
		 */

		if((strrpos(JURI::base(),'administrator',0) > 0)or(strrpos(JURI::base(),'administrator',0) !=''))
		{
			$link = 'index.php?option=com_zefaniabible&amp;view=zefaniamodal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';		 
		}
		else
		{
			$link = 'index.php?option=com_zefaniabible&amp;view=modal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';
		}
		JHtml::_('jquery.ui');
		JHTML::_('behavior.modal');
		JHtml::_('bootstrap.tooltip');
		JHtml::_('bootstrap.popover');	
		$document = JFactory::getDocument();	
		$document->addStyleSheet(JURI::root().'plugins/content/zefaniascripturelinks/css/zefaniascripturelinks.css'); 
		$document->addScript(JURI::root().'plugins/content/zefaniascripturelinks/zefaniascripturelinks.js');
		$button = new JObject();
		$button->modal = true;
		$button->link = $link;
		$button->class = 'btn';
		$button->text = JText::_('PLG_EDITORS-XTD_ZEFANIABIBLE_BUTTON_ZEFANIABIBLE');
		$button->name = 'file-add';
		$button->options = "{handler: 'iframe', size: {x: 770, y: 550}}";

		return $button;
	}
}
