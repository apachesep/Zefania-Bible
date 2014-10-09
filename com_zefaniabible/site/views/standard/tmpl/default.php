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

$cls_bible_reading_plan = new StandardViewDefault($this->item, $this->obj_player);

class StandardViewDefault
{
	public function __construct($item, $obj_player)
	{
		switch($item->standard_layout)
		{
			case "bible":
				require_once(JPATH_COMPONENT_SITE.'/views/standard/tmpl/bible.php');	
				$mdl_view 	= new BibleView($item, $obj_player);				
				break;
			case "single":
				require_once(JPATH_COMPONENT_SITE.'/views/standard/tmpl/standard.php');	
				$mdl_view 	= new StandardView($item, $obj_player);	
				break;
				
			case "table":
				require_once(JPATH_COMPONENT_SITE.'/views/standard/tmpl/table.php');	
				$mdl_view 	= new TableView($item, $obj_player);				
				break;
				
			default:
				require_once(JPATH_COMPONENT_SITE.'/views/standard/tmpl/standard.php');	
				$mdl_view 	= new StandardView($item, $obj_player);
				break;	
		}	
	}
}
?>
