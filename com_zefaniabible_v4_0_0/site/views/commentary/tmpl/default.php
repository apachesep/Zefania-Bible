<?php
defined('_JEXEC') or die('Restricted access');
?>
<div class="zef_commentary_image"><img src="<?php echo $this->item->str_com_default_image;?>"></div>
<div class="zef_commentary_title"><?php echo $this->item->str_commentary_name;?></div>
<div class="zef_commentary_book"><?php echo JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$this->item->int_Bible_Book_ID)." ".$this->item->int_Bible_Chapter.":".$this->item->int_Bible_Verse;?></div>
<div class="zef_commentary_verse"><?php echo JHtml::_('content.prepare',$this->item->str_commentary_text);?></div>