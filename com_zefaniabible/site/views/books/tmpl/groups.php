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

class BibleBooksGroupsView 
{
	public function __construct($item)
	{	
		echo '<div class="zef_books_list">';
		
		echo '	<div class="zef_books_column">';
		echo '		<h2>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_OLD_TEST_BOOKS').'</h2>';

			for($x = 1; $x <= 39; $x++)
			{
				switch ($x)
				{
					case 1:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_PENTATEUCH').'</h3>';			
						break;
					case 6:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_HISTORICAL').'</h3>';
						break;
					case 18:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_WISDOM').'</h3>';
						break;
					case 23:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_PROPHETIC').'</h3>';					
						break;
					default:
						break;
				}
				$str_url = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$item->str_menuItem.
								"&bible=".$item->str_Bible_Version.
								"&book=".$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).
								"&chapter=1-chapter");						
				echo '<div id="zef_book_'.$x.'" class="zef_book" title="'.JText::_("COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK").' '.JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$x).' 1 '.mb_strtolower((JText::_("ZEFANIABIBLE_BIBLE_CHAPTER")),'UTF-8').'">';
				echo '<a href="'.$str_url.'" target="_blank">';
				echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x);	
				echo '</a></div>';	
				echo '<div style="clear:both"></div>';		
			}		
		echo '	</div>';
		
		echo '    <div class="zef_books_column">';
		echo '    	<h2>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_NEW_TEST_BOOKS').'</h2>';
			for($x = 40; $x <= 66; $x++)
			{
				switch ($x)
				{
					case 40:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_GOSPEL').'</h3>';			
						break;
					case 45:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_EPISTLES').'</h3>';
						break;
					case 52:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_EPISTLES_PAUL').'</h3>';
						break;
					case 66:
						echo '		<h3>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_GROUP_REVELATION').'</h3>';				
						break;
					default:
						break;
				}
				$str_url = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$item->str_menuItem.
								"&bible=".$item->str_Bible_Version.
								"&book=".$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).
								"&chapter=1-chapter");						
				echo '<div id="zef_book_'.$x.'" class="zef_book" title="'.JText::_("COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK").' '.JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$x).' 1 '.mb_strtolower((JText::_("ZEFANIABIBLE_BIBLE_CHAPTER")),'UTF-8').'">';
				echo '<a href="'.$str_url.'" target="_blank">';
				echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x);	
				echo '</a></div>';	
				echo '<div style="clear:both"></div>';		
			}			
		
  		
		
		
		echo '    </div>';
		
		echo '</div>';
		echo '<div style="clear:both"></div>';
	}
}
?>
