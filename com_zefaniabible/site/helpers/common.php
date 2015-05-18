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
class ZefaniabibleCommonHelper
{
	public function fnc_load_languages()
	{
		// make english strings
		$jlang = JFactory::getLanguage();
		$jlang->load('com_zefaniabible', JPATH_BASE, 'en-GB', true);
		for($i = 1; $i <=66; $i++)
		{
			$arr_english_book_names[$i] = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$i);
		}
		$jlang->load('com_zefaniabible', JPATH_BASE, null, true);
		return $arr_english_book_names;
	}
	public function fnc_redirect_last_day($item)
	{
		if($item->int_day_number > $item->int_max_days)
		{
			$str_redirect_url = "index.php?option=com_zefaniabible&view=".$item->str_view."&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$str_yesterday;
			if($item->str_tmpl == "component")
			{
				$str_redirect_url .= "&tmpl=component";
			}			
			$str_redirect_url = JRoute::_($str_redirect_url);
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$str_redirect_url); 			
		}
	}
	public function fnc_redirect_last_chapter($item)
	{		
		// redirect to last chapter
		if($item->int_Bible_Chapter > $item->int_max_chapter)
		{
			if($item->str_view =='standard')
			{
				$str_redirect_url = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID.'-'.strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".$item->int_max_chapter.'-chapter';
			}
			else
			{
				$str_redirect_url = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&bible2=".$item->str_Second_Bible_Version."&book=".$item->int_Bible_Book_ID.'-'.strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".$item->int_max_chapter.'-chapter';				
			}
			
			if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
			{
				$str_redirect_url .= "&com=".$item->str_primary_commentary;
			}
			if($item->str_tmpl == "component")
			{
				$str_redirect_url .= "&tmpl=component";
			}
			if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
			{
				$str_redirect_url .= "&dict=".$item->str_primary_dictionary;
			}
			if($item->flg_use_strong == 1)
			{
				$str_redirect_url .= "&strong=".$item->flg_use_strong;
			}			
			$str_redirect_url = JRoute::_($str_redirect_url);
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$str_redirect_url); 		
		}				
	}
	public function fnc_meta_data($item)
	{
		// add breadcrumbs
		$app_site = JFactory::getApplication();
		$pathway = $app_site->getPathway();		
		$doc_page = JFactory::getDocument();	
		//$attribs_atom = '';
		$href_atom = '';
		$str_descr = '';
		switch ($item->str_view)
		{
			case 'standard':
				$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.' - '.$item->str_Bible_Version;			
				if($item->str_meta_key == "")
				{
					$doc_page->setMetaData( 'keywords', $str_title.",".$item->str_Bible_Version.", ".$item->str_bible_name );
				}
				else
				{
					$doc_page->setMetaData( 'keywords', $item->str_meta_key );
				}
				$pathway->addItem(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter." - ".$item->str_Bible_Version, JFactory::getURI()->toString());
				$href_rss = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&variant=rss'; 				
				$href_atom = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&variant=atom'; 
				break;			

			case 'compare':
				$str_title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter.' - '.$item->str_Main_Bible_Version.', '. $item->str_Second_Bible_Version;				
				if($item->str_meta_key == "")
				{
					$doc_page->setMetaData( 'keywords', $str_title.",".$item->str_Bible_Version.", ".$item->str_bible_name_1 .", ".$item->str_bible_name_2);				
				}
				else
				{
					$doc_page->setMetaData( 'keywords', $item->str_meta_key );
				}
				$pathway->addItem(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$item->int_Bible_Chapter." - ".$item->str_Bible_Version, JFactory::getURI()->toString());
				$href_rss = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&variant=rss'; 				
				$href_atom = 'index.php?option=com_zefaniabible&view=biblerss&format=raw&bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&variant=atom'; 
				break;
				
			case 'reading':	
				$pathway->addItem(($item->str_reading_plan_name." - ". mb_strtoupper($item->str_Bible_Version)." - ".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$item->int_day_number), JFactory::getURI()->toString());					
				$href_rss = 'index.php?option=com_zefaniabible&view=readingrss&format=raw&plan='.$item->str_reading_plan.'&bible='.$item->str_Bible_Version.'&day='.$item->int_day_number."&variant=seperate"; 
				$str_title = $item->str_reading_plan_name." | ". mb_strtoupper($item->str_Bible_Version)." | ".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$item->int_day_number;						
				break;
				
			case 'plan':
				$pathway->addItem(($item->str_reading_plan_name." - ". mb_strtoupper($item->str_Bible_Version)), JFactory::getURI()->toString());					
				$href_rss = 'index.php?option=com_zefaniabible&view=planrss&format=raw&plan='.$item->str_Bible_Version.'&bible='.$item->str_Bible_Version.'&start='.$item->arr_pagination->limitstart.'&items='.$item->arr_pagination->limit.'&variant=rss'; 
				$href_atom = 'index.php?option=com_zefaniabible&view=planrss&format=raw&plan='.$item->str_Bible_Version.'&bible='.$item->str_Bible_Version.'&start='.$item->arr_pagination->limitstart.'&items='.$item->arr_pagination->limit.'&variant=atom'; 
				$str_title = $item->str_reading_plan_name.' | '. ($item->arr_pagination->limitstart+1).'-'.($item->arr_pagination->limitstart + $item->arr_pagination->limit).' '.JText::_('ZEFANIABIBLE_READING_PLAN_DAY');		
				break;
				
			default:
			 	break;
		}
				
		//RSS RSS 2.0 Feed

		$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0'); 
		$doc_page->addHeadLink( JRoute::_($href_rss, false), 'alternate', 'rel', $attribs );
		//Atom Feed

		$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0'); 
		$doc_page->addHeadLink( JRoute::_($href_atom, false), 'alternate', 'rel', $attribs );		
				
		$str_descr = trim(mb_substr($item->str_description,0,146))." ..."; 
		if($item->str_meta_desc == "")
		{
			$doc_page->setMetaData( 'description', strip_tags($str_descr));
			$doc_page->setMetaData( 'og:description', strip_tags($str_descr) );
		}
		else
		{
			$doc_page->setMetaData( 'description', strip_tags($item->str_meta_desc));
			$doc_page->setMetaData( 'og:description', strip_tags($item->str_meta_desc) );
		}
		$doc_page->setTitle($str_title);
					
		// Facebook Open Graph
		$doc_page->setMetaData( 'og:title', $str_title);	
		$doc_page->setMetaData( 'og:url', JFactory::getURI()->toString());		
		$doc_page->setMetaData( 'og:type', "article" );	
		$doc_page->setMetaData( 'og:image', JURI::root().$item->str_default_image );	
		$doc_page->setMetaData( 'og:site_name', $app_site->getCfg('sitename') );	
		if($item->str_tmpl == "component")
		{
			$doc_page->addCustomTag( '<meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />');	
		}		
	}
	public function fnc_Pagination_Buttons($item)
	{	
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";
		$str_other_url_var = '';
		
/*		if(($item->flg_show_commentary)and(count($this->arr_commentary_list) > 1))
		{
				$str_other_url_var .= "&com=".$item->str_commentary;
		}*/
		if($item->str_tmpl == "component")
		{
			$str_other_url_var .= "&tmpl=component";
		}
	
/*		if(($item->flg_show_dictionary)and(count($this->arr_dictionary_list) > 1))
		{
			$str_other_url_var .= "&dict=".$this->str_curr_dict;
		}*/
		if($item->flg_use_strong == 1)
		{
			$str_other_url_var .= "&strong=".$item->flg_use_strong;
		}
		if($item->int_Bible_Book_ID > 1)
		{
			$url[3] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".($item->int_Bible_Book_ID-1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID-1)]))."&chapter=1-chapter".$str_other_url_var;
			if($item->str_view == 'compare')
			{
				$url[3] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&bible2=".$item->str_Second_Bible_Version."&book=".($item->int_Bible_Book_ID-1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID-1)]))."&chapter=1-chapter".$str_other_url_var;				
			}
			$url[3] = JRoute::_($url[3]);
			if($item->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK').'" type="button" id="zef_Buttons" class="zef_lastBook" name="lastBook" onclick="'.$urlPrepend.$url[3].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($item->int_Bible_Book_ID-1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_BOOK')."' id='zef_links' href='".$url[3]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".($item->int_Bible_Book_ID-1)).' 1'."</a> ";
			}
		}
		if($item->int_Bible_Chapter > 1)
		{
			$url[1] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter-1)."-chapter".$str_other_url_var;	
			if($item->str_view == 'compare')
			{
				$url[1] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&bible2=".$item->str_Second_Bible_Version."&book=".$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter-1)."-chapter".$str_other_url_var;	
			}
			$url[1] = JRoute::_($url[1]);
			if($item->flg_show_pagination_type == 0)
			{
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER').'" type="button" id="zef_Buttons" class="zef_lastChapter" name="lastChapter" onclick="'.$urlPrepend.$url[1].$urlPostpend.'"  value="'. JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$item->int_Bible_Book_ID)." ".($item->int_Bible_Chapter-1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_CHAPTER')."' id='zef_links' href='".$url[1]."'>".JText::_("ZEFANIABIBLE_BIBLE_BOOK_NAME_".$item->int_Bible_Book_ID).' '.($item->int_Bible_Chapter-1)."</a> ";
			}
		}
		if($item->int_Bible_Chapter < $item->int_max_chapter)
		{
			$url[0] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter+1)."-chapter".$str_other_url_var;	
			if($item->str_view == 'compare')
			{
				$url[0] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&bible2=".$item->str_Second_Bible_Version."&book=".$item->int_Bible_Book_ID."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$item->int_Bible_Book_ID]))."&chapter=".($item->int_Bible_Chapter+1)."-chapter".$str_other_url_var;
			}
			$url[0] = JRoute::_($url[0]);
			
			if($item->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER').'" type="button" id="zef_Buttons" class="zef_NextChapter" name="nextChapter" onclick="'.$urlPrepend.$url[0].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID)." ".($item->int_Bible_Chapter+1).'" />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_CHAPTER')."' id='zef_links' href='".$url[0]."'>".JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$item->int_Bible_Book_ID).' '.($item->int_Bible_Chapter+1)."</a> ";
			}
		}
		if($item->int_Bible_Book_ID < 66)
		{
			$url[2] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&book=".($item->int_Bible_Book_ID+1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID+1)]))."&chapter=1-chapter".$str_other_url_var;
			if($item->str_view == 'compare')
			{
				$url[2] = "index.php?option=com_zefaniabible&view=".$item->str_view."&bible=".$item->str_Bible_Version."&bible2=".$item->str_Second_Bible_Version."&book=".($item->int_Bible_Book_ID+1)."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[($item->int_Bible_Book_ID+1)]))."&chapter=1-chapter".$str_other_url_var;
			}
			$url[2] = JRoute::_($url[2]);
			
			if($item->flg_show_pagination_type == 0)
			{			
				echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK').'" type="button" id="zef_Buttons" class="zef_NextBook" name="nextBook" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($item->int_Bible_Book_ID+1)).' 1"'.' />';
			}
			else
			{
				echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_BOOK')."' id='zef_links' href='".$url[2]."'>". JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.($item->int_Bible_Book_ID+1)).' 1'."</a>";
			} 
		}
	}
	public function fnc_Pagination_Buttons_day($item)
	{
		$urlPrepend = "document.location.href=('";
		$urlPostpend = "')";	
		$str_other_url_var = '';	
		if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
		{
				$str_other_url_var .= "&com=".$item->str_commentary;
		}
		if($item->str_tmpl == "component")
		{
			$str_other_url_var .=  "&tmpl=component";
		}
		if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
		{
			$str_other_url_var .=  "&dict=".$item->str_curr_dict;
		}
		if($item->flg_use_strong ==1)
		{
			$str_other_url_var .= "&strong=".$item->flg_use_strong;
		}		
		// fix days yesterday's day when less than 1
		if($item->int_day_number <= 1)
		{
			$str_yesterday = $item->int_max_days;
		}
		else
		{
			$str_yesterday = ($item->int_day_number-1);
		}
		
		// make yesterday's link/button
		$url[2] = "index.php?option=com_zefaniabible&view=".$item->str_view."&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$str_yesterday.$str_other_url_var;
	
		$url[2] = JRoute::_($url[2]);			
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_LAST_DAY_READING').'" type="button" id="zef_Buttons" class="zef_last_day" name="lastday" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$str_yesterday.'" />';
		}
		else
		{
			echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_LAST_DAY_READING')."' id='zef_links' href='".$url[2]."'>".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$str_yesterday."</a> ";
		}
		
		// make today's text or disabled button
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('').'" type="button" id="zef_Buttons" disabled="disabled" class="zef_today" name="today" onclick="'.$urlPrepend.$url[2].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.($item->int_day_number).'" />';		
		}
		else
		{
			echo JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".($item->int_day_number);			
		}
		
		// fix tommorow when greater than max days in plan
		if($item->int_day_number >= $item->int_max_days)
		{
			$int_tommorow = 1;
		}
		else
		{
			$int_tommorow = ($item->int_day_number+1);
		}
		
		//make tomorow's link/button
		$url[3] = "index.php?option=com_zefaniabible&view=".$item->str_view."&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$int_tommorow.$str_other_url_var;	
		$url[3] = JRoute::_($url[3]);	
		
		if($item->flg_show_pagination_type == 0)
		{
			echo '<input title="'.JText::_('ZEFANIABIBLE_BIBLE_NEXT_DAY_READING').'" type="button" id="zef_Buttons" class="zef_next_day" name="nextday" onclick="'.$urlPrepend.$url[3].$urlPostpend.'"  value="'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$int_tommorow.'" />';
		}
		else
		{
			echo "<a title='".JText::_('ZEFANIABIBLE_BIBLE_NEXT_DAY_READING')."' id='zef_links' href='".$url[3]."'>".JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$int_tommorow."</a> ";
		}
	}	
	public function fnc_find_bible_name($arr_Bibles, $str_Bible_Version)
	{
		$str_bible_name = '';
		foreach($arr_Bibles as $obj_bibles)
		{
			if($str_Bible_Version == $obj_bibles->alias)
			{
				$str_bible_name = $obj_bibles->bible_name;
			}
		}
		return $str_bible_name;
	}
	public function fnc_find_reading_name($arr_reading, $str_reading_plan)
	{
		$str_reading_name = '';
		foreach($arr_reading as $obj_plan)
		{
			if($str_reading_plan == $obj_plan->alias)
			{
				$str_reading_name = $obj_plan->name;
			}
		}
		return $str_reading_name;
	}		
	public function fnc_dictionary_dropdown($item)
	{
		$obj_dropdown = '';
		foreach($item->arr_dictionary_list as $obj_dictionary)
		{
			if($item->str_curr_dict == $obj_dictionary->alias)
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'" selected>'.$obj_dictionary->name.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_dropdown = $obj_dropdown.'<option value="'.$obj_dictionary->alias.'">'.$obj_dictionary->name.'</option>'.PHP_EOL;
			}
		}
		return $obj_dropdown;	
	}
	public function fnc_bible_book_dropdown($item)
	{
		$obj_Book_Dropdown = '';
		$obj_Book_Dropdown .= '<optgroup id="oldTest" label="'.JText::_('ZEFANIABIBLE_BIBLE_OLD_TEST').'">'.PHP_EOL;
		
		for($x = 1; $x <=66; $x++)
		{
			if($item->int_Bible_Book_ID == $x)
			{
				$obj_Book_Dropdown .= '		<option value="'.$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).'" selected>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>'.PHP_EOL;						
			}
			else
			{
				$obj_Book_Dropdown .= '		<option value="'.$x."-".strtolower(str_replace(" ","-",$item->arr_english_book_names[$x])).'" >'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>'.PHP_EOL;				
			}
			if($x == 39)
			{
				$obj_Book_Dropdown .= '</optgroup>'.PHP_EOL.'<optgroup id="newTest" label="'.JText::_('ZEFANIABIBLE_BIBLE_NEW_TEST').'">'.PHP_EOL;
			}
		}
		$obj_Book_Dropdown .= '</optgroup>';
		return $obj_Book_Dropdown;
	}
	public function fnc_bible_chapter_dropdown($item)
	{
		$obj_Chap_Dropdown = '';
		for( $x = 1; $x <= $item->int_max_chapter; $x++)
		{
			if($x == $item->int_Bible_Chapter)
			{
				$obj_Chap_Dropdown .= '		<option value="'.$x.'-chapter" selected="selected">'.$x.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_Chap_Dropdown .= '		<option value="'.$x.'-chapter">'.$x.'</option>'.PHP_EOL;
			}
		}
		return $obj_Chap_Dropdown;
	}
	public function fnc_bible_name_dropdown($arr_Bibles,$str_Bible_Version)
	{
		$obj_Bible_Dropdown = '';
		foreach($arr_Bibles as $obj_Bible)
		{
			// Error blank alias found
			if($obj_Bible->alias == '')
			{
				JError::raiseWarning('',str_replace('%s','<b>'.$obj_Bible->bible_name.'</b>',JText::_('ZEFANIABIBLE_ERROR_BLANK_ALIAS_BIBLE')));
			}	
			if($str_Bible_Version == $obj_Bible->alias)
			{
				$obj_Bible_Dropdown .= '		<option value="'.$obj_Bible->alias.'" selected>'.$obj_Bible->bible_name.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_Bible_Dropdown .= '		<option value="'.$obj_Bible->alias.'" >'.$obj_Bible->bible_name.'</option>'.PHP_EOL;
			}
		}	
		return $obj_Bible_Dropdown;	
	}
	public function fnc_commentary_drop_down($item)
	{
		$obj_commentary_dropdown = '';
		foreach($item->arr_commentary_list as $obj_comm_list)
		{
			if($obj_comm_list->alias == "")
			{
				JError::raiseWarning('',str_replace('%s','<b>'.$obj_comm_list->title.'</b>',JText::_('ZEFANIABIBLE_ERROR_BLANK_ALIAS_COMMENTARY')));
			}
			if($item->str_commentary == $obj_comm_list->alias)
			{
				$obj_commentary_dropdown .= '			<option value="'.$obj_comm_list->alias.'" selected>'.$obj_comm_list->title.'</option>'.PHP_EOL;
			}
			else
			{
				$obj_commentary_dropdown .= '			<option value="'.$obj_comm_list->alias.'">'.$obj_comm_list->title.'</option>'.PHP_EOL;
			}
		}
		return $obj_commentary_dropdown;
	}
	public function fnc_reading_plan_drop_down($item)
	{
		$str_dropdown = '';
		foreach($item->arr_reading_plan_list as $readingplan)
		{
			if($item->str_reading_plan == $readingplan->alias)
			{
				$str_dropdown .= '			<option value="'.$readingplan->alias.'" selected>'.$readingplan->name.'</option>'.PHP_EOL;
			}
			else
			{
				$str_dropdown .= '			<option value="'.$readingplan->alias.'" >'.$readingplan->name.'</option>'.PHP_EOL;
			}
		}
		return $str_dropdown;		
	}
	public function fnc_output_single_chapter($item)
	{
		$x = 0;
		$str_Chapter_Output = '';
		foreach ($item->arr_Chapter as $arr_verse)
		{
			if($item->flg_show_dictionary == 1)
			{
				$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
				if($item->flg_use_strong == 1)
				{
					$arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);
					$arr_verse->verse = preg_replace('/{dict-alias}/iu',$item->str_curr_dict,$arr_verse->verse);
					$arr_verse->verse = preg_replace('/{dict-width}/iu',$item->str_dictionary_width,$arr_verse->verse);
					$arr_verse->verse = preg_replace('/{dict-height}/iu',$item->str_dictionary_height,$arr_verse->verse);
				}
				else
				{
					$arr_verse->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','',$arr_verse->verse);
				}
			}
			if ($x % 2)	
			{
				$str_Chapter_Output .= '<div class="odd">';
			}
			else
			{
				$str_Chapter_Output  .= '<div class="even">'; 
			}
			
			$str_Chapter_Output  .= "<div class='zef_verse_number'>".$arr_verse->verse_id."</div><div class='zef_verse'>".$arr_verse->verse."</div>";
			
			if($item->flg_show_references)
			{
				foreach($item->arr_references as $obj_references)
				{
					if($obj_references->verse_id == $arr_verse->verse_id)
					{
						$temp = 'bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&verse='.$arr_verse->verse_id;
						$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
						$str_Chapter_Output  .= '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';	
						break;
					}
				}
			}		
			
			if($item->flg_show_commentary)
			{
				foreach($item->arr_commentary as $int_verse_commentary)
				{
					if($arr_verse->verse_id == $int_verse_commentary->verse_id)
					{
						$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$item->int_Bible_Book_ID."&chapter=".$item->int_Bible_Chapter."&verse=".$arr_verse->verse_id."&tmpl=component");
						$str_Chapter_Output  .= '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
					}
				}
			}			
			$str_Chapter_Output  .= '<div style="clear:both"></div></div>';
			$x++;
		}			
		if( $str_Chapter_Output == "")
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_ERROR_CHAPTER_NOT_FOUND'));
		}
		return $str_Chapter_Output;		
	}
	public function fnc_output_dual_chapter($item)
	{
		$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
		$a = 1;
		$b = 1;
		$str_Chapter_Output = '';
		$temp   = '';
		$temp2  = '';
		foreach($item->arr_Chapter_1 as $arr_verse)
		{
			if($item->flg_show_dictionary)
			{
				if($item->flg_use_strong)
				{
					$arr_verse->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse->verse);	
					$arr_verse->verse = preg_replace('/{dict-alias}/iu',$item->str_curr_dict,$arr_verse->verse);
					$arr_verse->verse = preg_replace('/{dict-width}/iu',$item->str_dictionary_width,$arr_verse->verse);
					$arr_verse->verse = preg_replace('/{dict-height}/iu',$item->str_dictionary_height,$arr_verse->verse);	
				}
				else
				{
					$arr_verse->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','',$arr_verse->verse);
				}		
			}
			$temp[$a] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse->verse_id.'</div><div class="zef_verse">'.$arr_verse->verse.'</div></div>';		
			$a++;
		}
		foreach($item->arr_Chapter_2 as $arr_verse2)
		{
			if($item->flg_show_dictionary)
			{
				if($item->flg_use_strong)
				{				
					$arr_verse2->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $arr_verse2->verse);	
					$arr_verse2->verse = preg_replace('/{dict-alias}/iu',$item->str_curr_dict,$arr_verse2->verse);
					$arr_verse2->verse = preg_replace('/{dict-width}/iu',$item->str_dictionary_width,$arr_verse2->verse);
					$arr_verse2->verse = preg_replace('/{dict-height}/iu',$item->str_dictionary_height,$arr_verse2->verse);		
				}
				else
				{
					$arr_verse2->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','',$arr_verse2->verse);
				}	
			}
			$temp2[$b] = '<div class="zef_compare_bibles">'.'<div class="zef_verse_number">'.$arr_verse2->verse_id.'</div><div class="zef_verse">'.$arr_verse2->verse.'</div></div>';
			$b++;				
		}
		
		for($x=1; $x < $item->int_max_verse; $x++)
		{
			if ($x % 2)
			{
				$str_Chapter_Output  .= '<div class="odd">';
			}
			else
			{
				$str_Chapter_Output  .= '<div class="even">'; 
			}			
			for($y = 1; $y <= 2; $y++)
			{
				if($y %2 )
				{
					$str_Chapter_Output .= '<div class="zef_bible_1">'.$temp[$x].'</div>';
				}
				else
				{
					$str_Chapter_Output  .= '<div class="zef_bible_2">'.$temp2[$x].'</div>';
					if($item->flg_show_references)
					{
						foreach($item->arr_references as $obj_references)
						{
							if($obj_references->verse_id == $x)
							{
								$temp_link = 'bible='.$item->str_Bible_Version.'&book='.$item->int_Bible_Book_ID.'&chapter='.$item->int_Bible_Chapter.'&verse='.$x;
								$str_pre_link = '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'.$temp_link.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
								$str_Chapter_Output  .= '<div class="zef_reference_hash">'.$str_pre_link.JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a></div>';	
								break;
							}
						}
					}				
					if($item->flg_show_commentary)
					{
						foreach($item->arr_commentary as $int_verse_commentary)
						{
							if($x == $int_verse_commentary->verse_id)
							{
								$str_commentary_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$item->int_Bible_Book_ID."&chapter=".$item->int_Bible_Chapter."&verse=".$x."&tmpl=component");
								$str_Chapter_Output  .= '<div class="zef_commentary_hash"><a href="'.$str_commentary_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">'.JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a></div>";
							}
						}
					}						
				}
				
			}
			$str_Chapter_Output  .= '<div style="clear:both"></div></div>';			
		}
		return $str_Chapter_Output;
	}
	public function fnc_check_strong_bible($arr_chapter)
	{
		$flg_is_strong = 0;
		$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";
		$x=1;
		foreach($arr_chapter as $obj_chapter)
		{
			if(preg_match($str_match_fuction,$obj_chapter->verse))
			{
				$flg_is_strong = 1;
				break;
			}
			// only check 1st 5 verses
			if($x>4)
			{
				break;	
			}
			
			$x++;
		}
		return $flg_is_strong;
	}
	public function fnc_Make_Scripture(&$arr_matches)
	{
		$str_verse='';
		$temp = 'dict={dict-alias}&item='.trim(strip_tags($arr_matches[0]));
		$str_verse = ' <a id="zef_strong_link" title="'. JText::_('COM_ZEFANIA_BIBLE_STRONG_LINK').'" target="blank" href="index.php?view=strong&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:{dict-width},y:{dict-height}}}">';		
		$str_verse .=  trim(strip_tags($arr_matches[0]));			
		$str_verse .= '</a> ';
		return $str_verse;
	}
	public function fnc_make_meta_desc($arr_meta)
	{
		foreach ($arr_meta as $meta)
		{
			$str_metadesc = trim($meta->metadesc);
		}	
		return $str_metadesc;
	}
	public function fnc_make_meta_key($arr_meta)
	{
		foreach ($arr_meta as $meta)
		{
			$str_metakey = trim($meta->metakey);
		}	
		return $str_metakey;
	}	
	public function fnc_make_description($arr_chapter)
	{
		$str_descr = '';
		foreach ($arr_chapter as $arr_verse)
		{
			if($arr_verse->verse_id <= 1)
			{
				$str_descr .= " ".trim($arr_verse->verse);
				break;
			}
		}
		return $str_descr;
	}
	public function fnc_calcualte_day_diff($str_start_reading_date, $int_max_days, $int_month=0, $int_year=0, $int_day=0)
	{
		// time zone offset.
 		$config = JFactory::getConfig();
		if(($int_year > 2000)and($int_month> 0))
		{
			$JDate = JFactory::getDate($int_year.'-'.$int_month.'-'.$int_day, new DateTimeZone($config->get('offset')));	
		}else{
			$JDate = JFactory::getDate('now', new DateTimeZone($config->get('offset')));
		}
		$str_today = $JDate->format('Y-m-d', true);
		$arr_today = new DateTime($str_today);	
		$arr_start_date = new DateTime($str_start_reading_date);	
		$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;

		$int_verse_remainder = $int_day_diff % $int_max_days;
		if($int_verse_remainder == 0)
		{
			$int_verse_remainder = $int_max_days;
		}
		return $int_verse_remainder;
	}
	public function fnc_output_reading_plan($item)
	{
			$book = 0;
			$chap = 0;
			$x = 1;
			$y = 1;		
			$z = 0;
			$str_chapter = '';
			$int_chap_cnt = 1;
			
			require_once(JPATH_COMPONENT_SITE.'/helpers/audioplayer.php');
			$mdl_audio = new ZefaniaAudioPlayer;

			foreach($item->arr_plan as $reading)
			{
				$cnt_verse_count = count($reading);
				foreach($reading as $plan)
				{	
					if (($plan->book_id > $book)or($plan->chapter_id > $chap))
					{					
						$book = $plan->book_id;
						$chap = $plan->chapter_id;
						
						if($y > 1)
						{
							$str_chapter .=  '</div>';
						}
						$str_chapter .=  '<div class="zef_bible_Header_Label_Plan">';
						$str_chapter .=  	'<h1 class="zef_bible_Header_Label_h1">';
						$str_chapter .=  		'<a name="'.$y.'" id="zef-heading-a"></a>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ";
						$str_chapter .=  		mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id;

						foreach($item->arr_reading as $arr_list_reading)
						{								
							if(($book == $arr_list_reading->book_id) and ($chap == $arr_list_reading->begin_chapter))
							{
								if(($arr_list_reading->begin_verse != 0)and($arr_list_reading->end_verse != 0))
								{
									if($z != $x)
									{
										$str_chapter .= ":".$arr_list_reading->begin_verse."-".$arr_list_reading->end_verse;
									}
									else
									{
										$str_chapter .= ", ".$arr_list_reading->begin_verse."-".$arr_list_reading->end_verse;
									}
									$z = $x;
								}

							}
						}						
						$str_chapter .=  	'</h1>';
						$str_chapter .=  '</div>';
						$str_chapter .=  '<div class="zef_bible_Chapter">';
						if($item->flg_show_audio_player)
						{
							$obj_player = $mdl_audio->fnc_audio_player($item->str_Bible_Version,$plan->book_id,$plan->chapter_id, $y);
							$str_chapter .=  '<div class="zef_player-'.$y.'">';
							$str_chapter .=  $obj_player;
        					$str_chapter .=  "</div>";
							$str_chapter .=  '<div style="clear:both"></div>';
						}
						$x = 1;
						$int_chap_cnt = $y;
						$y++;			
					}

					if ($x % 2)
					{
						$str_chapter .=  '<div class="odd">';
					}
					else
					{
						$str_chapter .=  '<div class="even">'; 
					}
					if($item->flg_show_dictionary == 1)
					{
						$str_match_fuction = "/(?=\S)([HG](\d{1,4}))/iu";					
						if($item->flg_use_strong == 1)
						{
							$plan->verse = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $plan->verse);
							$plan->verse = preg_replace('/{dict-alias}/iu',$item->str_curr_dict,$plan->verse);
							$plan->verse = preg_replace('/{dict-width}/iu',$item->str_dictionary_width,$plan->verse);
							$plan->verse = preg_replace('/{dict-height}/iu',$item->str_dictionary_height,$plan->verse);
						}
						else
						{
							$plan->verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','',$plan->verse);
						}					
					}
					
					$str_chapter .=  "<div class='zef_verse_number'>".$plan->verse_id."</div><div class='zef_verse'>".$plan->verse."</div>";

					if($item->flg_show_references)
					{				
						$str_chapter .=	$this->fnc_create_reference_link($item,$plan->book_id, $plan->chapter_id, $plan->verse_id, $int_chap_cnt);
					}
					if($item->flg_show_commentary)
					{
						$str_chapter .= $this->fnc_create_comentary_link($item, $plan->book_id, $plan->chapter_id, $plan->verse_id, $int_chap_cnt);			
					}
					$str_chapter .=  '<div style="clear:both"></div></div>';		
					$x++;
				}
			}
		return $str_chapter;
	}
	public function fnc_jump_button($item)
	{
		$int_today = $item->int_day_diff;
		$str_other_url_var = '';
		$str_plan_start_date = date('d-m-Y', strtotime("-".$int_today." day"));
				
		echo '<select name="jump" id="zef_day_jump" class="inputbox" onchange="javascript:location.href = this.value;">';
		for($x = 1; $x <= $item->int_max_days; $x++)
		{
			if(($item->flg_show_commentary)and(count($item->arr_commentary_list) > 1))
			{
					$str_other_url_var .= "&com=".$item->str_commentary;
			}
			if($item->str_tmpl == "component")
			{
				$str_other_url_var .=  "&tmpl=component";
			}
			if(($item->flg_show_dictionary)and(count($item->arr_dictionary_list) > 1))
			{
				$str_other_url_var .=  "&dict=".$this->str_curr_dict;
			}
			if($item->flg_use_strong ==1)
			{
				$str_other_url_var .= "&strong=".$item->flg_use_strong;
			}			
			$str_url = "index.php?option=com_zefaniabible&view=".$item->str_view."&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$x.$str_other_url_var;
			$str_url = JRoute::_($str_url);
			echo '	<option value="'.$str_url.'"';			

			if($x == $item->int_day_number )
			{
				echo 'selected';
			}
			echo  '>'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '.$x;
			if($x == $int_today)
			{
				echo " - " .JText::_('COM_ZEFANIABIBLE_TODAY');
			}
			else
			{
				echo " - " .date('d/m/Y', strtotime($str_plan_start_date. "+".$x." day"));
			}
			echo '</option>';
		}
		echo '</select>';
	}
	public function fnc_create_plan_list_output($item)
	{
		$temp_day = 0;
		$str_page_output = '';
		$str_page_output .=  '<div class="odd">';
		$x = 0;
		$y = 0;
		foreach($item->arr_reading as $reading)
		{			
			if($temp_day != $reading->day_number)
			{
				$y = 0;
				$temp_day = $reading->day_number;
				if($x != 0)
				{
					$str_page_output .=  '<div style="clear:both"></div></div>';
					if ($reading->day_number % 2)
					{

						$str_page_output .=  '<div class="odd">';
					}
					else
					{
						$str_page_output .=  '<div class="even">';
					}
					
				}
					$str_page_output .=  '<div class="zef_day_number">'.JText::_('ZEFANIABIBLE_READING_PLAN_DAY')." ".$reading->day_number."</div>";
			}
			$y++;		
			$x++;
			$str_page_output .=  '<div class="zef_reading">';
			$link = '<a title="'.JText::_('ZEFANIABIBLE_VERSE_READING_PLAN_OVERVIEW_CLICK_TITLE').'" href="'.JRoute::_("index.php?option=com_zefaniabible&view=reading&plan=".$item->str_reading_plan."&bible=".$item->str_Bible_Version."&day=".$reading->day_number.'&Itemid='.$item->str_view_plan).'#'.$y.'" target="_self">';
			$str_page_output .=  $link.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$reading->book_id)." ";
			$str_page_output .=  $reading->begin_chapter;
			if(($reading->begin_verse == 0)and($reading->end_verse == 0))
			{
				if($reading->end_chapter != $reading->begin_chapter )
				{
					$str_page_output .=  "-".$reading->end_chapter;
				}
			}
			else
			{
				$str_page_output .=  ":".$reading->begin_verse."-".$reading->end_chapter.":".$reading->end_verse;
			}
			$str_page_output .=  "</a></div>";
		}
		$str_page_output .=  '</div><div style="clear:both"></div>';
		return $str_page_output;
	}
	public function fnc_create_reading_desc($arr_readingplans, $str_reading_plan)
	{
		$str_plan_description = '';
		foreach ($arr_readingplans as $reading_plan)
		{
			if($str_reading_plan == $reading_plan->alias)
			{
				$str_plan_description = JText::_(strip_tags($reading_plan->description)); 
				break;
			}
		}
		return $str_plan_description;
	}
	public function fnc_count_chapters($arr_reading)
	{
		$cnt_chapters = 0;
		foreach($arr_reading as $obj_reading)
		{
			if($obj_reading->begin_chapter != $obj_reading->end_chapter)
			{
				for($e = $obj_reading->begin_chapter; $e <= $obj_reading->end_chapter; $e++)
				{
					$cnt_chapters++;
				}
			}
			else
			{
				$cnt_chapters++;
			}
		}
		return $cnt_chapters;
	}
	public function fnc_create_comentary_link($item, $int_book_id, $int_chapter_id, $int_verse_id, $int_cnt_chap)
	{
		$str_output = '';

		$str_output .=  '<div class="zef_commentary_hash">';
		foreach ($item->arr_commentary[($int_cnt_chap-1)] as $arr_chapter)
		{
			if($arr_chapter->verse_id == $int_verse_id)
			{
				$str_url = JRoute::_("index.php?option=com_zefaniabible&view=commentary&com=".$item->str_commentary."&book=".$int_book_id.
									"&chapter=".$int_chapter_id."&verse=".$int_verse_id."&tmpl=component");

				$str_output .=  '<a href="'.$str_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
				$str_output .=  JText::_('ZEFANIABIBLE_BIBLE_COMMENTARY_LINK')."</a>";
				break;
			}
		}
		$str_output .=  "</div>";		
		return $str_output;
	}
	public function fnc_create_reference_link($item,$int_book_id,$int_chapter_id,$int_verse_id, $int_cnt_chap)
	{
		$str_output = '';
		$str_output .= '<div class="zef_reference_hash">';
		foreach ($item->arr_references[($int_cnt_chap-1)] as $arr_chapter)
		{		
			if($arr_chapter->verse_id == $int_verse_id)
			{
				$str_output .= '<a title="'. JText::_('COM_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".'" target="blank" href="index.php?view=references&option=com_zefaniabible&tmpl=component&'
								.'bible='.$item->str_Bible_Version.'&book='.$int_book_id.'&chapter='.$int_chapter_id.'&verse='.$int_verse_id.
								'" class="modal" rel="{handler: \'iframe\', size: {x:'.$item->str_commentary_width.',y:'.$item->str_commentary_height.'}}">';
				$str_output .= JText::_('ZEFANIABIBLE_BIBLE_REFERENCE_LINK').'</a>';
				break;				
			}
		}
		$str_output .= '</div>';		
		return $str_output;
	}
	public function fnc_create_catcha($str_view)
	{
		$flg_catcha_correct = 0;
		$captcha = JCaptcha::getInstance('recaptcha', array('namespace' => $str_view));
		$captcha->initialise($str_view);
		echo $captcha->display('recaptcha', 'recaptcha');	
	}
	public function fnc_check_catcha($str_view)
	{
		$flg_catcha_correct = 0;
		$captcha = JCaptcha::getInstance('recaptcha', array('namespace' => $str_view));
		$captcha->initialise($str_view);
		$flg_catcha_correct = $captcha->checkAnswer($str_view);
		if(!$flg_catcha_correct)
		{
			JError::raiseWarning('',$captcha->getError());	
		}		
		return $flg_catcha_correct;
	}
	public function fnc_validate_email($str_email)
	{
		$flg_email_valid = 0;
		if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $str_email)) 
		{
			$flg_email_valid = 1;
    	}
		else
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_EMAIL_NOT_VALID'));
		}
		return $flg_email_valid;
	}
	public function fnc_validate_date($str_start_reading_date)
	{
		$flg_date_valid = 0;
		// check date fomat dd-mm-yyyy
		
		if (preg_match('/^((0[1-9]|[12][0-9]|3[01])[-](0[1-9]|1[012])[-](19|20)\d\d)$/', $str_start_reading_date))
		{
			$flg_date_valid = 1;
		}
		else
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_DATE_NOT_VALID'));
		}		
		return $flg_date_valid;
	}
	public function sendSignUpEmail($item)
	{ 
		$str_message = str_replace('%n',$item->str_user_name,JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_BODY'))."<br>";
		$str_message .= JText::_('ZEFANIABIBLE_BIBLE_START_READING_DATE').": ".$item->str_start_date."<br>";
		$str_message .= JText::_('ZEFANIABIBLE_BIBLE_VERSION').": ".$item->str_Bible_Version."<br>";
		$str_message .= JText::_('ZEFANIABIBLE_READING_PLAN').": " . $item->str_reading_plan."<br>";
		$str_message .= JText::_('ZEFANIABIBLE_BIBLE_SEND_READING_EMAIL').": ";
		if($item->flg_send_reading) 
		{ 
			$str_message .= JText::_('JYES'); 
		}else{ 
			$str_message .= JText::_('JNO'); 
		} 
		$str_message .= "<br>"; 
		$str_message .= JText::_('ZEFANIABIBLE_BIBLE_SEND_VERSE_EMAIL').": ";
		if($item->flg_send_verse ) 
		{ 
			$str_message .= JText::_('JYES'); 
		}else { 
			$str_message .= JText::_('JNO'); 
		} 
		$str_message .= "<br>";	
		$str_message .= JText::_('ZEFANIABIBLE_IP_ADDRESS').": ". $_SERVER['REMOTE_ADDR']."<br>";	

		$mailer = JFactory::getMailer();
		$str_sender = array($item->str_from_email,$item->str_from_email_name);
		$mailer->setSender($str_sender);
		$mailer->addRecipient($item->str_email);
		$mailer->addBCC($item->str_admin_email);
		$mailer->setSubject(JText::_('ZEFANIABIBLE_SIGNUP_EMAIL_SUBJECT'));
		$mailer->isHTML(true);
		$mailer->Encoding = 'base64';
		$mailer->setBody($str_message);
		$send = $mailer->Send();			
	}
	public function fnc_todays_date()
	{
		$config = JFactory::getConfig();
		$JDate = JFactory::getDate('today', new DateTimeZone($config->get('offset')));
		$str_today = $JDate->format(DATE_RFC822, true);
		return 	$str_today;		
	} 
	public function fnc_scripture_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title, $arr_multi_query,$flg_use_multi_query,$str_passages, $flg_add_title = 0)
	{
		$verse = '';
		$x = 1;
		$int_verse_cnt = count($arr_verses);

		foreach($arr_verses as $obj_verses)
		{	
			switch(true)
			{
				//multi verse query
				case (($flg_use_multi_query)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)):
					foreach($arr_multi_query as $obj_multi_query)
					{
						if(($obj_verses->verse_id == $obj_multi_query[0])and($x==1))
						{

							$verse .= '<div class="zef_content_scripture">';
							if($flg_add_title)
							{
								$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_passages;
								if($flg_add_bible_title)
								{
									$verse .= ' - '.$obj_verses->bible_name;
								}
								$verse .= '</div>';
							}
							$verse .= '<div class="zef_content_verse" >';
						}
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{
						$verse .= '</div></div>';
					}
					$x++;					
					break;									
				// Genesis 1:1
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					$verse = 		'<div class="zef_content_scripture">';
					if($flg_add_title)
					{
						$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse;
						if($flg_add_bible_title)
						{
							$verse .= ' - '.$obj_verses->bible_name;
						}
						$verse .= 	'</div>';
					}
					$verse .= 	'<div class="zef_content_verse"><div class="odd">'.$obj_verses->verse.'</div></div>';
					$verse .= '</div>';					
					break;
					
				// Genesis 1:1-3
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse)and(!$flg_use_multi_query)):
					if($obj_verses->verse_id == $str_begin_verse)
					{
						$verse .= '<div class="zef_content_scripture">';
						if($flg_add_title)
						{
							$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_verse;
							if($flg_add_bible_title)
							{
								$verse .= ' - '.$obj_verses->bible_name;
							}
							$verse .= 	'</div>';
						}
						$verse .= 	'<div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{
						$verse .= '</div></div>';
					}
					$x++;	
					break;	
						
				// Genesis 1		
				case (($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					if($obj_verses->verse_id == '1')
					{
						$verse .= '<div class="zef_content_scripture">';
						if($flg_add_title)
						{
							$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap;
							if($flg_add_bible_title)
							{
								$verse .= ' - '.$obj_verses->bible_name;
							}
							$verse .= 	'</div>';
						}
						$verse .= 	'<div class="zef_content_verse" >';

					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';
					}
					$x++;					
					break;
					
				// Genesis 1-2
				case (($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					if(($obj_verses->verse_id == '1')and($str_begin_chap == $obj_verses->chapter_id))
					{
						$verse .= '<div class="zef_content_scripture">';
						if($flg_add_title)
						{
							$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.'-'.$str_end_chap;
							if($flg_add_bible_title)
							{
								$verse .= ' - '.$obj_verses->bible_name;
							}
							$verse .= 	'</div>';
						}
						$verse .= 	'<div class="zef_content_verse" >';
					}		
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';

					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';		
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';	
					}	
					$x++;				
					break;
					
				// Genesis 1:30-2:3
				case (($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse)and(!$flg_use_multi_query)):
					if(($obj_verses->verse_id == $str_begin_verse)and($str_begin_chap == $obj_verses->chapter_id))
					{
						$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_chap.':'.$str_end_verse;
						if($flg_add_bible_title)
						{
							$title .= ' - '.$obj_verses->bible_name;
						}
						$verse .= '<div class="zef_content_scripture">';
						if($flg_add_title)
						{
							$verse .= 	'<div class="zef_content_title">'.$title.'</div>';
						}
						$verse .= 	'<div class="zef_content_verse" >';							
					}
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse .=  '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$obj_verses->chapter_id.'</div>';
					}							
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';

					}		
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';	
					if($x == $int_verse_cnt)
					{	
						$verse .= '</div></div>';			
					}
					$x++;				
					break;	
				//multi verse query
				case (count($arr_multi_query>1)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):

					if($obj_verses->verse_id == $arr_multi_query[0][0])
					{
						$verse .= '<div class="zef_content_scripture">';
						if($flg_add_title)
						{
							$verse .= 	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).' '.$str_passages;
							if($flg_add_bible_title)
							{
								$verse .= ' - '.$obj_verses->bible_name;
							}
							$verse .= 	'</div>';
						}
						$verse .= 	'<div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse .= '<div class="odd">';
					}
					else
					{
						$verse .= '<div class="even">';
					}
					$verse .= 	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse .= 	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse .= 	'<div style="clear:both"></div>';
					$verse .= '</div>';
					if($x == $int_verse_cnt)
					{
					//	$verse .= '</div></div>';
					}
					$x++;					
					break;					
				default:
					break;	
			}
		}
		
		$verse .= 	'<div style="clear:both"></div>';
		$verse = preg_replace('/(?=\S)([HG](\d{1,4}))/iu','', $verse); // remove strong numbers
		
		return $verse;
	}
	public function fnc_make_scripture_title($int_book_id, $int_begin_chapter, $int_begin_verse, $int_end_chapter, $int_end_verse, $flg_short=0 )
	{
		if($flg_short)
		{
			$str_title =  JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_ABR_'.$int_book_id);
		}else{
			$str_title =  JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$int_book_id);
		}
		switch(true)
		{
			// Genesis 2-3;
			case (($int_begin_verse == 0)and($int_end_verse == 0)and($int_begin_chapter != $int_end_chapter)and($int_end_chapter != 0)):
				$str_title .= " ".$int_begin_chapter."-".$int_end_chapter;
				break;
			
			case (($int_begin_verse != 0)and($int_end_verse != 0)and($int_begin_chapter == $int_end_chapter)): // Genesis 2:2-2:3
			case (($int_begin_verse != 0)and($int_end_verse != 0)and($int_end_chapter == 0)): // Genesis 2:1-3
				$str_title .= " ".$int_begin_chapter.":".$int_begin_verse."-".$int_end_verse;
				break;
			// Genesis 2:3-4:2;
			case (($int_begin_verse != 0)and($int_end_verse != 0)and($int_begin_chapter != $int_end_chapter)):
				$str_title .= " ".$int_begin_chapter.":".$int_begin_verse."-".$int_end_chapter.":".$int_end_verse;
				break;
			// Genesis 2:2
			case (($int_begin_verse != 0)and($int_end_verse == 0)and($int_end_chapter == 0)):
			case (($int_begin_verse != 0)and($int_end_verse == 0)and($int_begin_chapter == $int_end_chapter)):
				$str_title .= " ".$int_begin_chapter.":".$int_begin_verse;
				break;
			// Genesis 1
			default;
				$str_title .= " ".$int_begin_chapter;
				break;
		}
		return $str_title;
	}
	public function fnc_get_month_name($int_month, $int_short = 0)
	{
		if($int_short == 1)
		{
			$str_postpend = "_SHORT";
		}
		else
		{
			$str_postpend = "";
		}
		switch($int_month)
		{
			case 1:
				$str_month_name = JText::_('JANUARY'.$str_postpend);
				break;
			case 2:
				$str_month_name = JText::_('FEBRUARY'.$str_postpend);
				break;				
			case 3:
				$str_month_name = JText::_('MARCH'.$str_postpend);
				break;				
			case 4:
				$str_month_name = JText::_('APRIL'.$str_postpend);
				break;				
			case 5:
				$str_month_name = JText::_('MAY'.$str_postpend);
				break;				
			case 6:
				$str_month_name = JText::_('JUNE'.$str_postpend);
				break;
			case 7:
				$str_month_name = JText::_('JULY'.$str_postpend);
				break;				
			case 8:
				$str_month_name = JText::_('AUGUST'.$str_postpend);
				break;				
			case 9:
				$str_month_name = JText::_('SEPTEMBER'.$str_postpend);
				break;				
			case 10:
				$str_month_name = JText::_('OCTOBER'.$str_postpend);
				break;				
			case 11:
				$str_month_name = JText::_('NOVEMBER'.$str_postpend);
				break;				
			case 0:
			default:
				$str_month_name = JText::_('DECEMBER'.$str_postpend);
				break;	
		}
		return $str_month_name;
	}
	public function fnc_get_day_name($int_day)
	{
		$str_day_name ='';
		switch($int_day)
		{
			case 1:
				$str_day_name =  JText::_('MONDAY');
				break;
			case 2:
				$str_day_name =  JText::_('TUESDAY');			
				break;
			case 3:
				$str_day_name =  JText::_('WEDNESDAY');			
				break;
			case 4:
				$str_day_name =  JText::_('THURSDAY');			
				break;
			case 5:
				$str_day_name =  JText::_('FRIDAY');			
				break;
			case 6:
				$str_day_name =  JText::_('SATURDAY');			
				break;
			case 0:
			default:			
				$str_day_name =  JText::_('SUNDAY');
				break;
		}
		return $str_day_name;
	}	
}
?>