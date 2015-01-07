<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Zefaniascriptureitem item controller class.
 *
 * @package     Zefaniabible
 * @subpackage  Controllers
 */
class ZefaniabibleControllerZefaniascriptureitem extends JControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'Zefaniascriptureitem';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'Zefaniascripture';
	
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
		$model = $this->getModel('Zefaniascriptureitem', 'ZefaniabibleModel', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_zefaniabible&view=zefaniascripture' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
}
?>