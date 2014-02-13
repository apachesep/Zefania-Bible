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
JHTML::_('behavior.modal');

$cls_bible_reading_plan = new BibleReadingPlan($this->bibles, $this->reading, $this->arr_reading_plans, $this->plan, $this->str_bible_Version, $this->int_day_number);

class BibleReadingPlan
{
		/*
			a = plan
			b = bible
			c = day
		*/

	public function __construct($arr_bibles, $arr_reading, $arr_reading_plans, $arr_plan, $str_bible_Version, $int_day_number)
	{
		$this->arr_reading = $arr_reading;
		$params = JComponentHelper::getParams( 'com_zefaniabible' );		
		$this->doc_page = JFactory::getDocument();	

		$str_primary_reading = 		$params->get('primaryReading', 'ttb');		
		$str_reading_plan = 	JRequest::getCmd('a', $str_primary_reading);		
		$int_feed_type	= 	JRequest::getInt('d', 0);
		$str_bible_name = '';
		foreach ($arr_reading_plans as $plan)
		{
			if($str_reading_plan == $plan->alias)
			{
				$str_curr_read_plan = $plan->name;
				$str_desc = $plan->description;
			}
		}
		foreach ($arr_bibles as $obj_bibles)
		{
			if($obj_bibles->alias == $str_bible_Version)
			{
				$str_bible_name = $obj_bibles->bible_name;
				$str_bible_audio_file = $obj_bibles->xml_audio_url;
			}
		}
		if($int_feed_type == 0)
		{
			$this->fnc_output_seperate($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc);
		}
		elseif($int_feed_type == 1)
		{
			$this->fnc_output_single($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc);	
		}
		else
		{
			$this->fnc_output_podcast($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc, $str_bible_audio_file);	
		}
	}
	private function fnc_output_podcast($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc, $str_bible_audio_file)
	{
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
	
		$str_audio_path = 		$params->get('xmlAudioPath', 'media/com_zefaniabible/audio/');
		$str_admin_email = 		$params->get('adminEmail');
		$str_admin_name = 		$params->get('adminName');
		$book = 0;
		$str_mp3_file = '';
		$arr_verse = array();
		$arr_podcast_output = '';
		$chap = 0;				
		$str_full_path = $str_audio_path.$str_bible_audio_file;
		if(is_file($str_full_path))
		{
			$arr_mp3_files = simplexml_load_file($str_full_path);
		}
		else
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_FIELD_XML_AUDIO_FILE_LOCATION_NOT_VALID'));
		}
		$y = 1;	
		$x = 0;
		echo '<?xml version="1.0" encoding="utf-8" ?>'.PHP_EOL;
		echo '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		echo '  <channel>'.PHP_EOL;
		echo '	<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan.  " - ". $str_bible_name.'</title>'.PHP_EOL;
		echo '	<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;
		echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
		echo '	<description>'.$mainframe->getCfg('MetaDesc').'</description>'.PHP_EOL;
		echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
		echo '	<lastBuildDate>'.date('D, d M Y 00:00:00').'</lastBuildDate>'.PHP_EOL;
		echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
		echo '	<managingEditor>'.$str_admin_email.'('.$str_admin_name.')'.'</managingEditor>'.PHP_EOL;
		echo '	<copyright>(c) '.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
		echo '	<itunes:summary></itunes:summary>'.PHP_EOL;
		echo '	<itunes:author>'.$mainframe->getCfg('sitename').'</itunes:author>'.PHP_EOL;
		echo '	<itunes:category text="Religion &amp; Spirituality" />'.PHP_EOL;
		echo '	<itunes:category text="Religion &amp; Spirituality">'.PHP_EOL;
		echo '		<itunes:category text="Christianity" />'.PHP_EOL;
		echo '	</itunes:category>'.PHP_EOL;
		echo '	<itunes:subtitle></itunes:subtitle>'.PHP_EOL;
		echo '	<itunes:owner>'.PHP_EOL;
		echo '		<itunes:name>'.$str_admin_name.'</itunes:name>'.PHP_EOL;
		echo '		<itunes:email>'.$str_admin_email.'</itunes:email>'.PHP_EOL;
		echo '	</itunes:owner>'.PHP_EOL;
		echo '	<itunes:image href="'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'" />'.PHP_EOL;
		echo '	<image>'.PHP_EOL;
		echo '		<url>'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'</url>'.PHP_EOL;
		echo '		<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan.  " - ". $str_bible_name.'</title>'.PHP_EOL;
		echo '		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;
		echo '		<description>'.$mainframe->getCfg('MetaDesc').'</description>'.PHP_EOL;
		echo '	</image>'.PHP_EOL;
		echo '	<itunes:explicit>no</itunes:explicit>'.PHP_EOL;
		echo '	<itunes:keywords>'.$mainframe->getCfg('MetaKeys').'</itunes:keywords>'.PHP_EOL;
		
