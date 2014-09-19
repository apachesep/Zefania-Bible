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


<!-- SEARCH : filter_search : search on  + Chapter Number +  + Book Name > Bible Book Name  -->

	<div style="float:right;">
		<div style="float:left">
				<div class="btn-group pull-left hidden-phone">
					<button class="btn tip" onclick="this.form.submit();"><i class="icon-search"></i></button>
					<button class="btn tip" onclick="resetFilters()"><i class="icon-remove"></i></button>
				</div>
		</div>
	</div>
	<div style="float:right">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('ZEFANIABIBLE_SEARCH');?></label>
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('ZEFANIABIBLE_SEARCH'); ?>" id="filter_search" value="<?php echo $this->filters['search']->value; ?>" title="<?php echo JText::_('ZEFANIABIBLE_SEARCH'); ?>" />
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
							$arr_bible_version[] = array("value" => $ojb_bible_version->bible_name, 'text'=>$ojb_bible_version->bible_name);
						}		
						?>	                    
                    <select name="filter_bible_name" id="filter_bible_name" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSION');?></option>
                        <?php  echo JHtml::_('select.options', $arr_bible_version, 'value', 'text', $this->state->get('filter.bible_name'));?>
                    </select>
                                        
                    <?php
						$params = JComponentHelper::getParams( 'com_zefaniabible' );
						$int_primary_backend_book = $params->get('primary_book_backend');				
					?>
                        <select name="filter_book_id" id="filter_book_id" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_( "ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE" );?></option>	
                            <?php 
								for($x = 1; $x<=66; $x++)
								{
									if(($this->state->get('filter.book_id') == $x)or(($int_primary_backend_book == $x)and($this->state->get('filter.book_id') == '')))
									{
										echo '<option value="'.$x.'" selected="selected">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';
									}
									else
									{
										echo '<option value="'.$x.'">'.JText::_('ZEFANIABIBLE_BIBLE_BOOK_NAME_'.$x).'</option>';
									}
								}
							?>
						</select>
                    <?php
						$arr_bible_chapter = array();
						for($x = 1; $x<= $this->int_max_chapter; $x++)
						{
							$arr_bible_chapter[] = array("value" => $x, 'text'=> $x);
						}
					?>
					<select name="filter_chapter_id" id="filter_chapter_id" class="inputbox" onchange="this.form.submit()">
                        <option value="" ><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_CHAPTER');?></option>                      
                        <?php echo JHtml::_('select.options', $arr_bible_chapter, 'value', 'text', $this->state->get('filter.chapter_id'));?>
                    </select>
                    <?php
						$arr_bible_verse = array();
						for($x = 1; $x<= $this->int_max_verse; $x++)
						{
							$arr_bible_verse[] = array("value" => $x, 'text'=> $x);
						}
					?>                    
					<select name="filter_verse_id" id="filter_verse_id" class="inputbox" onchange="this.form.submit()">
                        <option value=""><?php echo JText::_('ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSE');?></option>
                        <?php echo JHtml::_('select.options', $arr_bible_verse, 'value', 'text', $this->state->get('filter.verse_id'));?>
                    </select>
                    
					</div>
		</div>
	</div>
	<div clear='all'></div>
</fieldset>
