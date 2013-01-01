<?php
defined('_JEXEC') or die('Restricted access');
ZefaniabibleHelper::headerDeclarations();
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');
$this->token = JUtility::getToken();
?>

<?
class BibleCommentary
{
	private $str_Full_Commentary_Path;
	private $str_Commentary_Book_XML_File;
	private $arr_commentary_final_output;
	private $int_bibleBookID;
	public $int_bibleVerse;
	private $str_Commentary_XML_Path;
	public $flg_show_drop_down_menu;
	public $str_verse_output;
	public $str_bibleLayout;
	private $arr_bibleIDs;
	public $int_maxBookChapters;
	public $int_maxBookVerses;
	public function __construct($comment, $bibleBooks)
	{
		/*
			a = book
			b = chapter
			c = verse
			d = flag use drop down		
		*/		
		$this->params = &JComponentHelper::getParams( 'com_zefaniabible' );
		$this->int_bibleBookID = JRequest::getInt('a');	
		$this->int_bibleChapter = JRequest::getInt('b');		
		$this->int_bibleVerse = JRequest::getInt('c');	
		$this->flg_show_drop_down_menu = JRequest::getInt('d', 0);
		$this->str_bibleLayout = JRequest::getCmd('layout','default');
		
		if($this->int_bibleBookID == 0)
		{
			$this->int_bibleBookID = 1;
			$this->int_bibleChapter = 1;
			$this->int_bibleVerse = 1;
			$this->flg_show_drop_down_menu = 1;
		}
		$this->str_Commentary_XML_Path = $this->params->get('xmlCommentaryPath', 'media/com_zefaniabible/commentary/');
		$this->commentaryLookup($comment);
		$this->generateChapterCommentary();
		$this->doc_page =& JFactory::getDocument();	
		$this->doc_page->setTitle(JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->int_bibleBookID)." ".$this->int_bibleChapter.":".$this->int_bibleVerse);	
	}
	protected function commentaryLookup($commentaries)
	{
		
		foreach($commentaries as $commentary)
		{
			$str_temp = $commentary->file_location;
			break;
		}
		$this->str_Full_Commentary_Path = $this->str_Commentary_XML_Path.$str_temp;
	}
	
	public function createBookDropDown($bibleBooks)
	{
		$x=1;
		foreach($bibleBooks as $bibleBook)
		{
			$arr_temp = "";
			$arr_temp[$x]['ordering'] = $bibleBook->ordering;
			if($this->int_bibleBookID == $bibleBook->ordering)
			{
				echo '<option value="'.$arr_temp[$x]['ordering']."-".str_replace(" ","-",JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_temp[$x]['ordering'])).'" selected="selected">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_temp[$x]['ordering']).'</option>';
				$this->int_bookIDLocation = $x;							
			}
			else
			{
				echo '<option value="'.$arr_temp[$x]['ordering']."-".str_replace(" ","-",JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_temp[$x]['ordering'])).'" >'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$arr_temp[$x]['ordering']).'</option>';
			}			
			$x++;				
		}
		if(!isset($this->int_bookIDLocation))
		{
			$this->int_bookIDLocation = 1;
		}
		
	}
	protected function generateChapterCommentary()
	{
		$str_Commentary_Book_XML_File = "";
		$arr_Commentary_XML_File = simplexml_load_file($this->str_Full_Commentary_Path);
		foreach($arr_Commentary_XML_File->book as $arr_Commentary_Book)
		{
			if($arr_Commentary_Book['id'] == $this->int_bibleBookID)
			{
				$str_Commentary_Book_XML_File = $arr_Commentary_Book;
				break;
			}
		}
		if($str_Commentary_Book_XML_File != "")
		{
			$arr_Commentary_Extension = pathinfo($this->str_Full_Commentary_Path);
			$str_Commentary_Path = $arr_Commentary_Extension['dirname'];
			$this->str_Commentary_Full_Path = $str_Commentary_Path."/".$str_Commentary_Book_XML_File;
			$arr_Commentary = simplexml_load_file($this->str_Commentary_Full_Path);
			$flg_call_back = 0;
			foreach($arr_Commentary->chapter as $arr_Commentary_Chapter)
			{				
				if($arr_Commentary_Chapter['value'] == $this->int_bibleChapter)
				{				
					foreach($arr_Commentary_Chapter->verse as $arr_comment_verse)
					{

						if($this->int_bibleVerse == $arr_comment_verse['value1'])
						{
							$this->str_verse_output = $this->str_verse_output.'<div class="zef_commentary"><div class="zef_commentary_heading">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->int_bibleBookID)." ".$this->int_bibleChapter.":".$arr_comment_verse['value1'].'</div><div class="zef_commentary_verse">'.$arr_comment_verse->asXML()."</div></div>";						
						}
						$this->int_maxBookVerses++;
					}
				}
				$this->int_maxBookChapters++;
			}
			if($this->int_bibleVerse > $this->int_maxBookVerses )
			{
				$this->int_bibleVerse = $this->int_maxBookVerses;
				$flg_call_back = 1;
			}
			if($this->int_bibleChapter > $this->int_maxBookChapters)
			{
				$this->int_bibleChapter = $this->int_maxBookChapters;
				$flg_call_back = 1;				
			}
			if($flg_call_back == 1)
			{
				$this->generateChapterCommentary();			
			}
		}
		else
		{
			JError::raiseWarning('',JText::_('ZEFANIABIBLE_COMMNETARY_NOT_AVAIL'));
		}
	}
}

 $cls_bibleBook = new BibleCommentary($this->comment, $this->bibleBooks);

if($cls_bibleBook->flg_show_drop_down_menu == 1)
{
?>
<form action="<?php echo JFactory::getURI()->toString(); ?>" method="post" id="adminForm" name="adminForm">
	<select name="a" id="book" class="inputbox" onchange="this.form.submit()">
		<?php $cls_bibleBook->createBookDropDown($this->bibleBooks); ?>
	</select>    
	<select name="b" id="chapter" class="inputbox" onchange="this.form.submit()">
		<?php 
		for( $x = 1; $x <= $cls_bibleBook->int_maxBookChapters; $x++)
		{
			if($x == $cls_bibleBook->int_bibleChapter)
			{
				echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8').'" selected="selected">'.$x.'</option>';
			}
			else
			{
				echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_BIBLE_CHAPTER'),'UTF-8').'">'.$x.'</option>';
			}
		}
		?>               
	</select>
	<select name="c" id="verse" class="inputbox" onchange="this.form.submit()">
		<?php 
		for( $x = 1; $x <= $cls_bibleBook->int_maxBookVerses; $x++)
		{
			if($x == $cls_bibleBook->int_bibleVerse)
			{
				echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_VERSE'),'UTF-8').'" selected="selected">'.$x.'</option>';
			}
			else
			{
				echo '<option value="'.$x.'-'.mb_strtolower(JText::_('ZEFANIABIBLE_VERSE'),'UTF-8').'">'.$x.'</option>';
			}
		}
		?>               
	</select>    
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getCmd('view');?>" />
    <input type="hidden" name="d" value="<?php echo $cls_bibleBook->flg_show_drop_down_menu; ?>" />    
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>"/>
</form>
<?php
}
echo $cls_bibleBook->str_verse_output;
?>
