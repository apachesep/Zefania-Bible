<?php
defined('_JEXEC') or die('Restricted access');
ZefaniabibleHelper::headerDeclarations();
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');
$this->token = JUtility::getToken();
?>

<?
class BibleCommentary
{

	public function __construct()
	{
		/*
			a = commentary
			b = book
			c = chapter
			d = verse	
		*/		

	}
}

 $cls_Bible_commentary = new BibleCommentary();

?>
