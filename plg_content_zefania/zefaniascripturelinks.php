<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Scripture Plugin
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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
{
	JError::raiseWarning('5', 'ZefaniaBible - ScriptureLinks Plugin - ZefaniaBible component is not installed or not enabled.');
	return;
}
class plgContentZefaniaScriptureLinks extends JPlugin
{
	private $str_default_alias;
	private $int_BibleGateway_id;
	private $int_modal_box_height;
	private $int_modal_box_width;
	private $flg_auto_replace;
	private $str_Bible_books;
	private $int_featured_articles;
	private $cnt_articles;
	private $arr_Bible_books_english;
	private $flg_automatic_scripture_type;
	private $str_label_default;
	private $str_tmpl;
	private $document;
	private $mdl_default;
	private $str_regex;
	private $str_regex_auto;
	private $flg_scripture_detection_type;
	private $arr_book_names;
	private $cnt_books = 0;
	private $arr_avail_lang;
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		// Remove tags in XML Files
		$this->document	= JFactory::getDocument();
		$docType = $this->document->getType();		
		if($docType != 'html')
		{
			return; 
		}
		// don't run anythinig below for admin section
		if((strrpos(JURI::base(),'administrator',0) > 0)or(strrpos(JURI::base(),'administrator',0) !=''))
		{
			return;
		}
		require_once('components/com_zefaniabible/models/default.php');
		require_once('components/com_zefaniabible/helpers/common.php');
		$this->mdl_default 	= new ZefaniabibleModelDefault;
		$this->mdl_common 	= new ZefaniabibleCommonHelper;		
		
		$params_content = JComponentHelper::getParams( 'com_content' );
		$this->int_featured_articles = $params_content->get('num_intro_articles')+1;
			
		$flg_return = $this->fnc_exclude_plugin();
		if($flg_return)
		{
			return;
		}
		$this->str_tmpl 					=	JRequest::getCmd('tmpl');
		$this->int_BibleGateway_id  		= 	$this->params->get('bible_gateway_version', '51');
		$this->int_modal_box_height  		= 	$this->params->get('modal_box_height', '500');
		$this->int_modal_box_width  		= 	$this->params->get('modal_box_width', '700'); 
		$this->flg_auto_replace 			=	$this->params->get('flg_automatic_scripture_detection', '0');
		$this->flg_only_css  				=	$this->params->get('flg_only_css', '0');	
		$this->str_default_alias 			=	$this->params->get('content_Bible_alias' );
		$this->flg_automatic_scripture_type = 	$this->params->get('flg_automatic_scripture_type','0');
		$this->str_label_default 			=	$this->params->get('str_label_default', 'Scripture Reference');
		$this->str_regex 					=	$this->params->get('str_regex');
		$this->str_regex_auto 				=	$this->params->get('str_regex_auto');
		$this->flg_scripture_detection_type	=	$this->params->get('flg_scripture_detection_type', 0);
		$this->flg_use_inline_js 			=	$this->params->get('flg_use_inline_js', 0);
		$this->flg_no_conflict_mode 		=	$this->params->get('flg_no_conflict_mode', 0);
		
