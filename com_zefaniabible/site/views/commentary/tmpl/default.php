<?php
defined('_JEXEC') or die('Restricted access');
//ZefaniabibleHelper::headerDeclarations();
$mdl_zef_bible_helper = new ZefaniabibleHelper();
$mdl_zef_bible_helper->headerDeclarations();
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');
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
