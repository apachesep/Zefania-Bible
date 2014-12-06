
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

function fnc_scripture(obj)
{
	var url = "index.php?option=com_zefaniabible&view=scripture&bible="+obj.bible+"&book="+obj.book+"&chapter="+obj.chapter+"&verse="+obj.verse+"&endchapter="+obj.endchapter+"&endverse="+obj.endverse+"&type=1&tmpl=component";	
	switch(obj.type)
	{			
		case "dialog":
			fnc_dialog(url, obj);
			break;
		case "tooltip":
			fnc_tooltip(url, obj);
			break;		
		default:
			fnc_popover(url, obj);
			break;
	}
}
function fnc_popover(url, obj)
{
	jQuery.get( url, function( data ) 
	{
		jQuery( ".div-"+obj.unique_id+" p" ).html( data );
	});
}

function fnc_tooltip(url, obj)
{
	jQuery.get( url, function( data ) 
	{
		jQuery( ".div-"+obj.unique_id+" p" ).html( data );
	});
}

function fnc_dialog(url, obj)
{
	jQuery.get( url, function( data ) 
	{
		jQuery( ".modal-body-"+obj.unique_id).html(data);
	});
}
