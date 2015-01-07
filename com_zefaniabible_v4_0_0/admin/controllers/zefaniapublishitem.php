<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * ZefaniapublishItem item controller class.
 *
 * @package     Zefaniabible
 * @subpackage  Controllers
 */
class ZefaniabibleControllerZefaniapublishItem extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'ZefaniapublishItem';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'Zefaniapublish';
	
	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('ZefaniapublishItem', 'ZefaniabibleModel', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_zefaniabible&view=zefaniapublish' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
?>