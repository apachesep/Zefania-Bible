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

defined('_JEXEC') or die('Restricted access'); ?>
<?php 
$cls_verse_rss_default = new ClsVerseRSSDefault($this->item);

class ClsVerseRSSDefault
{
	public function __construct($item)
	{
		switch($item->str_variant)
		{				
			case "json":
				require_once(JPATH_COMPONENT_SITE.'/views/verserss/tmpl/json.php');
				$mdl_json 	= new ClsVerseJSON($item);					
				break;
			case "rss":
				require_once(JPATH_COMPONENT_SITE.'/views/verserss/tmpl/rss.php');
				$mdl_json 	= new ClsVerseRSS($item);					
				break;
			default:
				require_once(JPATH_COMPONENT_SITE.'/views/verserss/tmpl/html.php');
				$mdl_json 	= new ClsVerseHTML($item);					
				break;	
		}
	}
}

?>