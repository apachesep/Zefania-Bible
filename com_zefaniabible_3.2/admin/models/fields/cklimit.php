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

if (!class_exists('ZefaniabibleClassFormField'))
	require_once(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR. 'components' .DIRECTORY_SEPARATOR. 'com_zefaniabible' .DIRECTORY_SEPARATOR. 'helpers' .DIRECTORY_SEPARATOR. 'loader.php');


/**
* Form field for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Form
*/
class ZefaniabibleCkFormFieldCklimit extends ZefaniabibleClassFormField
{
	/**
	* The form field type.
	*
	* @var string
	*/
	public $type = 'cklimit';

	/**
	* Method to get the field input markup.
	*
	* @access	public
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	public function getInput()
	{
		$options = array();
		if (!isset($this->jdomOptions['list']))
		{
			//Get the options from XML
			foreach ($this->element->children() as $option)
			{
				$opt = new stdClass();
				foreach($option->attributes() as $attr => $value)
					$opt->$attr = (string)$value;
		
				$opt->text = JText::_(trim((string) $option));
				$options[] = $opt;
			}
		}
		$this->input = JDom::_('html.form.input.select.limit', array_merge(array(
				'dataKey' => $this->getOption('name'),
				'domClass' => $this->getOption('class'),
				'domId' => $this->id,
				'domName' => $this->name,
				'dataValue' => (string)$this->value,
				'labelKey' => $this->getOption('labelKey'),
				'list' => $options,
				'listKey' => $this->getOption('listKey'),
				'nullLabel' => $this->getOption('nullLabel'),
				'responsive' => $this->getOption('responsive'),
				'size' => $this->getOption('size', 1, 'int'),
				'submitEventName' => ($this->getOption('submit') == 'true'?'onchange':null),
				'ui' => $this->getOption('ui')
			), $this->jdomOptions));

		return parent::getInput();
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JFormFieldCklimit')){ class JFormFieldCklimit extends ZefaniabibleCkFormFieldCklimit{} }

