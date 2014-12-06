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
class ClsVerseHTML
{
	public function __construct($item)
	{
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();		
		$str_verse = '';
		$x = 1;
		$int_book_name = 1;
		$int_chapter_number = 1;
		$int_begin_verse = 1;
		$int_end_verse = 0;
		foreach ($item->arr_verse_info as $obj_arr_verse_info)
		{
			$int_book_name 		= 	$obj_arr_verse_info->book_name;	
			$int_chapter_number =	$obj_arr_verse_info->chapter_number;
			$int_begin_verse	=	$obj_arr_verse_info->begin_verse;
			$int_end_verse 		=	$obj_arr_verse_info->end_verse;
		}
		$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_name)." ".$int_chapter_number.":".$int_begin_verse;
		if($int_end_verse)
		{
			$str_title .= "-". $int_end_verse;
		}

			// Facebook Open Graph
			$this->doc_page = JFactory::getDocument();	
			$this->doc_page->setMetaData( 'og:title', JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY'));
			$this->doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
			$this->doc_page->setMetaData( 'og:type', "article" );	
			$this->doc_page->setMetaData( 'og:image', JURI::root().$item->str_default_image );	
			$this->doc_page->setMetaData( 'og:description', JText::_('ZEFANIABIBLE_VIEW_VERSE_OF_DAY') );
			$this->doc_page->setMetaData( 'og:site_name', $mainframe->getCfg('sitename') );	
			$this->doc_page->addCustomTag( '<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />');				
			echo '<div id="zef_Bible_Main_verse_tmpl_comp">';
			echo '<div class="zef_bible_Header_Label">'.$str_title.'</div>';
			echo '<div style="clear:both"></div>';
			foreach ($item->arr_verse_of_day as $obj_verse)
			{
				if ($x % 2)
				{
					echo '<div class="odd">';
				}
				else
				{
					echo '<div class="even">'; 
				}	
				echo $obj_verse->verse.'</div>';
				$x++;
			}
			echo '<div style="clear:both"></div>';
			echo '</div>';
	}
}

?>