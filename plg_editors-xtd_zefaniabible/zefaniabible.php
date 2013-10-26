<?php
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

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
		$link = 'index.php?option=com_zefaniabible&amp;view=zefaniamodal&amp;tmpl=component&amp;'.JSession::getFormToken().'=1';

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_EDITORS-XTD_ZEFANIABIBLE_BUTTON_ZEFANIABIBLE'));
		$button->set('name', 'broadcast blank');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}
