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

class BibleBooksAccordionView 
{
	public function __construct($item)
	{	
		jimport('joomla.html.html.bootstrap');
		$document	= JFactory::getDocument();
				
		echo '<div class="zef_books_list">';
		echo '	<div class="zef_books_column" >';
		echo '		<h2>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_OLD_TEST_BOOKS').'</h2>';
		echo JHtml::_('bootstrap.startAccordion', 'zef-books-slide-1', array('active' => ''));		
        for ($x = 1; $x<=39; $x++)
		{
			echo JHtml::_('bootstrap.addSlide', 'zef-books-slide-1', JText::_(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)), 'slide-'.$x);		
			foreach($item->arr_max_chapters as $obj_max_chapter)
			{
				if($x == $obj_max_chapter->book_id)
				{
					$int_max_chapter = $obj_max_chapter->max_chapter;
					break;	
				}
			}
			for($y = 1; $y <= $int_max_chapter; $y++)
			{
				$str_url = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$item->str_menuItem.
							"&bible=".$item->str_Bible_Version.
							"&book=".$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).
							"&chapter=".$y."-chapter");				
				echo '<a href="'.$str_url.'" target="_blank" title="'.JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)." ".$y." ".mb_strtolower((JText::_("ZEFANIABIBLE_BIBLE_CHAPTER")),'UTF-8').' ">'.$y.'</a>'.PHP_EOL;
			}
			echo JHtml::_('bootstrap.endSlide');				
		}
		echo JHtml::_('bootstrap.endAccordion');
		echo '</div>';
		
		echo '	<div class="zef_books_column">';	
		echo '		<h2>'.JText::_('ZEFANIABIBLE_BIBLE_BOOKS_NEW_TEST_BOOKS').'</h2>';
        echo JHtml::_('bootstrap.startAccordion', 'zef-books-slide-2', array('active' => ''));	
		for ($x = 40; $x<=66; $x++)
		{
			echo JHtml::_('bootstrap.addSlide', 'zef-books-slide-2', JText::_(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)), 'slide-'.$x);		
			foreach($item->arr_max_chapters as $obj_max_chapter)
			{
				if($x == $obj_max_chapter->book_id)
				{
					$int_max_chapter = $obj_max_chapter->max_chapter;
					break;	
				}
			}
			for($y = 1; $y <= $int_max_chapter; $y++)
			{
				$str_url = JRoute::_("index.php?option=com_zefaniabible&view=standard&Itemid=".$item->str_menuItem.
							"&bible=".$item->str_Bible_Version.
							"&book=".$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).
							"&chapter=".$y."-chapter");				
				echo '<a href="'.$str_url.'" target="_blank" title="'.JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x)." ".$y." ".mb_strtolower((JText::_("ZEFANIABIBLE_BIBLE_CHAPTER")),'UTF-8').' ">'.$y.'</a>'.PHP_EOL;
			}
			echo JHtml::_('bootstrap.endSlide');		
		}
		echo JHtml::_('bootstrap.endAccordion');
		echo '</div></div>';
		echo '<div style="clear:both"></div>';		
		
			
		
		
	
	}
}
?>