		$flg_found_alias = 0;
		$this->loadLanguage();
		$jlang = JFactory::getLanguage();		
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_ADMINISTRATOR, 'en-GB', true);
		//english will be used by Biblegateway
		for($h = 1; $h <= 66; $h ++)
		{
			$arr_english_text = explode('|',mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$h,'UTF-8')));
			$this->arr_Bible_books_english[$h] = $arr_english_text[0];
		}
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_ADMINISTRATOR, null, true);
		JHtml::_('jquery.ui');
		JHTML::_('behavior.modal');
		JHtml::_('bootstrap.tooltip');
		JHtml::_('bootstrap.popover');		
		$this->document->addStyleSheet(JURI::base(true).'/media/zefaniascripturelinks/zefaniascripturelinks.css'); 
		if($this->flg_use_inline_js)
		{
			if($this->flg_no_conflict_mode)
			{
				$this->fnc_make_inline_js(true);
			}else {				
				$this->fnc_make_inline_js(false);
			}
		} else {
			$this->document->addScript(JURI::base(true).'/media/zefaniascripturelinks/zefaniascripturelinks.js');		
		}
		
		if($this->str_default_alias == '')
		{
			$this->str_default_alias = $this->mdl_default->_buildQuery_first_record();
		}
		$this->fncLoadLangauges();
		$this->arr_avail_bibles = $this->mdl_default->_buildQuery_Bibles_Names();
		
		// Attempt to find published alias for current langauge
		foreach($this->arr_avail_bibles as $arr_bibles)
		{
			if($this->str_default_alias == $arr_bibles->alias)
			{
				$flg_found_alias = 1;	
			}
		}
		// if alias not found grab first one.
		if($flg_found_alias == 0)
		{
			foreach($this->arr_avail_bibles as $arr_bibles)
			{
				$this->str_default_alias = $arr_bibles->alias;
				break;
			}
		}
		
		$this->str_regex = str_replace("{zefania-scripture}", $this->str_Bible_books, $this->str_regex);
	}
	private function fnc_make_inline_js( $flg_load_no_conflict)
	{
		$doc_page = JFactory::getDocument();
		$doc_page->addScriptDeclaration('
		function fnc_scripture(obj){
	
			var lang = document.getElement(\'html\').getProperty(\'lang\');	
			lang = lang.substring(0,2);	
			var current_url = window.location.href;
			var test_lang = "/"+lang+"/";
			var working_url = "";
			var str_verse = "";
			var int_temp_verse = 0;	
			if(current_url.indexOf(test_lang) > 0)
			{
				working_url = window.location.protocol+"//"+window.location.hostname+"/"+lang;
			}else{
				working_url = window.location.protocol+"//"+window.location.hostname;
			}
			var url = working_url + "/index.php?option=com_zefaniabible&view=scripture&bible="+obj.bible+"&book="+obj.book+"&chapter="+obj.chapter+"&verse="+obj.verse+"&endchapter="+obj.endchapter+"&endverse="+obj.endverse+"&type=1&variant=json3&format=raw&tmpl=component";
			');
			if($flg_load_no_conflict == true) {
				$doc_page->addScriptDeclaration('jQuery.noConflict();');
			}
			$doc_page->addScriptDeclaration('
			jQuery.getJSON( url, function( data ){
				jQuery.each(data, function( i, item ){
					jQuery.each(item.scripture, function( j, jitem ) {
						if(item.scripture.length > 1){
							if(((int_temp_verse == 0)&&((item.endchap != 0)&&(item.endchap != item.beginchap)))||				
							((jitem.verseid == 1)&&(int_temp_verse >= jitem.verseid)))
							{
								str_verse += \'<div class="zef_content_title">\'+item.bookname+\' \'+jitem.chapterid+\'</div>\';
							}
		
							str_verse += \'<div id="zef_content_verse" style="margin-left:5px;"><div id="zef_content_verse_id" style="float:left">\'+jitem.verseid+\'</div><div id="zef_content_verse_text" style="float:left;margin-left:5px;width:90%;">\'+jitem.verse+\'</div></div><div style="clear:both"></div>\';
							int_temp_verse = jitem.verseid;
						}else{
							str_verse = \'<div id="zef_content_verse_text" style="margin-left:5px;">\'+jitem.verse+\'</div>\';
						}
					});
				});
				switch(obj.type)	{			
					case "dialog":
						jQuery( ".modal-body-"+obj.unique_id).html(str_verse);
						break;
					case "tooltip":	
					default:
						jQuery( ".div-"+obj.unique_id+" p").html(str_verse);
						break;
				}
			});	
		}
		');
	}
	private function fncLoopBooks($lang)
	{
		// take a language and prep strings for multi langauge detection
		$jlang = JFactory::getLanguage();		
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_ADMINISTRATOR, $lang, true);			
		for($z = 1; $z <= 66; $z ++)
		{
			$this->str_Bible_books .= mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'))."|";
			$this->arr_book_names[$this->cnt_books][$z] = mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
		}
		$this->cnt_books++;
	}
	private function fncLoadLangauges()
	{
		switch ($this->flg_scripture_detection_type)
		{
			case 1: 	// Site langauges and English also
				$this->fncLoopBooks("en-GB");
				$this->fncLoopBooks(null);
				break;
			case 2:		// ALL Installed langauges
				foreach(JLanguage::getKnownLanguages() as $arr_system_lang)
				{
					$this->fncLoopBooks($arr_system_lang['tag']);
				}			
				break;
			case 0:		// Site Langauge only
			default:	
				$this->fncLoopBooks(null);
				break;		
		}

		$this->str_Bible_books = substr($this->str_Bible_books, 0, -1); // remove extra | from end of string		
	}
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{ 	
		JFactory::getLanguage()->load('com_zefaniabible', JPATH_BASE, null, true);
		$document = JFactory::getDocument();
		$docType = $this->document->getType();			
		if($docType != 'html')
		{
			$str_match_fuction = "#{zefaniabible\s*(.*?)}#";
			$str_match_fuction_v2 = "#{/zefaniabible}#";
			$row->text = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, ', ', $row->text ));			
			return; 
		}
				
		// stop script for articles that are not loaded on page.
		if($context == "com_content.featured")
		{
			if($this->cnt_articles >= $this->int_featured_articles)
			{
				return; 
			}
			$this->cnt_articles++;
		}
		$regex_substitute = "((\d{1,3})+([:,-;]?(?=\d{1,3}))?)+";
		switch (true)
		{
			case $this->flg_auto_replace:
			case (JRequest::getCmd('option') == 'com_zefaniabible')and(JRequest::getCmd('view') == 'strong'):
			case (JRequest::getCmd('option') == 'com_zefaniabible')and(JRequest::getCmd('view') == 'commentary'): 
				$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})?\b(".$this->str_Bible_books.")(\.)?(\s)?(";
				$str_match_fuction .= $regex_substitute;
				$str_match_fuction .= ")(\{\/zefaniabible\})?/iu";
				$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);		
				break;				
			default:
				$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})\b(".$this->str_Bible_books.")(\.)?(\s)?(";
				$str_match_fuction .= $regex_substitute;
				$str_match_fuction .= ")(\{\/zefaniabible\})/iu";
				$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);		
				break;			
		}	
      	return true;
	}
	private function fnc_Make_Scripture(&$arr_matches)
	{
		$str_scripture = "";
		$str_Bible_book_id = 0;
		$str_passages = '';
		$str_new_alias = '';
		$flg_insert_method = 0;
		$flg_insert_label = 0;
		$flg_insert_tooltip = 0;
		$str_begin_chap = '';
		$str_begin_verse = '';
		$str_end_verse = '';
		$str_end_chap = '';
		$str_proper_name = '';
		$str_look_up;
		$arr_look_up_orig = '';
		$flg_add_bible_title = 0;
		$str_label = '';
		$flg_use_multi_query = 0;
		$str_match_fuction = "#{/zefaniabible}#";
		$str_match_fuction_v2 = "#{zefaniabible*(.*?)}#";	
		$str_scripture = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, '', $arr_matches[0] ));
		$str_scripture_book_name = $arr_matches[3];	
		$str_scripture_name = '';
		$str_scripture_verse = '';	
		$d = 0;
		$w = 0;
		$str_title = '';
		$str_type = '';
		$t = 0;
		// this will allow setting of different link types for automatic replacement.
		if(($this->flg_auto_replace) and (!preg_match('#{zefaniabible(.*?)}(.*?){/zefaniabible}#',$arr_matches[0])))
		{
			switch($this->flg_automatic_scripture_type)
			{
				// text into page
				case 1:
					$flg_insert_method = 1;
					$str_type = 'text';
					break;
				// label flag		
				case 2:
					$flg_insert_method = 2;
					$str_label = $this->str_label_default;	
					$str_type = 'label';			
					break;
				// mouseover tooltip flag	
				case 3:
					$flg_insert_method = 3;		
					$str_type = 'tooltip';
					break;
				// mouseover popover flag
				case 4:
					$flg_insert_method = 4;	
					$str_type = 'popover';	
					break;
				// dialogbox
				case 5:
					$flg_insert_method = 5;		
					$str_type = 'dialog';	
					break;						
				// zefania bible regular flag
				case 6:
					$flg_insert_method = 6;		
					$str_type = 'biblegateway';	
					break;				
							
				// zefania bible regular flag					
				default:
					$flg_insert_method = 0;
					$str_type = '';
					break;
			}
		}
		
		switch (true)
		{
			// text into page
			case preg_match('#{zefaniabible text(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#text#', '', $arr_matches[2] ));
				$flg_insert_method = 1;
				$str_type = 'text';
				break;
			// label flag				
			case preg_match('#{zefaniabible\slabel=*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( "#label=\'(.*?)\'#", '', $arr_matches[2] ));
				$arr_label = preg_split('#\'#',$arr_matches[2]);
				$str_label = $arr_label[1];
				$flg_insert_method = 2;
				$str_type = 'label';
				break;
			// mouseover tooltip flag				
			case preg_match('#{zefaniabible\stooltip*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#tooltip#', '', $arr_matches[2] ));			
				$flg_insert_method = 3;	
				$str_type = 'tooltip';
				break;
			// mouseover popover flag				
			case preg_match('#{zefaniabible\spopover*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#popover#', '', $arr_matches[2] ));			
				$flg_insert_method = 4;			
				$str_type = 'popover';
				break;
			// mouseover dialog flag				
			case preg_match('#{zefaniabible\sdialog*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#dialog#', '', $arr_matches[2] ));			
				$flg_insert_method = 5;		
				$str_type = 'dialog';	
				break;			
			case preg_match('#{zefaniabible\sbiblegateway*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#biblegateway#', '', $arr_matches[2] ));			
				$flg_insert_method = 6;		
				$str_type = 'biblegateway';	
				break;				
			// zefania bible regular flag				
			case preg_match('#{zefaniabible*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim($arr_matches[2]);
				$str_type = '';
				switch ($this->flg_automatic_scripture_type)
				{
					// text into page
					case 1:
						$flg_insert_method = 1;
						$str_type = 'text';
						break;
					// label flag		
					case 2:
						$flg_insert_method = 2;
						$str_label = $this->str_label_default;	
						$str_type = 'label';			
						break;
					// mouseover tooltip flag	
					case 3:
						$flg_insert_method = 3;		
						$str_type = 'tooltip';
						break;
					// mouseover popover flag
					case 4:
						$flg_insert_method = 4;	
						$str_type = 'popover';	
						break;
					// dialogbox
					case 5:
						$flg_insert_method = 5;		
						$str_type = 'dialog';	
						break;						
					// zefania bible regular flag
					case 6:
						$flg_insert_method = 6;		
						$str_type = 'biblegateway';	
						break;				
								
					// zefania bible regular flag					
					default:
						$flg_insert_method = 0;
						$str_type = '';
						break;
				}
				break;
			default:
				$str_scripture = $arr_matches[0];
				$str_type = '';
				break;
		}
				
		for($z = 1; $z <= 66; $z ++)
		{
			$arr_multi_query = '';
			$str_look_up = '';
			for($r = 0; $r < $this->cnt_books; $r++)
			{
				$str_look_up .= $this->arr_book_names[$r][$z]."|";
			}
			$str_look_up = substr($str_look_up, 0, -1); // remove extra | from end of string
			$arr_look_up_orig = explode('|',JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z));
			if(preg_match('/^('.$str_look_up.')$/', mb_strtolower($str_scripture_book_name,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$str_passages = trim(str_replace($str_scripture_book_name ,'',$str_scripture));
				$str_passages = trim(preg_replace( '#\.#', '', $str_passages )); // remove period
				$arr_split_chapters = preg_split('#[;]#',$str_passages);
				foreach($arr_split_chapters as $obj_chapters)
				{
					// make defaults;
					$arr_split_verses = preg_split('#[,]#',$obj_chapters);
					foreach($arr_split_verses as $obj_verses)
					{
						$arr_verses_info[$t]['begin_chapter'] = 1;
						$arr_verses_info[$t]['end_chapter'] = 0;
						$arr_verses_info[$t]['begin_verse'] = '0';		
						$arr_verses_info[$t]['end_verse'] = '0';
						switch(true)
						{
							case preg_match('/^([0-9]{1,3}):([0-9]{1,3})$/',$obj_verses): 								// Gen 1:1
								$arr_split_verses = preg_split('#[:]#',$obj_verses); 			// split on colon
								list($arr_verses_info[$t]['begin_chapter'],$arr_verses_info[$t]['begin_verse']) = $arr_split_verses;
								break;
							case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3})$/',$obj_verses): 				// Gen 1:1-4
								$arr_split_verses = preg_split('#[:-]+#',$obj_verses);	
								list($arr_verses_info[$t]['begin_chapter'],$arr_verses_info[$t]['begin_verse'],$arr_verses_info[$t]['end_verse']) = $arr_split_verses;
								break;
							case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3}):([0-9]{1,3})$/',$obj_verses):	// Gen 2:3-3:3
								$arr_split_verses = preg_split('#[:-]+#',$obj_verses);			// split on colon and hyphen 	
								list($arr_verses_info[$t]['begin_chapter'],$arr_verses_info[$t]['begin_verse'],$arr_verses_info[$t]['end_chapter'],$arr_verses_info[$t]['end_verse']) = $arr_split_verses;	
								break;
							case preg_match('/^([0-9]{1,3})-([0-9]{1,3})$/',$obj_verses):								//Gen 1-4	
								$arr_split_verses = preg_split('#[-]#',$obj_verses); 			// split on hyphen
								// if comma is found use parse differently
								if(strpos($obj_chapters, ",") == true)
								{
									$arr_verses_info[$t]['begin_chapter'] = $arr_verses_info[$t-1]['begin_chapter'];
									list($arr_verses_info[$t]['begin_verse'],$arr_verses_info[$t]['end_verse']) = $arr_split_verses;
								}else{
									list($arr_verses_info[$t]['begin_chapter'],$arr_verses_info[$t]['end_chapter']) = $arr_split_verses;									
								}
								break;										
							case preg_match('/^([0-9]{1,3})$/',$obj_verses):											// Gen 1
							default:																				// as default send genesis 1
								// if comma is found use parse differently							
								if(strpos($obj_chapters, ",") == true)
								{
									$arr_verses_info[$t]['begin_chapter'] = $arr_verses_info[$t-1]['begin_chapter'];
									$arr_verses_info[$t]['begin_verse'] = $obj_verses;
								}else{
									$arr_verses_info[$t]['begin_chapter'] = $obj_verses;	
								}
								break;
						}
						$t++;
					}				
				}
				break;
			}
		}		
		// set new alias 
		if($str_new_alias != "")
		{
			$str_alias = $str_new_alias;
			$flg_add_bible_title = 1;		
		}else{
			$str_alias = $this->str_default_alias;
		}
		
					
		foreach ($arr_verses_info as $obj_verses_info)
		{
			$str_unique_id = uniqid();
			$str_begin_chap = $arr_verses_info[$w]['begin_chapter'];
			$str_end_chap = $arr_verses_info[$w]['end_chapter'];
			$str_begin_verse = $arr_verses_info[$w]['begin_verse'];
			$str_end_verse = $arr_verses_info[$w]['end_verse'];
			
			$str_scripture_name = $this->mdl_common->fnc_make_scripture_title($str_Bible_book_id, $str_begin_chap, $str_begin_verse, $str_end_chap, $str_end_verse);
			if($flg_add_bible_title)
			{
				$str_title = $str_scripture_name.' - '. $str_alias;
			}
			else
			{
				$str_title = $str_scripture_name;
			}			
			// make an object
			$str_obj = '{type: \''.$str_type.'\', unique_id: \''.$str_unique_id.'\', native_name: \''.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'\', scripture: \''.$str_title.'\', bible: \''.$str_alias.'\', book: '.$str_Bible_book_id.', chapter: '.$str_begin_chap.', verse: '.$str_begin_verse.', endchapter: '.$str_end_chap.', endverse: '.$str_end_verse.', width: '.$this->int_modal_box_width.', height: '.$this->int_modal_box_height.' }';		
			$temp = '&bible='.$str_alias.'&book='.$str_Bible_book_id.'&chapter='.$str_begin_chap.'&verse='.$str_begin_verse.'&endchapter='.$str_end_chap.'&endverse='.$str_end_verse;
			switch ($flg_insert_method)
			{
				// 1  = text
				case 1:
					$arr_verses = $this->mdl_default->_buildQuery_scripture($str_alias, $str_Bible_book_id, $str_begin_chap, $str_begin_verse, $str_end_chap, $str_end_verse);	 
					$str_scripture_verse .= $this->mdl_common->fnc_scripture_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title, $arr_multi_query,$flg_use_multi_query,$str_passages, $flg_add_title = 1);
					break;
				// 2 = label
				case 2:
					$str_scripture_verse .= '<a href="index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-label">'.$str_label .'</a>'; 				
					break;
				// 3 = tooltip
				case 3:
					$str_scripture_verse .= '<a id="zef-scripture-tooltip" data-placement="right" onMouseOver="fnc_scripture('.$str_obj.');" data-original-title="<strong>'.$str_title.'</strong><br /><div id=\'div-zef-scripture-tooltip\' class=\'div-'.$str_unique_id.'\'><p></p></div>" class="'.$str_unique_id.' hasTooltip" title="">'.$str_scripture_name .'</a>';
					break; 
				// 4 = popover
				case 4:
					$str_scripture_verse .= '<a id="zef-scripture-popover-link" class="zef-scripture-popover-'.$str_unique_id.' hasPopover" onmouseover="fnc_scripture('.$str_obj.');" data-content="<div class=\'div-'.$str_unique_id.'\'><p><img witdh=\'220\' height=\'19\' src=\''.JURI::base(true).'/media/zefaniascripturelinks/loader.gif\'</p></div>" data-placement="right" title="'.$str_title.'" id="zef-scripture-popover">'.$str_scripture_name .'</a>';
					break;
				// dialog box
				case 5:
					$str_scripture_verse .= '<a id="zef-scripture-dialog" data-toggle="modal" data-target="#div-'.$str_unique_id.'" class="zef-scripture-dialog-'.$str_unique_id.'" onclick="fnc_scripture('.$str_obj.');" title="'.JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK').' '.$str_scripture_name.'">'.$str_scripture_name .'</a><div style="float:left" role="dialog" aria-labelledby="div-'.$str_unique_id.'" aria-hidden="true" id="div-'.$str_unique_id.'" class="modal fade " title="'.$str_title .'" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="div-'.$str_unique_id.'-label">'.$str_scripture_name.'</h4></div><div id="modal-body" class="modal-body-'.$str_unique_id.'"><img witdh="220" height="19" src="'.JURI::base(true).'/media/zefaniascripturelinks/loader.gif"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.JText::_('COM_ZEFANIABIBLE_CLOSE').'</button></div></div></div></div>';					
					break;
				// Biblegateway link
				case 6:
					$str_link = urlencode(str_replace(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id) ,$this->arr_Bible_books_english[$str_Bible_book_id], $str_scripture_name));
					$str_url = 'http://classic.biblegateway.com/passage/index.php?search='.$str_link.';&version='.$this->int_BibleGateway_id.';&interface=print';		
					if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
					{	
						$str_scripture_verse .= '<a href="'.$str_url.'" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" id="zef-scripture-link">'.$str_scripture_name .'</a>'; 					
					}else{					
						$str_scripture_verse .= '<a href="'.$str_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'; 
					}
					break;
				// defaut = regular link with modal box				
				default: 
					if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
					{
						$str_scripture_verse .= '<a id="zef-scripture-link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'">'.$str_scripture_name.'</a>'; 					
					}else{				
						$str_scripture_verse .= '<a href="index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'; 
					}
					break;				
			}
			// add comma
			if((count($arr_verses_info) > 1)and($w < count($arr_verses_info)-1)and($flg_insert_method != 1))
			{
						$str_scripture_verse .= ",";		
			}			
			$w++;
		}

		// use this to avoid broken scripture link
		if(strpos($str_scripture_verse,'ZEFANIABIBLE_BIBLE_BOOK_NAME_')> 1)
		{
			return $str_scripture_verse = $arr_matches[0];
		}	
		return $str_scripture_verse;
	}
	protected function fnc_exclude_plugin()
	{
		$str_component_list = $this->params->get('str_exclude_component', '');
		$str_menu_list = $this->params->get('str_exclude_menuitem', '');
		$str_article_list = $this->params->get('str_exclude_article_id', '');
		$str_URI_list = $this->params->get('str_exclude_URI', '');
		$flg_return = 0;
		// exclude component code here 
		if($str_component_list != '')
		{
			$arr_component_list = explode(',',$str_component_list);
			foreach($arr_component_list as $str_component)
			{
				if(JRequest::getCmd('option') ==  trim(strip_tags($str_component)))
				{
					$flg_return = 1;
					return $flg_return;
				}	
			}
		}
		// exclude menu item here 
		if(($str_menu_list != '')and(JRequest::getInt('Itemid') >= 1))
		{
			$arr_menu_list = explode(',',$str_menu_list);
			foreach($arr_menu_list as $str_menu)
			{
				if(JRequest::getInt('Itemid') ==  trim(strip_tags($str_menu)))
				{
					$flg_return = 1;	
					return $flg_return;
				}
			}
		}
		// exclude article ID.
		if(($str_article_list != '')and(JRequest::getInt('id') >= 1))
		{
			$arr_article_list = explode(',',$str_article_list);
			foreach($arr_article_list as $str_article)
			{
				if(JRequest::getInt('id') ==  trim(strip_tags($str_article)))
				{
					$flg_return = 1;
					return $flg_return;
				}				
			}
		}
		// exclude URI
		if($str_URI_list != "")
		{
			$arr_URI_list = explode(',',$str_URI_list);
			foreach($arr_URI_list as $str_uri)
			{
				if(preg_match('#'.trim(strip_tags($str_uri)).'#',JURI::getInstance()))
				{
					$flg_return = 1;
					return $flg_return;
				}	
			}
		}
		
		return $flg_return;
	}
}
?>