<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	Contents
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

defined('_JEXEC') or die;


/**
 * Build the route for the com_zefaniabible component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function ZefaniabibleBuildRoute(&$query){

	$segments = array();
	if(isset($query['view']))
	{
		$view = $query['view'];
		$segments[] = $view;
		unset( $query['view'] );
	}

	if(isset($query['layout']))
	{
		$segments[] = $query['layout'];
		unset( $query['layout'] );
	}


	if(isset($query['id']))
	{
		if(in_array($view, array()))
		{
			$segments[] = (is_array($query['id'])?implode(',', $query['id']):$query['id']);
			unset( $query['id'] );
		}
	};


	return $segments;
}


/**
 * Parse the segments of a URL.
 *
 * @param	array	The segments of the URL to parse.
 *
 * @return	array	The URL attributes to be used by the application.
 */
function ZefaniabibleParseRoute($segments)
{
	$vars = array();


	$vars['view'] = $segments[0];

	$nextPos = 1;
	if (isset($segments[$nextPos]))
	{
		$vars['layout'] = $segments[$nextPos];
		$nextPos++;
	}

	//Item layout : get the cid value
	if(in_array($vars['view'], array()) && isset($segments[$nextPos]))
	{
		$slug = $segments[$nextPos];
		$id = explode( ':', $slug );
		$vars['id'] = (int) $id[0];

		$nextPos++;
	}

	return $vars;
}