		foreach($arr_plan as $reading)
		{
			foreach($reading as $plan)
			{		
				if (($plan->book_id > $book)or($plan->chapter_id > $chap))
				{
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					
					foreach($arr_mp3_files as $obj_mp3_file_book) 
					{	
						if($plan->book_id == $obj_mp3_file_book['id'])
						{
							
							foreach ($obj_mp3_file_book as $obj_mp3_file_chap)
							{
								if($plan->chapter_id == $obj_mp3_file_chap['id'])
								{
									$str_mp3_file = $obj_mp3_file_chap;
									break;
								}
							}
							break;
						}
					}
					$arr_podcast_output[$y] = '	<item>'.PHP_EOL
										  .'		<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id.'</title>'.PHP_EOL
										  .'		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'#'.$y.'</link>'.PHP_EOL
										  .'		<guid>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'#'.$y.'</guid>'.PHP_EOL
										  .'		<dc:creator></dc:creator>'.PHP_EOL
										  .'		<pubDate>'.date('D, d M Y 00:00:00').'</pubDate>'.PHP_EOL
										  .'		<enclosure url="'.JURI::base().$str_audio_path.$str_mp3_file.'" length="27186997" type="audio/mpeg"></enclosure>'.PHP_EOL
										  .'		<itunes:image href="'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'" />'.PHP_EOL
										  .'		<itunes:author>'.$mainframe->getCfg('sitename').'</itunes:author>'.PHP_EOL
										  .'		<itunes:duration></itunes:duration>'.PHP_EOL
										  .'		<itunes:explicit>no</itunes:explicit>'.PHP_EOL
										  .'		<itunes:keywords>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id).','.$str_curr_read_plan.','.$str_bible_name.','.$str_reading_plan.','.$str_bible_Version.'</itunes:keywords>'.PHP_EOL
										  .'		<itunes:subtitle></itunes:subtitle>'.PHP_EOL
										  .'		<itunes:summary>'.PHP_EOL.'{str_verse}		</itunes:summary>'.PHP_EOL
										  .'		<description>'.PHP_EOL.'{str_verse}		</description>'.PHP_EOL
										  .'	</item>'.PHP_EOL;
					$y++;
					$x++;
					$arr_verse[$x] = '';					
				}
				
				$arr_verse[$x] = $arr_verse[$x]. '			'.strip_tags($plan->verse).PHP_EOL;	
			}		
		}
		$z = 1;
		foreach($arr_podcast_output as $obj_podcast_output)
		{
			if (strlen($arr_verse[$z]) > 4000)
			{
				$arr_verse[$z] = mb_substr($arr_verse[$z], 0, 3997, 'UTF-8').'...'.PHP_EOL;
			}			
			$obj_podcast_output = str_replace('{str_verse}',$arr_verse[$z], $obj_podcast_output);
			echo $obj_podcast_output;
			$z++;
		}
		echo  '  </channel>'.PHP_EOL;
		echo  '</rss>'.PHP_EOL;
	}
	private function fnc_output_seperate($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc)
	{
		$book = 0;
		$chap = 0;
		$y = 1;
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		echo '<channel>'.PHP_EOL;
		echo '	<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
		echo '	<image>'.PHP_EOL;
		echo '	  <url>'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'</url>'.PHP_EOL;
		echo '	  <title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan." - ". $str_bible_name. '</title>'.PHP_EOL;
		echo '	  <link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;
		echo '	</image>'.PHP_EOL;		
		echo '	<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan.  " - ". $str_bible_name. '</title>'.PHP_EOL;
		echo '	<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;				
		echo '	<generator>Zefania Bible</generator>'.PHP_EOL;
		echo '	<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
		echo '	<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
		echo '	<description>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE_DESC').' "'.$str_curr_read_plan.'" '. JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $int_day_number. ". ".JText::_($str_desc).'</description>'.PHP_EOL;		
	
		foreach($arr_plan as $reading)
		{
			$x= 1;
			$int_len_reading = count($reading);
			foreach($reading as $plan)
			{					
				if (($plan->book_id > $book)or($plan->chapter_id > $chap))
				{
					if($x > 1)
					{
						echo '		</description>'.PHP_EOL.'	</item>'.PHP_EOL;
					}
					echo '	<item>'.PHP_EOL;
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					echo '		<title>'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id."</title>".PHP_EOL;			
					echo '		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'#'.$y.'</link>'.PHP_EOL;	
					echo '		<guid>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'#'.$y.'</guid>'.PHP_EOL;
					echo '		<pubDate>'.date('D, d M Y 00:00:00').'</pubDate>'.PHP_EOL;
					echo '		<description>'.PHP_EOL;
					$y++;
				}
				echo "		 ".strip_tags($plan->verse). PHP_EOL;
				$x++;
			}
			echo '		</description>'.PHP_EOL.'	</item>'.PHP_EOL;
		}
		echo '</channel>'.PHP_EOL;
		echo '</rss>';		
	}
	private function fnc_output_single($arr_plan, $str_reading_plan, $str_bible_Version, $int_day_number, $str_curr_read_plan, $str_bible_name, $int_day_number, $str_desc)
	{
		$book = 0;
		$chap = 0;
		$str_title = '';
		$str_desc = '';
		$y = 1;
		$int_len = 0;
		$doc = JFactory::getDocument();
		$mainframe = JFactory::getApplication();
		echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
		echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'.PHP_EOL;
		echo '	<channel>'.PHP_EOL;
		echo '		<atom:link href="'.htmlspecialchars(JURI::getInstance()).'" rel="self" type="application/rss+xml" />'.PHP_EOL;
		echo '		<image>'.PHP_EOL;
		echo '	  		<url>'.JURI::root().'components/com_zefaniabible/images/bible_100.jpg'.'</url>'.PHP_EOL;
		echo '	  		<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan." - ". $str_bible_name. '</title>'.PHP_EOL;
		echo '	  		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;
		echo '		</image>'.PHP_EOL;		
		echo '		<title>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE') .' - '. $str_curr_read_plan.  " - ". $str_bible_name. '</title>'.PHP_EOL;
		echo '		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;				
		echo '		<generator>Zefania Bible</generator>'.PHP_EOL;
		echo '		<language>'.$doc->getLanguage().'</language>'.PHP_EOL;
		echo '		<copyright>'.$mainframe->getCfg('sitename').'</copyright>'.PHP_EOL;
		echo '		<description>'.JText::_('ZEFANIABIBLE_READING_RSS_TITLE_DESC').' "'.$str_curr_read_plan.'" '. JText::_('ZEFANIABIBLE_READING_PLAN_DAY').' '. $int_day_number. ". ".JText::_($str_desc).'</description>'.PHP_EOL;		
		foreach($arr_plan as $reading)
		{
			$x= 1;
			$int_len_reading = count($reading);
			foreach($reading as $plan)
			{					
				if (($plan->book_id > $book)or($plan->chapter_id > $chap))
				{
					$str_desc = $str_desc. PHP_EOL;
					$book = $plan->book_id;
					$chap = $plan->chapter_id;
					$str_title = $str_title. JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$plan->book_id)." ".mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8')." ".$plan->chapter_id. ", ";
				}
				$str_desc = $str_desc. "		 ".strip_tags($plan->verse). PHP_EOL;
				$x++;
			}
		}
		$int_len = mb_strlen($str_title);
		$str_title = mb_substr($str_title, 0, ($int_len-2),'UTF-8');
		echo '		<item>'.PHP_EOL;
		echo '		<title>'.$str_title."</title>".PHP_EOL;		
		echo '		<link>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</link>'.PHP_EOL;	
		echo '		<guid>'.JRoute::_(JURI::base()."index.php?option=com_zefaniabible&amp;view=reading&amp;a=".$str_reading_plan."&amp;b=".$str_bible_Version."&amp;c=".$int_day_number).'&amp;ord='.date("mdy").'</guid>'.PHP_EOL;
		echo '		<pubDate>'.date('D, d M Y 00:00:00').'</pubDate>'.PHP_EOL;		
		echo '		<description>';	
		echo '		'.$str_desc;
		echo '		</description>'.PHP_EOL;
		echo '		</item>'.PHP_EOL;	
		echo '	</channel>'.PHP_EOL;
		echo '</rss>'.PHP_EOL;
	}
}
?>