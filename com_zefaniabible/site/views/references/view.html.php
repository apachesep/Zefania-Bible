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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewReferences extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;
	private $str_primary_dictionary;
	
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();
		$layout = $this->getLayout();
		switch($layout)
		{

			case 'default':
				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}
	}
	
	function display_default($tpl = null)
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		/*
			a = Bible Version
			b = book
			c = chapter
			d = verse
		*/	
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		require_once(JPATH_COMPONENT_SITE.'/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;		
		
		JHTML::stylesheet('components/com_zefaniabible/css/modal.css');
		JHTML::stylesheet('zefaniascripturelinks.css', 'plugins/content/zefaniascripturelinks/css/');
		
		$jinput = JFactory::getApplication()->input;
		$item = new stdClass();
		$item->str_primary_bible 				= $params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$item->flg_show_commentary 				= $params->get('show_commentary', '0');
		$item->str_primary_commentary 			= $params->get('primaryCommentary');
		$item->flg_reference_words 				= $params->get('flg_reference_words', '1');
		$item->flg_reference_chapter_link 		= $params->get('flg_reference_chapter_link', '1');
		$item->str_primary_dictionary  			= $params->get('str_primary_dictionary','');
		$item->str_ref_default_image 			= $params->get('str_ref_default_image', 'media/com_zefaniabible/images/references.jpg');
		$item->flg_enable_debug					= $params->get('flg_enable_debug','0');
		
		$item->str_Bible_Version 	= $jinput->get('bible', $item->str_primary_bible, 'CMD');	
		$item->int_Bible_Book_ID 	= $jinput->get('book', '1', 'INT');	
		$item->int_Bible_Chapter 	= $jinput->get('chapter', '1', 'INT');		
		$item->int_Bible_Verse	 	= $jinput->get('verse', '1', 'INT');
		
		$item->arr_english_book_names 	= $mdl_common->fnc_load_languages();		
		$item->arr_references 			= $mdl_default->_buildQuery_Single_Reference($item->int_Bible_Book_ID, $item->int_Bible_Chapter, $item->int_Bible_Verse );
		$item->str_view_plan			= $mdl_default->_buildQuery_get_menu_id('standard');
		
			$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
			$arr_orig_ref[1]='ge';
			$arr_orig_ref[2]='ex';
			$arr_orig_ref[3]='le';
			$arr_orig_ref[4]='nu';
			$arr_orig_ref[5]='de';
			$arr_orig_ref[6]='jos';
			$arr_orig_ref[7]='jud';
			$arr_orig_ref[8]='ru';
			$arr_orig_ref[9]='1sa';
			$arr_orig_ref[10]='2sa';
			$arr_orig_ref[11]='1ki';
			$arr_orig_ref[12]='2ki';
			$arr_orig_ref[13]='1ch';
			$arr_orig_ref[14]='2ch';
			$arr_orig_ref[15]='ezr';
			$arr_orig_ref[16]='ne';
			$arr_orig_ref[17]='es';
			$arr_orig_ref[18]='job';
			$arr_orig_ref[19]='ps';
			$arr_orig_ref[20]='pr';
			$arr_orig_ref[21]='ec';
			$arr_orig_ref[22]='so';
			$arr_orig_ref[23]='isa';
			$arr_orig_ref[24]='jer';
			$arr_orig_ref[25]='la';
			$arr_orig_ref[26]='eze';
			$arr_orig_ref[27]='da';
			$arr_orig_ref[28]='ho';
			$arr_orig_ref[29]='joe';
			$arr_orig_ref[30]='am';
			$arr_orig_ref[31]='ob';
			$arr_orig_ref[32]='jon';
			$arr_orig_ref[33]='mic';
			$arr_orig_ref[34]='na';
			$arr_orig_ref[35]='hab';
			$arr_orig_ref[36]='zep';
			$arr_orig_ref[37]='hag';
			$arr_orig_ref[38]='zec';
			$arr_orig_ref[39]='mal';
			$arr_orig_ref[40]='mt';
			$arr_orig_ref[41]='mr';
			$arr_orig_ref[42]='lu';
			$arr_orig_ref[43]='joh';
			$arr_orig_ref[44]='ac';
			$arr_orig_ref[45]='ro';
			$arr_orig_ref[46]='1co';
			$arr_orig_ref[47]='2co';
			$arr_orig_ref[48]='ga';
			$arr_orig_ref[49]='eph';
			$arr_orig_ref[50]='php';
			$arr_orig_ref[51]='col';
			$arr_orig_ref[52]='1th';
			$arr_orig_ref[53]='2th';
			$arr_orig_ref[54]='1ti';
			$arr_orig_ref[55]='2ti';
			$arr_orig_ref[56]='tit';
			$arr_orig_ref[57]='phm';
			$arr_orig_ref[58]='heb';
			$arr_orig_ref[59]='jas';
			$arr_orig_ref[60]='1pe';
			$arr_orig_ref[61]='2pe';
			$arr_orig_ref[62]='1jo';
			$arr_orig_ref[63]='2jo';
			$arr_orig_ref[64]='3jo';
			$arr_orig_ref[65]='jude';
			$arr_orig_ref[66]='re';	
			echo '<div class="zef_reference_image"><img src="'.$item->str_ref_default_image.'"></div>';
			foreach($item->arr_references as $obj_References)
			{ 
				$arr_single_ref = preg_split('/;/',$obj_References->reference);
				if($item->flg_reference_words)
				{
					echo '<div class="zef_reference_word">'.$obj_References->word.'</div>';
				}
				foreach($arr_single_ref as $obj_single_ref)
				{
					$item->int_Bible_Book_ID = 1;
					for($x = 1; $x <= 66; $x++)
					{
						if(preg_match('/\b('.$arr_orig_ref[$x].')\b/',$obj_single_ref))
						{
							$item->int_Bible_Book_ID = $x;
							break;
						}
					}				
					$str_bible_book_abr = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_ABR_'.$item->int_Bible_Book_ID);
					$str_bible_single_ref = preg_replace( "/\b(".$arr_orig_ref[$item->int_Bible_Book_ID].")\b/", $str_bible_book_abr, $obj_single_ref );
					$arr_bible_chapter = preg_split('/:/', preg_replace( "#".$str_bible_book_abr."(\s)?#", '', $str_bible_single_ref ));
					$item->int_Bible_Chapter = $arr_bible_chapter[0];
					$arr_bible_verses = preg_split('/,/',$arr_bible_chapter[1]);
	
					$str_end_chap = 0;
					$str_end_verse = 0;
					$str_verse = '';
					echo '<div class="zef_reference_modal">';
					foreach($arr_bible_verses as $obj_bible_verses)
					{			
						if(preg_match('/-/',$obj_bible_verses))
						{
							$arr_verses_split = preg_split('/-/',$obj_bible_verses);
							$str_start_verse = $arr_verses_split[0];
							$str_end_verse = $arr_verses_split[1];
						}
						else
						{
							$str_start_verse = $obj_bible_verses;	
						}				
						$str_bible_verse_full = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".$item->int_Bible_Chapter.":".$str_start_verse;
						if($str_end_verse != 0)
						{
							$str_bible_verse_full .= '-'.$str_end_verse;
						}											
						$arr_verse = $mdl_default->fnc_make_verse($item->str_Bible_Version,$item->int_Bible_Book_ID,$item->int_Bible_Chapter,$str_start_verse,$str_end_verse);
						echo '<div class="zef_content_title">'.$str_bible_verse_full."</div>";
						$x = 1;
						echo '<div class="zef_reference">';
						foreach ($arr_verse as $obj_verse)
						{
							if ($x % 2 )
							{
								echo '<div class="odd">';
							}
							else
							{
								echo '<div class="even">';
							}
							if($str_end_verse != 0)
							{
								echo '<div class="zef_content_verse_id" >'.$obj_verse->verse_id.'</div>';
							}
	
							$obj_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $obj_verse->verse);						
							echo '<div class="zef_content_verse">'.$obj_verse->verse."</div>";
							echo '<div style="clear:both"></div>';
							echo '</div>';
							$x++;
						}		
							
						$str_url = "index.php?option=com_zefaniabible&bible=".$item->str_Bible_Version."&view=standard&book=".
								$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter)."-chapter&Itemid=".$item->str_view_plan;	
						if($item->flg_show_commentary)
						{
							$str_url .=  "&com=".$item->str_primary_commentary;
						}
						if($item->flg_reference_chapter_link)
						{
							echo "<div class='zef_content_verse_link'><a title='".JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_GOTO_CHAPTER')." ".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".$item->int_Bible_Chapter."' id='zef_links' href='".JRoute::_($str_url)."' target='_parent'>".JText::_("ZEFANIABIBLE_BIBLE_REFERENCE_GOTO_CHAPTER")."</a></div>";
						}
						echo '<div style="clear:both"></div>';					
					echo '</div>';					
					}
					echo '</div>';
				}
			}
		
		//Filters
		$this->assignRef('item',	$item);
		parent::display($tpl);
	}
	private function fnc_Make_Scripture(&$arr_matches)
	{
		$temp = 'dict='.$this->str_primary_dictionary.'&number='.trim(strip_tags($arr_matches[0]));
		$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" >';		
		$str_verse = $str_verse. trim(strip_tags($arr_matches[0]));			
		$str_verse = $str_verse. '</a> ';
		return $str_verse;
	}	
}