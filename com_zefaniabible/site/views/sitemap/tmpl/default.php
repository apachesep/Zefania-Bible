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
$cls_zefania_sitemap = new ZefaniaSitemap($this->item);

class ZefaniaSitemap
{
		public function __construct($item)
		{					
			echo '<urlset>'.PHP_EOL;
			foreach($item->arr_chapter_list as $obj_chapter_list)
			{
				echo '	<url>'.PHP_EOL;
				echo '		<loc>'.substr(JURI::root(),0,-1).JRoute::_('index.php?option=com_zefaniabible&view=standard&bible='.$obj_chapter_list->alias.'&book='.$obj_chapter_list->book_id.'-'.strtolower(str_replace(" ","-",$item->arr_english_book_names[$obj_chapter_list->book_id])).'&chapter='.$obj_chapter_list->chapter_id.'-chapter&Itemid='.$item->str_view_plan).'</loc>'.PHP_EOL;
				echo '		<priority>'.$item->str_priority.'</priority>'.PHP_EOL;
				echo '		<changefreq>'.$item->str_frequency.'</changefreq>'.PHP_EOL;
				echo '		<lastmod>'.date("Y-m-d").'</lastmod>'.PHP_EOL;
				echo '	</url>'.PHP_EOL;
			}
			echo '</urlset>'.PHP_EOL;
		}
}
?>
