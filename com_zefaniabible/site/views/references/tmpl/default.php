<?php
defined('_JEXEC') or die('Restricted access');
$mdl_zef_bible_helper = new ZefaniabibleHelper();
$mdl_zef_bible_helper->headerDeclarations();
JHTML::addIncludePath(JPATH_COMPONENT.'/helpers');
?>

<?
class BibleReference
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

 $cls_Bible_reference = new BibleReference();

?>
