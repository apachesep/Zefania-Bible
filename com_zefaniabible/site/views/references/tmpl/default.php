<?php
defined('_JEXEC') or die('Restricted access');
?>
<?php 
	if($this->item->flg_enable_debug == 1)
	{
		echo '<!--';
		print_r($this->item);
		echo '-->';
	}
?>