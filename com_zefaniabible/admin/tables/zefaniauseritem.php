<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniauser
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
class TableZefaniauseritem extends JTable
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
	var $user_name = null;
	/**
	 * @var int
	 */
	var $plan = null;
	/**
	 * @var int
	 */
	var $bible_version = null;
	/**
	 * @var int
	 */
	var $user_id = null;
	/**
	 * @var string
	 */
	var $email = null;
	/**
	 * @var bool
	 */
	var $send_reading_plan_email = null;
	/**
	 * @var bool
	 */
	var $send_verse_of_day_email = null;
	/**
	 * @var string
	 */
	var $reading_start_date = null;






	/**
	* Constructor
	*
	* @param object Database connector object
	*
	*/
	function __construct(& $db)
	{
		parent::__construct('#__zefaniabible_zefaniauser', 'id', $db);
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
		$this->user_name = $filter->clean($this->user_name, 'STRING');
		$this->plan = $filter->clean($this->plan, 'INT');
		$this->bible_version = $filter->clean($this->bible_version, 'INT');
		$this->user_id = $filter->clean($this->user_id, 'INT');
		$this->email = $filter->clean($this->email, 'STRING');
		$this->send_reading_plan_email = $filter->clean($this->send_reading_plan_email, 'BOOL');
		$this->send_verse_of_day_email = $filter->clean($this->send_verse_of_day_email, 'BOOL');
		$this->reading_start_date = $filter->clean($this->reading_start_date, 'STRING');


		if (!empty($this->email) && !preg_match("/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/", $this->email)){
			JError::raiseWarning( 1000, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_VALUE_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_EMAIL")) );
			$valid = false;
		}

		if (!empty($this->reading_start_date) && ($this->reading_start_date != '0000-00-00'))
		{
			$reading_start_date = ZefaniabibleHelper::getSqlDate($this->reading_start_date, array('%Y-%m-%d'));
			if ($reading_start_date === null){
				JError::raiseWarning(2001, JText::sprintf("ZEFANIABIBLE_VALIDATOR_WRONG_DATETIME_FORMAT_FOR_PLEASE_RETRY", JText::_("ZEFANIABIBLE_FIELD_READING_START_DATE")));
				$valid = false;
			}
			else
				$this->reading_start_date = $reading_start_date->toMySQL();
		}









		return $valid;
	}
}
