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

$cls_bible_reading_plan = new BooksViewDefault($this->item);

class BooksViewDefault
{
	public function __construct($item)
	{
		switch($item->str_variant)
		{
			case 'accordion':
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/accordion.php');	
				$mdl_view 	= new BibleBooksAccordionView($item);				
				break;

			case 'across':
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/across.php');	
				$mdl_view 	= new BibleBooksAcrossView($item);			
				break;
				
			case 'chapters':
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/chapters.php');	
				$mdl_view 	= new BibleBooksChaptersView($item);
				break;
				
			case 'groups':
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/groups.php');	
				$mdl_view 	= new BibleBooksGroupsView($item);			
				break;
				
			case 'json':
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/json.php');	
				$mdl_view 	= new BibleBooksJSON($item);				
				break;
				
			default: 
				require_once(JPATH_COMPONENT_SITE.'/views/books/tmpl/list.php');	
				$mdl_view 	= new BibleBooksListView($item);
				break;	
		}	
	}
}
?>
