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
$cls_zefania_sitemap = new ZefaniaSitemap($this->arr_chapter_list);

class ZefaniaSitemap
{
		public function __construct($arr_list)
		{
			$params = JComponentHelper::getParams( 'com_zefaniabible' );			
			$int_priority = $params->get('prio', '0.1');
			$str_frequency = $params->get('freq', 'weekly');	
			$str_menuItem = $params->get('rp_mo_menuitem', 0);
			
			// make english strings
			$jlang = JFactory::getLanguage();
			$jlang->load('com_zefaniabible', JPATH_COMPONENT, 'en-GB', true);
			for($i = 1; $i <=66; $i++)
			{
				$arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
			}
			$jlang->load('com_zefaniabible', JPATH_COMPONENT, null, true);
			
			echo '<urlset>'.PHP_EOL;
			foreach($arr_list as $obj_chapter_list)
			{
				echo '	<url>'.PHP_EOL;
				echo '		<loc>'.substr(JURI::root(),0,-1).JRoute::_('index.php?option=com_zefaniabible&view=standard&a='.$obj_chapter_list->alias.'&b='.$obj_chapter_list->book_id.'-'.strtolower(str_replace(" ","-",$arr_english_book_names[$obj_chapter_list->book_id])).'&c='.$obj_chapter_list->chapter_id.'-chapter&Itemid='.$str_menuItem).'</loc>'.PHP_EOL;
				echo '		<priority>'.$int_priority.'</priority>'.PHP_EOL;
				echo '		<changefreq>'.$str_frequency.'</changefreq>'.PHP_EOL;
				echo '		<lastmod>'.date("Y-m-d").'</lastmod>'.PHP_EOL;
				echo '	</url>'.PHP_EOL;
			}
			echo '</urlset>'.PHP_EOL;
		}
}
?>
