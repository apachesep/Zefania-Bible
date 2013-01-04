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

defined('_JEXEC') or die('Restricted access');



$isNew		= ($this->zefaniascriptureitem->id < 1);
$actionText = $isNew ? JText::_( "ZEFANIABIBLE_NEW" ) : JText::_( "ZEFANIABIBLE_EDIT" );
?>

<fieldset class="fieldsform">
	<legend><?php echo $actionText;?></legend>

	<table class="admintable">

		<tr>
			<td align="right" class="key">
				<label for="bible_id">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_BIBLE_VERSION" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'bible_id',
												'dataObject' => $this->zefaniascriptureitem,
												'list' => $this->lists['fk']['bible_id'],
												'listKey' => 'id',
												'labelKey' => 'bible_name',
												'nullLabel' => "ZEFANIABIBLE_FILTER_NULL_SELECT_BIBLE_VERSION"
												));
				?>               
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="book_id">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_BOOK_NAME" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.select', array(
												'dataKey' => 'book_id',
												'dataObject' => $this->zefaniascriptureitem,
												'list' => $this->lists['fk']['book_name'],
												'listKey' => 'id',
												'labelKey' => 'bible_book_name',
												'nullLabel' => "ZEFANIABIBLE_JSEARCH_SELECT_BOOK_ID"
												));
				?>        
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="chapter_id">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_CHAPTER_NUMBER" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'chapter_id',
												'dataObject' => $this->zefaniascriptureitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="verse_id">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_VERSE_NUMBER" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.text', array(
												'dataKey' => 'verse_id',
												'dataObject' => $this->zefaniascriptureitem,
												'size' => "32"
												));
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="key">
				<label for="verse">
					<?php echo JText::_( "ZEFANIABIBLE_FIELD_VERSE" ); ?> :
				</label>
			</td>
			<td>
				<?php echo JDom::_('html.form.input.editor', array(
												'dataKey' => 'verse',
												'dataObject' => $this->zefaniascriptureitem,
												'cols' => "80",
												'rows' => "10",
												'width' => "",
												'height' => "px"
												));
				?>                
			</td>
		</tr>


	</table>
</fieldset>
