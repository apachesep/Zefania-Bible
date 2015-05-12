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
$cls_ScritpureDefault = new ScritpureDefault($this->item); 
class ScritpureDefault
{
	public function __construct($item)
	{
		$doc_page = JFactory::getDocument();
		switch($item->str_variant)
		{
			case "json":
				$doc_page->setMimeEncoding('application/json');	
				//JResponse::setHeader('Content-Disposition','attachment;filename=scripture.json');							
				require_once(JPATH_COMPONENT_SITE.'/views/scripture/tmpl/json.php');
				$mdl_atom 	= new BibleReadingPlan($item);			
				break;
			case "json2":
				$doc_page->setMimeEncoding('application/json');	
				//JResponse::setHeader('Content-Disposition','attachment;filename=scripture.json');							
				require_once(JPATH_COMPONENT_SITE.'/views/scripture/tmpl/json2.php');
				$mdl_atom 	= new BibleReadingPlan($item);			
				break;
				
			case "json3":
				$doc_page->setMimeEncoding('application/json');	
				//JResponse::setHeader('Content-Disposition','attachment;filename=scripture.json');							
				require_once(JPATH_COMPONENT_SITE.'/views/scripture/tmpl/json3.php');
				$mdl_atom 	= new BibleReadingPlan($item);			
				break;								
			default:
				require_once(JPATH_COMPONENT_SITE.'/views/scripture/tmpl/html.php');
				$mdl_rss 	= new BibleReadingPlan($item);					
				break;	
		}
		if($item->flg_enable_debug == 1)
		{
			echo '<!--';
			print_r($item);
			echo '-->';
		}
	
	}
}
?>