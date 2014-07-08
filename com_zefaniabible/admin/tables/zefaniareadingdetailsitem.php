<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniareadingdetails
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
class TableZefaniareadingdetailsitem extends JTable
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
	 * @var int
	 */
	var $plan = null;
	/**
	 * @var int
	 */
	var $book_id = null;
	/**
	 * @var int
	 */
	var $begin_chapter = null;
	/**
	 * @var int
	 */
	var $begin_verse = null;
	/**
	 * @var int
	 */
	var $end_chapter = null;
	/**
	 * @var int
	 */
	var $end_verse = null;
	/**
	 * @var int
	 */
	var $day_number = null;
	/**
	 * @var string
	 */
	var $description = null;
	/**
	 * @var int
	 */
	var $ordering = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__zefaniabible_zefaniareadingdetails', 'id', $db);
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
		$this->plan = $filter->clean($this->plan, 'INT');
		$this->book_id = $filter->clean($this->book_id, 'INT');
		$this->begin_chapter = $filter->clean($this->begin_chapter, 'INT');
		$this->begin_verse = $filter->clean($this->begin_verse, 'INT');
		$this->end_chapter = $filter->clean($this->end_chapter, 'INT');
		$this->end_verse = $filter->clean($this->end_verse, 'INT');
		$this->day_number = $filter->clean($this->day_number, 'INT');
		$this->ordering = $filter->clean($this->ordering, 'INT');


		if (!empty($this->begin_chapter) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->begin_chapter)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_BEGIN_CHAPTER")) );
			$valid = false;
		}

		if (!empty($this->begin_verse) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->begin_verse)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_BEGIN_VERSE")) );
			$valid = false;
		}

		if (!empty($this->end_chapter) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->end_chapter)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_END_CHAPTER")) );
			$valid = false;
		}

		if (!empty($this->end_verse) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->end_verse)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_END_VERSE")) );
			$valid = false;
		}

		if (!empty($this->day_number) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->day_number)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_DAY_NUMBER")) );
			$valid = false;
		}

		if (!empty($this->ordering) && !preg_match("/^(\d|-)?(\d|,)*\.?\d*$/", $this->ordering)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_ORDERING")) );
			$valid = false;
		}





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
