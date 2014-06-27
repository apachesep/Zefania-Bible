<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Users
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
class ZefaniabibleCkJFormFieldModal_Thirduser extends ZefaniabibleClassFormFieldModal
{
	/**
	* Default label for the picker.
	*
	* @var string
	*/
	protected $_nullLabel = 'ZEFANIABIBLE_DATA_PICKER_SELECT_USER';

	/**
	* Option in URL
	*
	* @var string
	*/
	protected $_option = 'com_zefaniabible';

	/**
	* Modal Title
	*
	* @var string
	*/
	protected $_title;

	/**
	* View in URL
	*
	* @var string
	*/
	protected $_view = "thirdusers";

	/**
	* Field type
	*
	* @var string
	*/
	protected $type = 'modal_thirduser';

	/**
	* Method to get the field input markup.
	*
	* @access	protected
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	protected function getInput()
	{
		if ($this->value == 'auto')
			$this->_title = JText::_('ZEFANIABIBLE_AUTO');
		else
		{
			$db	= JFactory::getDBO();
			$db->setQuery(
				'SELECT `name`' .
				' FROM #__users' .
				' WHERE id = '.(int) $this->value
			);
			$this->_title = $db->loadResult();
	
			if ($error = $db->getErrorMsg()) {
				JError::raiseWarning(500, $error);
			}
		}

		return parent::getInput();
	}

	/**
	* Method to extend the buttons in the picker.
	*
	* @access	protected
	*
	* @return	array	An array of tasks
	*
	* @since	Cook 2.5.8
	*/
	protected function getTasks()
	{
		$labelAuto = JText::_('ZEFANIABIBLE_AUTO');
		$scriptAuto = "document.id('" . $this->id . "_id').value = 'auto';";
		$scriptAuto .= "document.id('" . $this->id . "_name').value = '" . htmlspecialchars($labelAuto, ENT_QUOTES, 'UTF-8') . "';";
		
		return array(
			'auto' => array(
				'label' => 'ZEFANIABIBLE_AUTO',
				'icon' => 'user',
				'jsCommand' => $scriptAuto,
				'description' => 'ZEFANIABIBLE_AUTOSELECT_CURRENT_USER'
			)

		);
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JFormFieldModal_Thirduser')){ class JFormFieldModal_Thirduser extends ZefaniabibleCkJFormFieldModal_Thirduser{} }

