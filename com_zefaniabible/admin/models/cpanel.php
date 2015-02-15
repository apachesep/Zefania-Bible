<?php
/**
 * @author		Andrei Chernyshev
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * List Model for zefaniabible.
 *
 * @package     Zefaniabible
 * @subpackage  Models
 */
class ZefaniabibleModelCpanel extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{

		parent::__construct($config);
	}
}
?>