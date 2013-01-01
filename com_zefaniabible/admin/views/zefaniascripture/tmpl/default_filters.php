<?php

/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Zefaniabible
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

defined('_JEXEC') or die('Restricted access'); ?>

<script language="javascript" type="text/javascript">
<!--


function resetFilters()
{
	if (typeof(jQuery) != 'undefined')
	{
		jQuery('.filters :input').val('');

	/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
		jQuery('#filter_order').val('');
		jQuery('#filter_orderDir').val('');
	*/
		document.adminForm.submit();
		return;
	}


/* TODO : Uncomment this if you want that the reset action proccess also on sorting values
	if ($('filter_order') != null)
	    $('filter_order').value='';
	if ($('filter_orderDir') != null)
	    $('filter_orderDir').value='';
*/

	document.adminForm.submit();
}

-->
</script>


<fieldset id="filters" class="filters">
	<legend><?php echo JText::_( "JSEARCH_FILTER_LABEL" ); ?></legend>



	<div style="float:right;">
		<div style="float:left">
				<div class="filter filter_buttons">
					<button onclick="this.form.submit();"><?php echo(JText::_("JSEARCH_FILTER_SUBMIT")); ?></button>
					<button onclick="resetFilters()"><?php echo(JText::_("JSEARCH_FILTER_CLEAR")); ?></button>
				</div>
		</div>
	</div>

	<div>
		<div style="float:left">     
			<!-- SELECT : Publish  -->

					<div class='filter filter_publish'>
						<?php
						$arr_bible_version = array();
						foreach($this->arr_Bibles_versions as $ojb_bible_version)
						{
							$arr_bible_version[] = array("value" => $ojb_bible_version->alias, 'text'=>$ojb_bible_version->bible_name);
						}
						?>	                    
                    <select name="filter_bibleversion" id="filter_bibleversion" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSION');?></option>
                        <?php echo JHtml::_('select.options', $arr_bible_version, 'value', 'text', $this->state->get('filter.bibleversion'));?>
                    </select>
                                        
						<?php
						$arr_bible_books = array();
						for($x = 1; $x< 66; $x++)
						{
							$arr_bible_books[] = array("value" => $x, 'text'=>JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x));
						}
						?>	
                     <select name="filter_biblebook" id="filter_biblebook" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE');?></option>
                        <?php echo JHtml::_('select.options', $arr_bible_books, 'value', 'text', $this->state->get('filter.biblebook'));?>
                    </select>
                    <?php
						$arr_bible_chapter = array();
						for($x = 1; $x<= $this->int_max_chapter; $x++)
						{
							$arr_bible_chapter[] = array("value" => $x, 'text'=> $x);
						}
					?>
					<select name="filter_biblechapter" id="filter_biblechapter" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_CHAPTER');?></option>
                        <?php echo JHtml::_('select.options', $arr_bible_chapter, 'value', 'text', $this->state->get('filter.biblechapter'));?>
                    </select>
                    <?php
						$arr_bible_verse = array();
						for($x = 1; $x<= $this->int_max_verse; $x++)
						{
							$arr_bible_verse[] = array("value" => $x, 'text'=> $x);
						}
					?>                    
					<select name="filter_bibleverse" id="filter_bibleverse" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSE');?></option>
                        <?php echo JHtml::_('select.options', $arr_bible_verse, 'value', 'text', $this->state->get('filter.bibleverse'));?>
                    </select>
                    
					</div>
		</div>
	</div>
	<div clear='all'></div>
</fieldset>
