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
		$option = JRequest::getString('option');
		$xmlFile = str_replace("com_", "", $option).'.xml';
		$xmlElement = simplexml_load_file(JPATH_ADMINISTRATOR.'/components/'.$option.'/'.$xmlFile);
		if($xmlElement)
		{
			$title = (string) $xmlElement->name;
			$version = (string) $xmlElement->version;
		}		
		echo '		<table style="margin-bottom: 5px; padding: 3px; width: 100%; border: 2px solid #BBBBBB; background:#F8F8F8"> ';
		echo '			<tr>';
		echo '				<td style="text-align: left; width: 25%; padding: 10px 0 0 10px;">';
		echo '					<img src="/administrator/components/com_zefaniabible/images/bible_128x128.png" width="128" height="128" border="0">';
		echo '				</td>';
		echo '				<td style="text-align: center; width: 49%;">';
		echo ' 					<a href="http://www.zefaniabible.com/?utm_campaign=admin&utm_medium=referral&utm_source='.str_replace('/administrator','',substr(JURI::base(),7,-1)).'" target="_blank">ZefaniaBible</a>';
		echo "					<br>"; 
		echo '					Copyright: 2012-'.date("Y").' © <a href="http://www.propoved.org/?utm_campaign=admin&utm_medium=referral&utm_source='.str_replace('/administrator','',substr(JURI::base(),7,-1)).'" target="_blank">Missionary Church of Grace</a> Web Development';
		echo "					<br>";		
		echo '					Version: '. $version;		
		echo '				</td>';
		echo '				<td style="text-align: right; width: 25%;">';
		echo '					<a href="http://extensions.joomla.org/extensions/living/religion/20470" target="blank">Leave Feedback on the JED</a>';
		echo "					<br>";
		echo '					<a href="https://www.facebook.com/ZefaniaBible" target="blank">Follow Us on Facebook</a>';
		echo "					<br>";
		echo '					<a href="http://www.zefaniabible.com/forum.html?utm_campaign=admin_forum&utm_medium=referral&utm_source='.str_replace('/administrator','',substr(JURI::base(),7,-1)).'" target="blank">Support Forums on ZefaniaBible</a>';
		echo "					<br>";
		echo '				</td> ';       
		echo '			</tr>';
		echo '		</table>';
	}
}
?>
