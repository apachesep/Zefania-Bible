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
$cls_PlanRSSDefault = new PlanRssDefault($this->item); 
class PlanRssDefault
{

	public function __construct($item)
	{
		switch($item->str_variant)
		{
			case "atom":
				require_once(JPATH_COMPONENT_SITE.'/views/planrss/tmpl/atom.php');
				$mdl_atom 	= new PlanAtom($item);			
				break;
			case "ical":
				require_once(JPATH_COMPONENT_SITE.'/views/planrss/tmpl/ical.php');
				$mdl_ical 	= new PlanICal($item);			
				break;
								
			case "json":
				require_once(JPATH_COMPONENT_SITE.'/views/planrss/tmpl/json.php');
				$mdl_json 	= new PlanJSON($item);					
				break;
				
			case "json2":
				require_once(JPATH_COMPONENT_SITE.'/views/planrss/tmpl/json2.php');
				$mdl_json 	= new PlanJSON($item);					
				break;				
				
			default:
				require_once(JPATH_COMPONENT_SITE.'/views/planrss/tmpl/rss.php');
				$mdl_rss 	= new PlanRss($item);					
				break;	
		}
	
	}
}
?>