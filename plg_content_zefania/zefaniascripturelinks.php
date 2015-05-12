<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Search Plugin
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
		$this->str_tmpl 						=	JRequest::getCmd('tmpl');
		$this->int_BibleGateway_id  		= 	$this->params->get('bible_gateway_version', '51');
		$this->int_modal_box_height  		= 	$this->params->get('modal_box_height', '500');
		$this->int_modal_box_width  		= 	$this->params->get('modal_box_width', '700'); 
		$this->flg_auto_replace 				=	$this->params->get('flg_automatic_scripture_detection', '0');
		$this->flg_only_css  				=	$this->params->get('flg_only_css', '0');	
		$this->str_default_alias 			=	$this->params->get('content_Bible_alias' );
		$this->flg_automatic_scripture_type = 	$this->params->get('flg_automatic_scripture_type','0');
		$this->str_label_default 			=	$this->params->get('str_label_default', 'Scripture Reference');
		$this->str_regex 					=	$this->params->get('str_regex');
		$this->str_regex_auto 				=	$this->params->get('str_regex_auto');

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
		$this->document->addStyleSheet(JURI::base(true).'/plugins/content/zefaniascripturelinks/css/zefaniascripturelinks.css'); 
		$this->document->addScript(JURI::base(true).'/plugins/content/zefaniascripturelinks/zefaniascripturelinks.js');		
		
		if($this->str_default_alias == '')
		{
			$this->str_default_alias = $this->mdl_default->_buildQuery_first_record();
		}
		for($z = 1; $z <= 66; $z ++)
		{
			$this->str_Bible_books = $this->str_Bible_books . mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'))."|";
		}
		$this->str_regex = str_replace("{zefania-scripture}", $this->str_Bible_books, $this->str_regex);
		
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
		switch (true)
		{
			case $this->flg_auto_replace:
			case (JRequest::getCmd('option') == 'com_zefaniabible')and(JRequest::getCmd('view') == 'strong'):
			case (JRequest::getCmd('option') == 'com_zefaniabible')and(JRequest::getCmd('view') == 'commentary'):
				$str_match_fuction = $this->str_regex_auto;
				$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);		
				break;				
			default:
				$str_match_fuction = $this->str_regex;
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
		$arr_verses_info[0]['begin_chapter'] = '0';
		$arr_verses_info[0]['end_chapter'] = 0;
		$arr_verses_info[0]['begin_verse'] = '0';
		$arr_verses_info[0]['end_verse'] = '0';		
		$str_scripture_name = '';
		$str_scripture_verse = '';	
		$d = 0;
		$w = 0;
		$str_title = '';
		$str_type = '';

		
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
			$str_look_up = mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'));
			$arr_look_up_orig = explode('|',JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z));
			if(preg_match('/^('.$str_look_up.')$/', mb_strtolower($str_scripture_book_name,'UTF-8')))
			{
				$str_proper_name = $arr_look_up_orig[0];
				$str_Bible_book_id = $z;
				$str_passages = trim(str_replace($str_scripture_book_name ,'',$str_scripture));
				$str_passages = preg_replace( '#\.#', '', $str_passages ); // remove period
				
				switch (true)
				{			
					case preg_match('/^([0-9]{1,3})-([0-9]{1,3})$/',$str_passages):		//Gen 1-4		 
					case preg_match('/^([0-9]{1,3})$/',$str_passages):					// Gen 1
					 	$arr_split_verses = preg_split('#[-]#',$str_passages); 			// split on hyphen
						if(count($arr_split_verses) == 2)
						{
							list($arr_verses_info[0]['begin_chapter'],$arr_verses_info[0]['end_chapter']) = $arr_split_verses;
						}
						else
						{
							list($arr_verses_info[0]['begin_chapter']) = $arr_split_verses;
							$arr_verses_info[0]['end_chapter'] = '0';
						}
						$arr_verses_info[0]['begin_verse'] = '0';
						$arr_verses_info[0]['end_verse'] = '0';
						break;				
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):	// Gen 2:3-3:3
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages);								// split on colon and hyphen 
						list($arr_verses_info[0]['begin_chapter'],$arr_verses_info[0]['begin_verse'],$arr_verses_info[0]['end_chapter'],$arr_verses_info[0]['end_verse']) = $arr_split_verses;
				  		break;					
					case preg_match('/(?=\S)(([0-9]{1,3})([;])(\s)?([0-9]{1,3}))?/iu',$str_passages): 	// Gen 1:2-3, 10; 2:20
						$arr_split_chapters = preg_split('#[;]#',$str_passages);
						$o = 0;	
						$m = 0;
						foreach ($arr_split_chapters as $ojb_chapters)
						{
							$arr_split_verses = preg_split('#[:]#',$arr_split_chapters[$m]);							
							if(count($arr_split_verses) > 1)
							{
								$arr_verse_ranges = explode(',',$arr_split_verses[1]);
								foreach ($arr_verse_ranges as $obj_verses)
								{
									if(count($arr_verse_ranges)>1)
									{
										$arr_verses_temp = explode('-',$arr_verse_ranges[$o]);
									}
									else
									{
										$arr_verses_temp = explode('-',$arr_verse_ranges[0]);
									}
									
									$arr_verses_info[$o]['begin_chapter'] = trim($arr_split_verses[0]);
									$arr_verses_info[$o]['end_chapter'] = 0;
									$arr_verses_info[$o]['begin_verse'] = trim($arr_verses_temp[0]);
									
									if(count($arr_verses_temp) > 1)
									{
										$arr_verses_info[$o]['end_verse'] = trim($arr_verses_temp[1]);
									}
									else
									{
										$arr_verses_info[$o]['end_verse'] = '0';
									}
									$o++;
								}								
							}
							else
							{
								$arr_verses_info[$o]['begin_chapter'] = trim($arr_split_chapters[$m]);
								$arr_verses_info[$o]['end_chapter'] = 0;
								$arr_verses_info[$o]['begin_verse'] = '0';
								$arr_verses_info[$o]['end_verse'] = '0';									
								$o++;
							}						
							$m++;
						}
						break;					
						
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):   				// Gen 1:1
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3})$/',$str_passages): 	// Gen 1:1-4
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages); 					// split on colon and hyphen
						if(count($arr_split_verses) == 3)
						{
							list($arr_verses_info[0]['begin_chapter'],$arr_verses_info[0]['begin_verse'],$arr_verses_info[0]['end_verse']) = $arr_split_verses;
						}
						else
						{
							list($arr_verses_info[0]['begin_chapter'],$arr_verses_info[0]['begin_verse']) = $arr_split_verses;
							$arr_verses_info[0]['end_verse'] = '0';
						}
						$arr_verses_info[0]['end_chapter'] = '0';							
						break;	
												
					case preg_match('/^([0-9]{1,3})([:])([0-9]{1,3})(([-])([0-9]{1,3}))?(([,])([0-9]{1,3}))/',$str_passages): 	// Gen 1:1-4,5...
						$arr_split_verses = preg_split('#[:]#',$str_passages); 													// split on colon
						list($arr_verses_info[0]['begin_chapter'],$arr_verse_ranges) = $arr_split_verses;
						$arr_verse_ranges = explode(',',$arr_verse_ranges);
						$arr_multi_query = "";
						foreach ($arr_verse_ranges as $arr_verse_range)
						{
							$arr_multi_query[$d] = explode("-", $arr_verse_range); 
							$d++;
						}
						$flg_use_multi_query = 1;
						break;
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3}):([0-9]{1,3})/',$str_passages):	// Gen 2:3-3:3; 5:1-32; 6:9-9:29;...
						$arr_split_chapters = preg_split('#[;]#',$str_passages);
						$o = 0;	
						$m = 0;
						foreach ($arr_split_chapters as $ojb_chapters)
						{
							$arr_split_verses = preg_split('#[:]#',$arr_split_chapters[$m]);	
												

							if(count($arr_split_verses) > 1)
							{
								$arr_verse_ranges = explode(',',$arr_split_verses[1]);
								foreach ($arr_verse_ranges as $obj_verses)
								{
									if(count($arr_verse_ranges)>1)
									{
										$arr_verses_temp = explode('-',$arr_verse_ranges[$o]);
									}
									else
									{
										$arr_verses_temp = explode('-',$arr_verse_ranges[0]);
									}
									$arr_verses_info[$o]['begin_chapter'] = trim($arr_split_verses[0]);
									$arr_verses_info[$o]['begin_verse'] = trim($arr_verses_temp[0]);

									switch(true)
									{
										case (preg_match('/^([0-9]{1,3})([:])([0-9]{1,3})([-])([0-9]{1,3})$/',trim($arr_split_chapters[$m]))):
											$arr_verse_temp = preg_split('#[-:]#',trim($arr_split_chapters[$m]));
											list($arr_verses_info[$o]['begin_chapter'],$arr_verses_info[$o]['begin_verse'],$arr_verses_info[$o]['end_verse']) = $arr_verse_temp;
											$arr_verses_info[$o]['end_chapter'] = 0;
											break;
										case (preg_match('/^([0-9]{1,3})([:])([0-9]{1,3})([-])([0-9]{1,3})([:])([0-9]{1,3})$/',trim($arr_split_chapters[$m]))):
											$arr_verse_temp = preg_split('#[-:]#',trim($arr_split_chapters[$m]));
											list($arr_verses_info[$o]['begin_chapter'],$arr_verses_info[$o]['begin_verse'],$arr_verses_info[$o]['end_chapter'],$arr_verses_info[$o]['end_verse']) = $arr_verse_temp;
											break;
										case (count($arr_verses_temp) > 1):
											$arr_verses_info[$o]['end_verse'] = trim($arr_verses_temp[1]);
											break;
										default:
											$arr_verses_info[$o]['end_verse'] = '0';
											$arr_verses_info[$o]['end_chapter'] = 0;
											break;
									}
									$o++;
								}								
							}
							else
							{
								$arr_verses_info[$o]['begin_chapter'] = trim($arr_split_chapters[$m]);
								$arr_verses_info[$o]['end_chapter'] = 0;
								$arr_verses_info[$o]['begin_verse'] = '0';
								$arr_verses_info[$o]['end_verse'] = '0';									
								$o++;
							}						
							$m++;
						}
						break;
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
			$str_spacer = '';
			$str_unique_id = uniqid();
			if(count($arr_verses_info > 1))
			{
				$str_begin_chap = $arr_verses_info[$w]['begin_chapter'];
				$str_end_chap = $arr_verses_info[$w]['end_chapter'];
				$str_begin_verse = $arr_verses_info[$w]['begin_verse'];
				$str_end_verse = $arr_verses_info[$w]['end_verse'];
			}	
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
					$str_scripture_verse .= '<a href="index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture.'" target="blank" id="zef-scripture-label">'.$str_label .'</a>'.PHP_EOL; 				
					break;
				// 3 = tooltip
				case 3:
					$str_scripture_verse .= '<a id="zef-scripture-tooltip" data-placement="right" onMouseOver="fnc_scripture('.$str_obj.');" data-original-title="<strong>'.$str_title.'</strong><br /><div id=\'div-zef-scripture-tooltip\' class=\'div-'.$str_unique_id.'\'><p></p></div>" class="'.$str_unique_id.' hasTooltip" title="">'.$str_scripture_name .'</a>'.PHP_EOL;
					break; 
				// 4 = popover
				case 4:
					$str_scripture_verse .= '<a id="zef-scripture-popover-link" class="zef-scripture-popover-'.$str_unique_id.' hasPopover" onmouseover="fnc_scripture('.$str_obj.');" data-content="<div class=\'div-'.$str_unique_id.'\'><p></p></div>" data-placement="right" title="'.$str_title.'" id="zef-scripture-popover">'.$str_scripture_name .'</a>'.PHP_EOL;		
					break;
				// dialog box
				case 5:
					$str_scripture_verse .= '<a id="zef-scripture-dialog" data-toggle="modal" data-target="#div-'.$str_unique_id.'" class="zef-scripture-dialog-'.$str_unique_id.'" onclick="fnc_scripture('.$str_obj.');" title="'.JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK').' '.$str_scripture_name.'">'.$str_scripture_name .'</a><div style="float:left" role="dialog" aria-labelledby="div-'.$str_unique_id.'" aria-hidden="true" id="div-'.$str_unique_id.'" class="modal fade " title="'.$str_title .'" ><div class="modal-dialog" ><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="div-'.$str_unique_id.'-label">'.$str_scripture_name.'</h4></div><div id="modal-body" class="modal-body-'.$str_unique_id.'">...</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">'.JText::_('COM_ZEFANIABIBLE_CLOSE').'</button></div></div></div></div>'.PHP_EOL;					
					break;
				// Biblegateway link
				case 6:
					$str_link = urlencode(str_replace(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id) ,$this->arr_Bible_books_english[$str_Bible_book_id], $str_scripture_name));
					$str_url = 'http://classic.biblegateway.com/passage/index.php?search='.$str_link.';&version='.$this->int_BibleGateway_id.';&interface=print';		
					if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
					{	
						$str_scripture_verse .= '<a href="'.$str_url.'" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" id="zef-scripture-link">'.$str_scripture_name .'</a>'.PHP_EOL; 					
					}else{					
						$str_scripture_verse .= '<a href="'.$str_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'.PHP_EOL; 
					}
					break;
				// defaut = regular link with modal box				
				default: 
					if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
					{
						$str_scripture_verse .= '<a id="zef-scripture-link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'">'.$str_scripture_name.'</a>'.PHP_EOL; 					
					}else{				
						$str_scripture_verse .= '<a href="index.php?view=scripture&option=com_zefaniabible&tmpl=component'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_scripture_name.'" target="blank" id="zef-scripture-link">'.$str_scripture_name .'</a>'.PHP_EOL; 
					}
					break;				
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