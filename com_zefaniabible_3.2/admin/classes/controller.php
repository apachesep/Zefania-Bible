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



/**
* Zefaniabible  Controller
*
* @package	Zefaniabible
* @subpackage	
*/
class ZefaniabibleCkClassController extends CkJController
{
	/**
	* Call the parent display function. Trick for forking overrides.
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected function _parentDisplay()
	{
		//Add the fork views path (LILO) instead of FIFO
		array_push($this->paths['view'], JPATH_COMPONENT . DS. 'fork' .DS. 'views');

		parent::display();
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassController')){ class ZefaniabibleClassController extends ZefaniabibleCkClassController{} }

