<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.missionarychurchofgrace.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
* /!\  Joomla! is free software.
* This version may have been modified pursuant to the GNU General Public License,
* and as distributed it includes or is derivative of works licensed under the
* GNU General Public License or other free or open source software licenses.
*
*             .oooO  Oooo.     See COPYRIGHT.php for copyright notices and details.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/



// no direct access
defined('_JEXEC') or die('Restricted access');


/**
* Zefaniabible Table class
*
* @package		Joomla
* @subpackage	Zefaniabible
*
*/
class TableZefaniadictionaryitem extends JTable
{

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $attribs = null;

	/**
	 * @var string
	 */
	var $alias = null;
	/**
	 * @var bool
	 */
	var $publish = null;
	/**
	 * @var int
	 */
	var $ordering = null;

	var $name = null;
	var $xml_file_url = null;


	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__zefaniabible_dictionary_info', 'id', $db);
	}




	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	*
	*/
	function bind($src, $ignore = array())
	{

		if (isset($src['attribs']) && is_array($src['attribs']))
		{
			$registry = new JRegistry;
			$registry->loadArray($src['attribs']);
			$src['attribs'] = (string) $registry;
		}

		return parent::bind($src, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @see JTable:check
	 */
	function check()
	{
		$valid = true;

		$filter = new JFilterInput(array(), array(), 0, 0);

		$this->name = $filter->clean($this->name, 'STRING');
		$this->alias = $filter->clean($this->alias, 'STRING');
		$this->xml_file_url = $filter->clean($this->xml_file_url, 'STRING');
		$this->publish = $filter->clean($this->publish, 'BOOL');
		$this->ordering = $filter->clean($this->ordering, 'INT');

		if (!empty($this->ordering) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->ordering)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_ORDERING")) );
			$valid = false;
		}





		//Alias
		if (!trim($this->alias))
			$this->alias = JFilterOutput::stringURLSafe($this->title);

		//New row : Ordering : place to the end
		if ($this->id == 0)
		{
			$db= JFactory::getDBO();

			$query = 	'SELECT `ordering` FROM `' . $this->_tbl . '`'
					. 	' ORDER BY `ordering` DESC LIMIT 1';
			$db->setQuery($query);
			$lastOrderObj = $db->loadObject();
			$this->ordering = (int)$lastOrderObj->ordering + 1;
		}





		return $valid;
	}
}
