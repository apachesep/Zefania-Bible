<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


/**
* HTML View class for the Zefaniabible component
*
* @package	Zefaniabible
* @subpackage	Class
*/
class ZefaniabibleCkClassView extends CkJView
{
	/**
	* Call the parent display function. Trick for forking overrides.
	*
	* @access	protected
	* @param	string	$tpl	Template.
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected function _parentDisplay($tpl)
	{
		parent::display($tpl);
	}

	/**
	* Manage a template override in the fork directory
	*
	* @access	protected
	*
	* @return	void	
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected function addForkTemplatePath()
	{
		$this->addTemplatePath(JPATH_COMPONENT .DS. 'fork' .DS. 'views' .DS. $this->getName() .DS. 'tmpl');
	}

	/**
	* Convert a custom table to a JSON object string.
	*
	* @access	public static
	* @param	array	$headers	Defines the fields to include in the SQL select query.
	* @return	void
	*
	* @since	Cook 2.6.3
	*/
	public static function jsonList($headers = array())
	{
		// Get the datas
		$jinput = JFactory::getApplication()->input;

		$view = $jinput->get('view');
		$states = $jinput->get('__states', array(), 'array');

		$model = CkJModel::getInstance($view, 'ZefaniabibleModel');

		$model->setState('context', '');

		if (count($states))
			foreach($states as $var => $value)
				$model->setState($var, $value);

		// Apply the headers
		if (count($headers))
			$model->prepareQueryHeaders($headers);

		$data = $model->getItems();

		$ajax = new ZefaniabibleClassAjax();		
		$ajax->responseJson(array(
			'data' => $data,
			'headers' => $headers,
			'renderExceptions' => 'html',
		));
	}

	/**
	* Renders the fieldset form.
	*
	* @access	public
	* @param	array	$fieldset	Fielset. array of fields.
	*
	* @return	string	Rendered fields.
	*
	* @since	Cook 2.6.1
	*/
	public function renderFieldset($fieldset)
	{
		$html = '';

		// Iterate through the fields and display them.
		foreach($fieldset as $field)
		{
			//Check ACL
		    if ((method_exists($field, 'canView')) && !$field->canView())
		    	continue;
	
			$hidden = (empty($field->hidden)?$field->hidden:null);
			$id = (empty($field->id)?$field->id:null);
			$responsive = (empty($field->responsive)?$field->responsive:null);
			$type = (empty($field->type)?$field->type:null);
			$label = (empty($field->label)?$field->label:null);
			$input = (empty($field->input)?$field->input:null);

			if ($hidden)
			{
				$html .= $field->input;
				continue;
			}

			$selectors = (($type == 'Editor' || $type == 'Textarea') ? ' style="clear: both; margin: 0;"' : '');

			$html .= '<div class="control-group field-' . $id . $responsive . '">';

			$html .= '<div class="control-label">' 
					. $label
					. '</div>';

			$html .= '<div class="controls"' . $selectors . '>'
					. $field->input
					. '</div>';

			$html .= '</div>';
		}
		return $html;
	}

	/**
	* Renders the error stack and returns the results as a string
	*
	* @access	public
	* @param	boolean	$raw	Only stack of string. rendered HTML instead.
	*
	* @return	string	Rendered messages.
	*
	* @since	Cook 2.0
	*/
	public function renderMessages($raw = true)
	{
		// Initialise variables.
		$buffer = null;
		$lists = null;

		// Get the message queue
		$messages = JFactory::getApplication()->getMessageQueue();

		$rawMessages = array();
		// Build the sorted message list
		if (is_array($messages) && !empty($messages))
		{
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message']))
				{
					$lists[$msg['type']][] = $msg['message'];
					$rawMessages[] = $msg['message'];
				}
			}
		}

		if ($raw)
			return implode("\n", $rawMessages );

		// Build the return string
		$buffer .= "\n<div id=\"system-message-container\">";

		// If messages exist render them
		if (is_array($lists))
		{
			$buffer .= "\n<dl id=\"system-message\">";
			foreach ($lists as $type => $msgs)
			{
				if (count($msgs))
				{
					$buffer .= "\n<dt class=\"" . strtolower($type) . "\">" . JText::_($type) . "</dt>";
					$buffer .= "\n<dd class=\"" . strtolower($type) . " message\">";
					$buffer .= "\n\t<ul>";
					foreach ($msgs as $msg)
					{
						$buffer .= "\n\t\t<li>" . $msg . "</li>";
					}
					$buffer .= "\n\t</ul>";
					$buffer .= "\n</dd>";
				}
			}
			$buffer .= "\n</dl>";
		}

		$buffer .= "\n</div>";
		return $buffer;
	}

	/**
	* Renders the toolbar.
	*
	* @access	public
	* @param	array	$items	List of items. Used in few cases
	*
	* @return	string	Rendered toolbar.
	*
	* @since	Cook 2.6.2
	*/
	public function renderToolbar($items = null)
	{
		$render = true;

		$app = JFactory::getApplication();
		if ($app->isAdmin())
		{
			//Toolbar is handled by the administrator template
			$render = false;
	
			//Need to render it in case of modal view, or template less
			if ($app->input->get('tmpl') == 'component')
				$render = true;
		}

		if (!$render)
			return '';

		$html = JDom::_('html.toolbar', array(
			"bar" => JToolBar::getInstance('toolbar'),
			'list' => $items
		));

		return $html;
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassView')){ class ZefaniabibleClassView extends ZefaniabibleCkClassView{} }

