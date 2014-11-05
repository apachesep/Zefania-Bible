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
	private $int_tooltip_width;
	private $int_tooltip_height;
	private $int_tooltip_duration;
	private $str_tooltip_effect;
	private $flg_use_new_tooltip;
	private $str_default_alias;
	private $int_BibleGateway_id;
	private $int_modal_box_height;
	private $int_modal_box_width;
	private $flg_link_use;
	private $flg_jquery_no_conflict;
	private $flg_auto_replace;
	private $flg_only_css;
	private $str_Bible_books;
	private $int_featured_articles;
	private $cnt_articles;
	private $arr_Bible_books_english;
	private $flg_automatic_scripture_type;
	private $str_label_default;
	private $str_tmpl;
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		// Remove tags in XML Files
		$document	= JFactory::getDocument();
		$docType = $document->getType();		
		if($docType != 'html')
		{
			return; 
		}
		// don't run anythinig below for admin section
		if((strrpos(JURI::base(),'administrator',0) > 0)or(strrpos(JURI::base(),'administrator',0) !=''))
		{
			return;
		}
		
		$params_content = JComponentHelper::getParams( 'com_content' );
		$this->int_featured_articles = $params_content->get('num_intro_articles')+1;
			
		$flg_return = $this->fnc_exclude_plugin();
		if($flg_return)
		{
			return;
		}
		$this->int_tooltip_width  = 	$this->params->get('int_tooltip_width', '700');
		$this->int_tooltip_height = 	$this->params->get('int_tooltip_height', '500');
		$this->int_tooltip_duration = 	$this->params->get('int_tooltip_duration', '1000');

		$this->str_tooltip_effect = 	$this->params->get('str_tooltip_effect', 'blind');
		$this->str_tmpl 		= 		JRequest::getCmd('tmpl');
		$this->flg_use_new_tooltip = 	$this->params->get('flg_use_new_tooltip', 0);
		$this->int_BibleGateway_id  = 	$this->params->get('bible_gateway_version', '51');
		$this->int_modal_box_height  = 	$this->params->get('modal_box_height', '500');
		$this->int_modal_box_width  = 	$this->params->get('modal_box_width', '700'); 
		$this->flg_link_use  = 			$this->params->get('flg_link_use', '0');
		$this->flg_jquery_no_conflict = $this->params->get('flg_jquery_no_conflict', 0);	
		$this->flg_auto_replace = 		$this->params->get('flg_automatic_scripture_detection', '0');
		$this->flg_only_css  = 			$this->params->get('flg_only_css', '0');	
		$this->str_default_alias = 		$this->params->get('content_Bible_alias' );
		$this->flg_automatic_scripture_type = $this->params->get('flg_automatic_scripture_type','0');
		$this->str_label_default =		$this->params->get('str_label_default', 'Scripture Reference');

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
		$document->addStyleSheet('/plugins/content/zefaniascripturelinks/css/zefaniascripturelinks.css'); 
		
		if($this->flg_only_css)
		{
			return; 
		}		
		// percentage resize
		if($this->int_tooltip_width <=1)
		{
			$document->addScriptDeclaration('var window_width = screen.width*'.$this->int_tooltip_width.';');
		}else{
			$document->addScriptDeclaration('var window_width = '.$this->int_tooltip_width.';');
		}
		
		if($this->int_tooltip_height<=1)
		{
			$document->addScriptDeclaration('var window_height = screen.height*'.$this->int_tooltip_height.';');
		}else		{
			$document->addScriptDeclaration('var window_height = '.$this->int_tooltip_height.';');				
		}
		$document->addScriptDeclaration('
			if(screen.width < 500){window_width = screen.width*0.8;}
			if(screen.height < 500){window_height = screen.height*0.8;}
		');
					
		JHTML::_('behavior.modal');
		
		if($this->flg_use_new_tooltip)
		{

		}else{
			$arr_toolTipArray = array('className'=>'zefania-tip', 
			'fixed'=>true,
			'showDelay'=>'500',
			'hideDelay'=>'5000'
			);						
			JHTML::_('behavior.tooltip', '.hasTip-zefania', $arr_toolTipArray);
		}
		if($this->str_default_alias == '')
		{
			$this->str_default_alias = $this->fnc_get_first_bible_record();
		}
		for($z = 1; $z <= 66; $z ++)
		{
			$this->str_Bible_books = $this->str_Bible_books . mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$z,'UTF-8'))."|";
		}
	}
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{ 	
		JFactory::getLanguage()->load('com_zefaniabible', JPATH_BASE, null, true);
		$document = JFactory::getDocument();
		$docType = $document->getType();			

		if($docType != 'html')
		{
			$str_match_fuction = "#{zefaniabible\s*(.*?)}#";
			$str_match_fuction_v2 = "#{/zefaniabible}#";
			$row->text = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, ', ', $row->text ));			
			return; 
		}
		if($this->flg_only_css)
		{
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
				$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})?\b(".$this->str_Bible_books.")(\.)?(\s)?((\d{1,3})([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?)(\{\/zefaniabible\})?/iu";
				$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);		
				break;				
			default:
				//$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})\b(".$this->str_Bible_books.")(\s)(\d{1,3})([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?(\{\/zefaniabible\})/iu";
				$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})\b(".$this->str_Bible_books.")(\.)?(\s)?((\d{1,3})([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?(\d{1,3})?([:,-;]?(\s)?(?=\d))?)(\{\/zefaniabible\})/iu";
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
		$str_scripture_verse = '';	
		$d = 0;
		$w = 0;
		
		/*
			set flag to one of those variables
			0 = regular tag
			1 = text
			2 = label
			3 = tooltip
		*/
		
		// this will allow setting of different link types for automatic replacement.
		if(($this->flg_auto_replace) and (!preg_match('#{zefaniabible(.*?)}(.*?){/zefaniabible}#',$arr_matches[0])))
		{
			switch($this->flg_automatic_scripture_type)
			{
				case 1:
					$flg_insert_method = 1;
					break;
				case 2:
					$flg_insert_method = 2;
					$str_label = $this->str_label_default;				
					break;
				case 3:
					$flg_insert_method = 3;		
					break;
				default:
					$flg_insert_method = 0;
					break;
			}
		}
		
		switch (true)
		{
			// text into page
			case preg_match('#{zefaniabible text(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#text#', '', $arr_matches[2] ));
				$flg_insert_method = 1;
				break;
			// label flag				
			case preg_match('#{zefaniabible\slabel=*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( "#label=\'(.*?)\'#", '', $arr_matches[2] ));
				$arr_label = preg_split('#\'#',$arr_matches[2]);
				$str_label = $arr_label[1];
				$flg_insert_method = 2;
				break;
			// mouseover tooltip flag				
			case preg_match('#{zefaniabible\stooltip*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim(preg_replace( '#tooltip#', '', $arr_matches[2] ));			
				$flg_insert_method = 3;			
				break;
			// zefania bible regular flag				
			case preg_match('#{zefaniabible*(.*?)}(.*?){/zefaniabible}#',$arr_matches[0]):
				$str_new_alias = trim($arr_matches[2]);
				break;
			default:
				$str_scripture = $arr_matches[0];
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
						
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):	// Gen 2:3-3:3
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages);								// split on colon and hyphen 
						list($arr_verses_info[0]['begin_chapter'],$arr_verses_info[0]['begin_verse'],$arr_verses_info[0]['end_chapter'],$arr_verses_info[0]['end_verse']) = $arr_split_verses;
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
			if(count($arr_verses_info > 1))
			{
				$str_begin_chap = $arr_verses_info[$w]['begin_chapter'];
				$str_end_chap = $arr_verses_info[$w]['end_chapter'];
				$str_begin_verse = $arr_verses_info[$w]['begin_verse'];
				$str_end_verse = $arr_verses_info[$w]['end_verse'];
				$str_spacer = ' ';
			}
			// don't execute lookup for regular link
			if($flg_insert_method)
			{
				$arr_verses = $this->fnc_Find_Bible_Passage($str_alias, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse,$arr_multi_query);
			}
	
			switch ($flg_insert_method)
			{
				case 1:
					$str_scripture_verse .= $str_spacer.$this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title,$arr_multi_query,$flg_use_multi_query,$str_passages,1 );
					break;
				case 2:
					$str_scripture_verse .= $str_spacer. $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias, $str_label,$flg_use_multi_query,$str_passages);
					break;
				case 3:
					if($this->flg_use_new_tooltip)
					{
						$str_scripture_tmp = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title, $arr_multi_query,$flg_use_multi_query,$str_passages,0 );				
						$str_scripture_verse .= $str_spacer. $this->fnc_make_dialog_box($str_scripture,$str_scripture_tmp);		
					}
					else
					{
						$str_scripture_temp = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title,$arr_multi_query,$flg_use_multi_query,$str_passages,1 );
						$str_scripture_verse .= $str_spacer. JHTML::tooltip($str_scripture_temp,'', '', $str_scripture, '', false,'hasTip-zefania');		
					}
					break;
				default:
					$str_scripture_verse .= $str_spacer. $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias,'',$flg_use_multi_query,$str_passages );
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
	protected function fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap=0, $str_begin_verse=0, $str_end_verse=0, $str_alias, $str_scripture_name, $flg_use_multi_query,$str_passages)
	{
			// 0 = Zefania
			// 1 = BibleGateway
		$verse = '';
		$str_link = '';
		
		$str_link_name = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id)." "; 
		switch(true)
    	{
			// Gen 1:1,3,5,7
			case $flg_use_multi_query:
				$str_temp = explode(":",$str_passages);
				$str_link = $str_begin_chap.":".$str_temp[1];
				$str_begin_verse = $str_temp[1];
				break;
			//Gen 1
			case ($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse):
				$str_link = $str_begin_chap;
				break;		
			//Gen 1:1	
			case ($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse):
				$str_link = $str_begin_chap.":".$str_begin_verse;
				break;
			//Gen 1:1-3
			case ($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse):
				$str_link = $str_begin_chap.":".$str_begin_verse."-".$str_end_verse;
				break;
			// Gen 1-3
			case ($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse):
				$str_link = $str_begin_chap."-".$str_end_chap;
				break;
			// Gen 1:2-4:2
			case ($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse):
				$str_link = $str_begin_chap.":".$str_begin_verse."-".$str_end_chap.":".$str_end_verse;
				break;
			default:
				break;
		}
		$str_link_label = $str_link_name.$str_link;
		if($str_scripture_name)
		{
			$str_link_label = $str_scripture_name;
		}
		if($this->flg_link_use == 0)
		{		
			// modal box coding begins here
			$temp = 'bible='.$str_alias.'&book='.$str_Bible_book_id.'&chapter='.$str_begin_chap.'&verse='.$str_begin_verse.'&endchapter='.$str_end_chap.'&endverse='.$str_end_verse;
			if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
			{
				$str_pre_link = '<a id="zef_bible_link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_name.$str_link.'" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'">';						
			}else{
				$str_pre_link = '<a id="zef_bible_link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_name.$str_link.'" target="blank" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}">';		
			}
			$verse = $str_pre_link.$str_link_label.'</a>';
			// modal bocx coding ends here			
		}
		else
		{
			// Bible gateway coding begins here
			$str_link_url = 'http://classic.biblegateway.com/passage/index.php?search='.urlencode($this->arr_Bible_books_english[$str_Bible_book_id]." ".$str_link).';&version='.$this->int_BibleGateway_id.';&interface=print';			
			if(((JRequest::getCmd('option') == 'com_zefaniabible')and((JRequest::getCmd('view') == 'strong')or(JRequest::getCmd('view') == 'commentary'))and($this->str_tmpl == "component")))
			{				
				$str_pre_link = '<a id="zef_bible_link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_label.'" href="'.$str_link_url.'" >';				
			}
			else
			{
				$str_pre_link = '<a id="zef_bible_link" title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_label.'" target="blank" href="'.$str_link_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}">';
			}
			$verse = $str_pre_link.$str_link_label .'</a>';
			// Bible gateway coding ends here			
		}
		return $verse;
	}
	// used for creating html to insert directly into page
	protected function fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_bible_title, $arr_multi_query,$flg_use_multi_query,$str_passages, $flg_add_title = 0)
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
		return $verse;
	}
	// sql calls to get Bible passage info
	protected function fnc_Find_Bible_Passage($str_alias, $int_Bible_book_id, $int_begin_chap, $int_end_chap, $int_begin_verse, $int_end_verse,$arr_multi_query)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$data = '';
			$query  = $db->getQuery(true);
			$int_Bible_book_id_clean	= 	$db->quote($int_Bible_book_id);
			$str_alias_clean			=	$db->quote($str_alias);
			$int_begin_chap_clean		=	$db->quote($int_begin_chap);
			$int_end_chap_clean			=	$db->quote($int_end_chap);
			$int_begin_verse_clean		= 	$db->quote($int_begin_verse);
			$int_end_verse_clean		=	$db->quote($int_end_verse);
						
			$query->select("a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name FROM `#__zefaniabible_bible_text` AS a");
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->where("a.book_id=".$int_Bible_book_id_clean);
			$query->where("b.alias=".$str_alias_clean);
				switch (true)
				{			
					// Genesis 1
					case (($int_begin_chap)and(!$int_end_chap)and(!$int_begin_verse)and(!$int_end_verse)):
						$query->where("a.chapter_id=".$int_begin_chap_clean);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 						
						break;
					// Genesis 1-2
					case (($int_begin_chap)and($int_end_chap)and(!$int_begin_verse)and(!$int_end_verse)):
						$query->where("a.chapter_id>=".$int_begin_chap_clean." AND a.chapter_id<=".$int_end_chap_clean);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:1
					case (($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and(!$int_end_verse)):
						$query->where("a.chapter_id=".$int_begin_chap_clean." AND a.verse_id=".$int_begin_verse_clean);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:1-2
					case (($int_begin_chap)and(!$int_end_chap)and($int_begin_verse)and($int_end_verse)):
						$query->where("a.chapter_id=".$int_begin_chap_clean." AND a.verse_id>=".$int_begin_verse_clean. " AND a.verse_id<=".$int_end_verse_clean);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:2-2:3
					case (($int_begin_chap)and($int_end_chap)and($int_begin_verse)and($int_end_verse)):
						$str_tmp_old_query = $query;
						$query	= "SELECT * FROM( ".$query . " AND a.chapter_id=".$int_begin_chap_clean." AND a.verse_id>=".$int_begin_verse_clean. " ORDER BY a.verse_id ASC ) as c";
						$query  .=  " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id=".$int_end_chap_clean." AND a.verse_id<=".$int_end_verse_clean." ORDER BY a.verse_id ASC) as d";
						if(($int_end_chap - $int_begin_chap)>1)
						{
							$query  .=  " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id>=".($int_begin_chap+1)." AND a.chapter_id<=".($int_end_chap-1)." ORDER BY a.verse_id ASC) as e";
						}
						$query  .=  " ORDER BY chapter_id, verse_id";					
						break;
					// default Genesis 1:1
					default:
						$query->where("a.chapter_id=1 AND a.verse_id=1");
						break;	
				}
				// Multi Verse Query
				if(count($arr_multi_query) > 1)
				{
					$query = ("SELECT a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name FROM `#__zefaniabible_bible_text` AS a");
					$query .= (' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
					$query .= (" WHERE a.book_id=".$int_Bible_book_id_clean." AND b.alias=".$str_alias_clean." AND a.chapter_id=".$int_begin_chap_clean." AND (");
					$y=1;				
					foreach ($arr_multi_query as $obj_multi_query)
					{
						if($y > 1)
						{
							$query .= (' OR ');
						}
						$query .= ('(');
						$int_verse_start	=	$db->quote($obj_multi_query[0]);
											
						if(count($obj_multi_query)>1)
						{
							$int_verse_end		=	$db->quote($obj_multi_query[1]);	
							$query .= (' a.verse_id>='.$int_verse_start.' AND a.verse_id<='.$int_verse_end);
						}
						else
						{
							$query .= (' a.verse_id ='.$int_verse_start);
						}
						$query .= (')');
						$y++;
					}
					$query .= (") ORDER BY a.book_id, a.chapter_id, a.verse_id"); 	
				}
			$db->setQuery($query);
			$data = $db->loadObjectList();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}		
		return $data;
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
	protected function fnc_get_first_bible_record()
	{
		try 
		{
			$db = JFactory::getDbo();
			$query  = $db->getQuery(true);
			$query->select('alias');
			$query->from('`#__zefaniabible_bible_names`');	
			$query->where("publish = 1");
			$query->order('id');		
			$db->setQuery($query,0, 1);
			$data = $db->loadResult();
		}
		catch (JException $e)
		{
			$this->setError($e);
		}
		return $data;			
	}
	protected function fnc_make_dialog_box($str_matches, $str_scripture_tmp)
	{
		JHtml::_('bootstrap.popover');
		$str_scripture = '<small id="zef-scripture-popover" class="hasPopover" data-placement="top" title="'.$str_matches.'" data-content="'.htmlentities($str_scripture_tmp).'">'.$str_matches.'</small>';
		return 	$str_scripture;
	}
}
?>