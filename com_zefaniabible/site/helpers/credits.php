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
defined('_JEXEC') or die('Restricted access');
class ZefaniabibleCredits
{
	public function fnc_credits()
	{
		echo '<div class="zef_credits">';
		echo JText::_('ZEFANIABIBLE_DEVELOPED_BY')." <a href='http://www.zefaniabible.com/?utm_campaign=".JRequest::getCmd('view')."&utm_medium=referral&utm_source=".substr(JURI::base(),7,-1)."' target='_blank'>Zefania Bible</a>"; 
		echo "<br><a href='http://www.propoved.org/?utm_campaign=".JRequest::getCmd('view')."&utm_medium=referral&utm_source=".substr(JURI::base(),7,-1)."' target='_blank'>".JText::_('ZEFANIABIBLE_DEVELOPER_CREDIT')."</a>"; 
		echo '</div>';

	}
}
?>