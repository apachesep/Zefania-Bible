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
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_BASE."/plugins/content/zefaniascripturelinks/", 'en-GB', true);
		//english will be used by Biblegateway
		for($h = 1; $h <= 66; $h ++)
		{
			$arr_english_text = explode('|',mb_strtolower(JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_BOOK_NAME_'.$h,'UTF-8')));
			$this->arr_Bible_books_english[$h] = $arr_english_text[0];
		}	
		$jlang->load('plg_content_zefaniascripturelinks', JPATH_BASE."/plugins/content/zefaniascripturelinks/", null, true);
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
					
		JHTML::_('behavior.modal');
		
		if($this->flg_use_new_tooltip)
		{
			// JQuery Dialog box
			$document->addScript('//code.jquery.com/jquery-1.9.1.js');
			$document->addScript('//code.jquery.com/ui/1.10.4/jquery-ui.js');	
			$document->addStyleSheet('//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');		
			if($this->flg_jquery_no_conflict)
			{
				$document->addScript('/plugins/content/zefaniascripturelinks/helpers/noconflict.js');
			}
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
		JFactory::getLanguage()->load('com_zefaniabible', 'components/com_zefaniabible', null, true);
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
		
		if($this->flg_auto_replace)
		{			
			$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})?\b(".$this->str_Bible_books.")(\.)?(\s)(\d{1,3})([:,](?=\d))?(\d{1,3})?[-]?(\d{1,3})?([,](?=\d))?(\d{1,3})?([:](?=\d))?(\d{1,3})?[-]?(\d{1,3})?(\{\/zefaniabible\})?/iu";
			$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);	
		}else{
			//$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})\b(".$this->str_Bible_books.")(\s)(\d{1,3})([:,](?=\d))?(\d{1,3})?[-]?(\d{1,3})?([,](?=\d))?(\d{1,3})?([:](?=\d))?(\d{1,3})?[-]?(\d{1,3})?(\{\/zefaniabible\})/iu";
			$str_match_fuction = "/(?=\S)(\{zefaniabible*(.*?)\})\b(".$this->str_Bible_books.")(\s)(\d{1,3})([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?([:,-](?=\d))?(\d{1,3})?(\{\/zefaniabible\})/iu";
			$row->text = preg_replace_callback( $str_match_fuction, array( &$this, 'fnc_Make_Scripture'),  $row->text);				
		}	
      	return true;
	}

	private function fnc_Make_Scripture(&$arr_matches)
	{
		$str_scripture = "";
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
		$flg_add_title = 0;
		$str_label = '';
		$flg_use_multi_query = 0;
		$str_match_fuction = "#{/zefaniabible}#";
		$str_match_fuction_v2 = "#{zefaniabible*(.*?)}#";	
		$str_scripture = preg_replace( $str_match_fuction, '', preg_replace( $str_match_fuction_v2, '', $arr_matches[0] ));
		$str_scripture_book_name = $arr_matches[3];
		$d = 0;
		
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
				switch (true)
				{				
					case preg_match('/^([0-9]{1,3})-([0-9]{1,3})$/',$str_passages):		//Gen 1-4		 
					case preg_match('/^([0-9]{1,3})$/',$str_passages):					// Gen 1
					 	$arr_split_verses = preg_split('#[-]#',$str_passages); 			// split on hyphen
						if(count($arr_split_verses) == 2)
						{
							list($str_begin_chap,$str_end_chap) = $arr_split_verses;
						}
						else
						{
							list($str_begin_chap) = $arr_split_verses;
							$str_end_chap = '0';
						}
						$str_begin_verse = '0';
						$str_end_verse = '0';
						break;
						
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):   				// Gen 1:1
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3})$/',$str_passages): 	// Gen 1:1-4
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages); 					// split on colon and hyphen
						if(count($arr_split_verses) == 3)
						{
							list($str_begin_chap,$str_begin_verse,$str_end_verse) = $arr_split_verses;
						}
						else
						{
							list($str_begin_chap,$str_begin_verse) = $arr_split_verses;
							$str_end_verse = '0';
						}
						$str_end_chap = '0';							
						break;	
						
					case preg_match('/^([0-9]{1,3}):([0-9]{1,3})-([0-9]{1,3}):([0-9]{1,3})$/',$str_passages):	// Gen 2:3-3:3
					 	$arr_split_verses = preg_split('#[:-]+#',$str_passages);								// split on colon and hyphen 
						list($str_begin_chap,$str_begin_verse,$str_end_chap,$str_end_verse) = $arr_split_verses;
				  		break;
						
					case preg_match('/^([0-9]{1,3})([:])([0-9]{1,3})(([-])([0-9]{1,3}))?(([,])([0-9]{1,3}))/',$str_passages): 	// Gen 1:1-4,5...
						$arr_split_verses = preg_split('#[:]#',$str_passages); 													// split on colon
						list($str_begin_chap,$arr_verse_ranges) = $arr_split_verses;
						$arr_verse_ranges = explode(',',$arr_verse_ranges);
						$arr_multi_query = "";
						foreach ($arr_verse_ranges as $arr_verse_range)
						{
							$arr_multi_query[$d] = explode("-", $arr_verse_range); 
							$d++;
						}
						$flg_use_multi_query = 1;
						break;
						
					default:
						break;	
				}
				break;
			}
		}
		// set new alias 
		if($str_new_alias != "")
		{
			$str_alias = $str_new_alias;
			$flg_add_title = 1;
		}else{
			$str_alias = $this->str_default_alias;
		}
		// don't execute lookup for regular link
		if($flg_insert_method)
		{
			$arr_verses = $this->fnc_Find_Bible_Passage($str_alias, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse,$arr_multi_query);
		}

		switch ($flg_insert_method)
		{
			case 1:
				$str_scripture = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title,$arr_multi_query,$flg_use_multi_query,$str_passages );
				break;
			case 2:
				$str_scripture = $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias, $str_label);
				break;
			case 3:
				if($this->flg_use_new_tooltip)
				{
					$str_scripture_tmp = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title, $arr_multi_query,$flg_use_multi_query,$str_passages );				
					$str_scripture = $this->fnc_make_dialog_box($str_scripture,$str_scripture_tmp);		
				}
				else
				{
					$str_scripture_temp = $this->fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title,$arr_multi_query,$flg_use_multi_query,$str_passages );
					$str_scripture = JHTML::tooltip($str_scripture_temp,'', '', $str_scripture, '', false,'hasTip-zefania');		
				}
				break;
			default:
				$str_scripture = $this->fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $str_alias,'' );
				break;				
		}

		return $str_scripture;
	}
	protected function fnc_create_link($str_Bible_book_id, $str_begin_chap, $str_end_chap=0, $str_begin_verse=0, $str_end_verse=0, $str_alias, $str_scripture_name)
	{
			// 0 = Zefania
			// 1 = BibleGateway
		$verse = '';
		$str_link = '';
		
		$str_link_name = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id)."&nbsp;&nbsp;"; 
		switch(true)
    	{
			case ($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse):
				$str_link = $str_begin_chap;
				break;			
			case ($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse):
				$str_link = $str_begin_chap.":".$str_begin_verse;
				break;
			case ($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse):
				$str_link = $str_begin_chap.":".$str_begin_verse."-".$str_end_verse;
				break;
			case ($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse):
				$str_link =$str_begin_chap."-".$str_end_chap;
				break;
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
			// modal bocx coding begins here
			$temp = 'a='.$str_alias.'&b='.$str_Bible_book_id.'&c='.$str_begin_chap.'&d='.$str_begin_verse.'&e='.$str_end_chap.'&f='.$str_end_verse;
			$str_pre_link = '<a title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_name.$str_link.'" target="blank" href="index.php?view=scripture&option=com_zefaniabible&tmpl=component&'.$temp.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}">';		
			$verse = $str_pre_link.$str_link_label.'</a>';
			// modal bocx coding ends here			
		}
		else
		{
			// Bible gateway coding begins here
			$str_link_url = 'http://classic.biblegateway.com/passage/index.php?search='.urlencode($this->arr_Bible_books_english[$str_Bible_book_id]." ".$str_link).';&version='.$this->int_BibleGateway_id.';&interface=print';			
			$str_pre_link = '<a title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_link_label.'" target="blank" href="'.$str_link_url.'" class="modal" rel="{handler: \'iframe\', size: {x:'.$this->int_modal_box_width.',y:'.$this->int_modal_box_height.'}}">';
			$verse = $str_pre_link.$str_link_label .'</a>';
			// Bible gateway coding ends here			
		}
		return $verse;
	}
	// used for creating html to insert directly into page
	protected function fnc_create_text_link($arr_verses, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse, $flg_add_title, $arr_multi_query,$flg_use_multi_query,$str_passages)
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
							$verse = $verse.'<div class="zef_content_scripture">';
							$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_passages;
							if($flg_add_title)
							{
								$verse = $verse.' - '.$obj_verses->bible_name;
							}
							$verse = $verse.	'</div><div class="zef_content_verse" >';
						}
					}
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';
					if($x == $int_verse_cnt)
					{
						$verse = $verse.'</div></div>';
					}
					$x++;					
					break;									
				// Genesis 1:1
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					$verse = 		'<div class="zef_content_scripture">';
					$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_begin_chap.':'.$str_begin_verse;
					if($flg_add_title)
					{
						$verse = $verse.' - '.$obj_verses->bible_name;
					}
					$verse = $verse.	'</div><div class="zef_content_verse"><div class="odd">'.$obj_verses->verse.'</div></div>';
					$verse = $verse.'</div>';					
					break;
					
				// Genesis 1:1-3
				case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse)and(!$flg_use_multi_query)):
					if($obj_verses->verse_id == $str_begin_verse)
					{
						$verse = $verse.'<div class="zef_content_scripture">';
						$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_verse;
						if($flg_add_title)
						{
							$verse = $verse.' - '.$obj_verses->bible_name;
						}
						$verse = $verse.	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';
					if($x == $int_verse_cnt)
					{
						$verse = $verse.'</div></div>';
					}
					$x++;	
					break;	
						
				// Genesis 1		
				case (($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					if($obj_verses->verse_id == '1')
					{
						$verse = $verse.'<div class="zef_content_scripture">';
						$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_begin_chap;
						if($flg_add_title)
						{
							$verse = $verse.' - '.$obj_verses->bible_name;
						}
						$verse = $verse.	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';
					if($x == $int_verse_cnt)
					{	
						$verse = $verse.'</div></div>';
					}
					$x++;					
					break;
					
				// Genesis 1-2
				case (($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					if(($obj_verses->verse_id == '1')and($str_begin_chap == $obj_verses->chapter_id))
					{
						$verse = $verse.'<div class="zef_content_scripture">';
						$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_begin_chap.'-'.$str_end_chap;
						if($flg_add_title)
						{
							$verse = $verse.' - '.$obj_verses->bible_name;
						}
						$verse = $verse.	'</div><div class="zef_content_verse" >';
					}		
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse = $verse. '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$obj_verses->chapter_id.'</div>';
					}
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';		
					if($x == $int_verse_cnt)
					{	
						$verse = $verse.'</div></div>';	
					}	
					$x++;				
					break;
					
				// Genesis 1:30-2:3
				case (($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse)and(!$flg_use_multi_query)):
					if(($obj_verses->verse_id == $str_begin_verse)and($str_begin_chap == $obj_verses->chapter_id))
					{
						$title = JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_begin_chap.':'.$str_begin_verse.'-'.$str_end_chap.':'.$str_end_verse;
						if($flg_add_title)
						{
							$title = $title.' - '.$obj_verses->bible_name;
						}
						$verse = $verse.'<div class="zef_content_scripture">';
						$verse = $verse.	'<div class="zef_content_title">'.$title.'</div>';
						$verse = $verse.	'<div class="zef_content_verse" >';							
					}
					if(($obj_verses->verse_id == '1')and($str_begin_chap != $obj_verses->chapter_id))
					{
						$verse = $verse. '<hr class="zef_content_subtitle_hr"><div class="zef_content_subtitle">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$obj_verses->chapter_id.'</div>';
					}							
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}		
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';	
					if($x == $int_verse_cnt)
					{	
						$verse = $verse.'</div></div>';			
					}
					$x++;				
					break;	
				//multi verse query
				case (count($arr_multi_query>1)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)and(!$flg_use_multi_query)):
					echo 'count iis:'.($arr_multi_query[0][0])."<br>";
					echo "array is: ".$arr_multi_query."<br>";
					echo "I'm Here printing this verse: ";

					foreach($arr_multi_query as $obj_query)
					{
						print_r($obj_query);
					}
					if($obj_verses->verse_id == $arr_multi_query[0][0])
					{
						$verse = $verse.'<div class="zef_content_scripture">';
						$verse = $verse.	'<div class="zef_content_title">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$str_Bible_book_id).'&nbsp;&nbsp;'.$str_passages;
						if($flg_add_title)
						{
							$verse = $verse.' - '.$obj_verses->bible_name;
						}
						$verse = $verse.	'</div><div class="zef_content_verse" >';
					}
					if ($x % 2 )
					{
						$verse = $verse.'<div class="odd">';
					}
					else
					{
						$verse = $verse.'<div class="even">';
					}
					$verse = $verse.	'<div class="zef_content_verse_id" >'.$obj_verses->verse_id.'</div>';
					$verse = $verse.	'<div class="zef_content_verse_text">'.$obj_verses->verse.'</div>';
					$verse = $verse.	'<div style="clear:both"></div>';
					$verse = $verse.'</div>';
					if($x == $int_verse_cnt)
					{
					//	$verse = $verse.'</div></div>';
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
	protected function fnc_Find_Bible_Passage($str_alias, $str_Bible_book_id, $str_begin_chap, $str_end_chap, $str_begin_verse, $str_end_verse,$arr_multi_query)
	{
		try 
		{
			$db		= JFactory::getDbo();
			$data = '';
			$query  = $db->getQuery(true);
			$query->select("a.book_id, a.chapter_id, a.verse_id, a.verse, b.bible_name FROM `#__zefaniabible_bible_text` AS a");
			$query->innerJoin('`#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
			$query->where("a.book_id=".(int)$str_Bible_book_id);
			$query->where("b.alias='".trim($str_alias)."'");
				switch (true)
				{			
					// Genesis 1
					case (($str_begin_chap)and(!$str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)):
						$query->where("a.chapter_id=".(int)$str_begin_chap);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 						
						break;
					// Genesis 1-2
					case (($str_begin_chap)and($str_end_chap)and(!$str_begin_verse)and(!$str_end_verse)):
						$query->where("a.chapter_id>=".(int)$str_begin_chap." AND a.chapter_id<=".(int)$str_end_chap);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:1
					case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and(!$str_end_verse)):
						$query->where("a.chapter_id=".(int)$str_begin_chap." AND a.verse_id=".(int)$str_begin_verse);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:1-2
					case (($str_begin_chap)and(!$str_end_chap)and($str_begin_verse)and($str_end_verse)):
						$query->where("a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " AND a.verse_id<=".$str_end_verse);
						$query->order("a.book_id, a.chapter_id, a.verse_id"); 					
						break;
					// Genesis 1:2-2:3
					case (($str_begin_chap)and($str_end_chap)and($str_begin_verse)and($str_end_verse)):
						$str_tmp_old_query = $query;
						$query	= "SELECT * FROM( ".$query . " AND a.chapter_id=".(int)$str_begin_chap." AND a.verse_id>=".(int)$str_begin_verse. " ORDER BY a.verse_id ASC ) as c";
						$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id=".$str_end_chap." AND a.verse_id<=".$str_end_verse." ORDER BY a.verse_id ASC) as d";
						if(($str_end_chap - $str_begin_chap)>1)
						{
							$query  = $query. " UNION SELECT * FROM( ".$str_tmp_old_query." AND a.chapter_id>=".($str_begin_chap+1)." AND a.chapter_id<=".($str_end_chap-1)." ORDER BY a.verse_id ASC) as e";
						}
						$query  = $query. " ORDER BY chapter_id, verse_id";					
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
					$query = $query.(' INNER JOIN `#__zefaniabible_bible_names` AS b ON a.bible_id = b.id');
					$query = $query.(" WHERE a.book_id=".(int)$str_Bible_book_id." AND b.alias='".trim($str_alias)."' AND a.chapter_id=".(int)$str_begin_chap." AND (");
					$y=1;				
					foreach ($arr_multi_query as $obj_multi_query)
					{
						if($y > 1)
						{
							$query = $query.(' OR ');
						}
						$query = $query.('(');
						if(count($obj_multi_query)>1)
						{
							$query = $query.(' a.verse_id>='.$obj_multi_query[0].' AND a.verse_id<='.$obj_multi_query[1]);
						}
						else
						{
							$query = $query.(' a.verse_id ='.$obj_multi_query[0]);
						}
						$query = $query.(')');
						$y++;
					}
					$query = $query.(") ORDER BY a.book_id, a.chapter_id, a.verse_id"); 	
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
		$document = JFactory::getDocument();

			$str_id = "-".rand(0,999999999).'-'.uniqid();
			$document->addScriptDeclaration('
				jQuery( document ).ready(function( $ )
				{
					$( "#dialog'.$str_id.'" ).dialog(
					{
						autoOpen: false,
						show:
						{
							effect: "'.$this->str_tooltip_effect.'",
							duration: '.$this->int_tooltip_duration.'
						},
						hide: 
						{
							effect: "'.$this->str_tooltip_effect.'",
							duration: '.$this->int_tooltip_duration.'
						},
						width: window_width,
						maxHeight: window_height,
						draggable: false,
						closeOnEscape: true,
						modal: true,
					});
					$( "#opener'.$str_id.'" ).mouseover(function() 
					{
						$( "#dialog'.$str_id.'" ).dialog( "open" );
					});
					$( "#dialog'.$str_id.'" ).mouseleave(function() 
					{
						$( "#dialog'.$str_id.'" ).dialog( "close" );
					});					
				});
			');				
			$str_scripture = '<a title="'. JText::_('PLG_ZEFANIA_BIBLE_SCRIPTURE_BIBLE_LINK')." ".$str_matches.'" id="opener'.$str_id.'">'.$str_matches.'</a>';
			$str_scripture = $str_scripture. '<div class="zef_scripture_tooltip" id="dialog'.$str_id.'" title="'.$str_matches.'">';
			$str_scripture = $str_scripture. '<p>'.$str_scripture_tmp.'</p>';
			$str_scripture = $str_scripture. '</div>';	
		return 	$str_scripture;
	}
}
?>