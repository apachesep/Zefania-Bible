<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Zefaniabibledictionaryinfo
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
* Zefaniabible Zefaniabibledictionaryinfoitem Controller
*
* @package	Zefaniabible
* @subpackage	Zefaniabibledictionaryinfoitem
*/
class ZefaniabibleCkControllerZefaniabibledictionaryinfoitem extends ZefaniabibleClassControllerItem
{
	/**
	* The context for storing internal data, e.g. record.
	*
	* @var string
	*/
	protected $context = 'zefaniabibledictionaryinfoitem';

	/**
	* The URL view item variable.
	*
	* @var string
	*/
	protected $view_item = 'zefaniabibledictionaryinfoitem';

	/**
	* The URL view list variable.
	*
	* @var string
	*/
	protected $view_list = 'zefaniabibledictionaryinfo';

	/**
	* Constructor
	*
	* @access	public
	* @param	array	$config	An optional associative array of configuration settings.
	* @return	void
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
		$app = JFactory::getApplication();

	}

	/**
	* Return the current layout.
	*
	* @access	protected
	* @param	bool	$default	If true, return the default layout.
	*
	* @return	string	Requested layout or default layout
	*/
	protected function getLayout($default = null)
	{
		if ($default)
			return '';

		$jinput = JFactory::getApplication()->input;
		return $jinput->get('layout', '', 'CMD');
	}

	/**
	* Function that allows child controller access to model data after the data
	* has been saved.
	*
	* @access	protected
	* @param	JModel	&$model	The data model object.
	* @param	array	$validData	The validated data.
	* @return	void
	*/
	protected function postSaveHook(&$model, $validData = array())
	{
		parent::postSaveHook($model, $validData);
		//UPLOAD FILE : xml_file_url
		$model->_upload('xml_file_url', array(
										'application/xml' => 'xml'));
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleControllerZefaniabibledictionaryinfoitem')){ class ZefaniabibleControllerZefaniabibledictionaryinfoitem extends ZefaniabibleCkControllerZefaniabibledictionaryinfoitem{} }

