
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

function fnc_scripture(obj){
	
	var lang = document.getElement('html').getProperty('lang');	
	lang = lang.substring(0,2);	
	var current_url = window.location.href;
	var test_lang = "/"+lang+"/";
	var working_url = "";
	var str_verse = "";
	var int_temp_verse = 0;	
	if(current_url.indexOf(test_lang) > 0)
	{
		working_url = window.location.protocol+"//"+window.location.hostname+"/"+lang;
	}else{
		working_url = window.location.protocol+"//"+window.location.hostname;
	}
	var url = working_url + "/index.php?option=com_zefaniabible&view=scripture&bible="+obj.bible+"&book="+obj.book+"&chapter="+obj.chapter+"&verse="+obj.verse+"&endchapter="+obj.endchapter+"&endverse="+obj.endverse+"&type=1&variant=json3&format=raw&tmpl=component";
	jQuery.getJSON( url, function( data ){
		jQuery.each(data, function( i, item ){
			jQuery.each(item.scripture, function( j, jitem ) {
				if(item.scripture.length > 1){
					if(((int_temp_verse == 0)&&((item.endchap != 0)&&(item.endchap != item.beginchap)))||				
					((jitem.verseid == 1)&&(int_temp_verse >= jitem.verseid)))
					{
						str_verse += '<div class="zef_content_title">'+item.bookname+' '+jitem.chapterid+'</div>';
					}

					str_verse += '<div id="zef_content_verse" style="margin-left:5px;"><div id="zef_content_verse_id" style="float:left">'+jitem.verseid+'</div><div id="zef_content_verse_text" style="float:left;margin-left:5px;width:90%;">'+jitem.verse+'</div></div><div style="clear:both"></div>';
					int_temp_verse = jitem.verseid;
				}else{
					str_verse = '<div id="zef_content_verse_text" style="margin-left:5px;">'+jitem.verse+'</div>';
				}
			});
		});
		switch(obj.type)	{			
			case "dialog":
				jQuery( ".modal-body-"+obj.unique_id).html(str_verse);
				break;
			case "tooltip":	
			default:
				jQuery( ".div-"+obj.unique_id+" p").html(str_verse);
				break;
		}
	});	
}
