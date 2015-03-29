<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	Users
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

defined('_JEXEC') or die;


/**
 * Build the route for the com_zefaniabible component
 *
 * @param	array	An array of URL arguments
 *
 * @return	array	The URL arguments to use to assemble the subsequent URL.
 */
function ZefaniabibleBuildRoute(&$query){

	$segments 	= array();
	$view = 'standard';
	if(isset($query['view']))
	{
		$view = $query['view'];
		$segments[] = $view;
		unset( $query['view'] );
	}
	switch($view)
	{			
		case "books":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}	
			if(isset($query['variant']))
			{
				$segments[] = $query['variant'];
				unset( $query['variant'] );		
			}
			break;
								
		case "biblerss":	
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['book']))
			{							
				$segments[] = $query['book'];
				unset( $query['book'] );	
			}
			if(isset($query['chapter']))
			{			
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );				
			}
			if(isset($query['variant']))
			{
				$segments[] = $query['variant'];
				unset( $query['variant'] );		
			}
			break;	
			
		case "calendar":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}	
			if(isset
			($query['plan']))
			{
				$segments[] = $query['plan'];
				unset( $query['plan'] );		
			}
					
			if(isset($query['year']))
			{
				$segments[] = $query['year'];
				unset( $query['year'] );		
			}
			
			if(isset($query['month']))
			{
				$segments[] = $query['month'];
				unset( $query['month'] );		
			}
			break;				
						
		case "compare":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['bible2']))
			{								
				$segments[] = $query['bible2'];
				unset( $query['bible2'] );
			}
			if(isset($query['book']))
			{						
				$segments[] = $query['book'];
				unset( $query['book'] );				
			}
			if(isset($query['chapter']))
			{
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );
			}
			break;
			
		case "commentary":
			if(isset($query['com']))
			{		
				$segments[] = $query['com'];
				unset( $query['com'] );	
			}
			if(isset($query['book']))
			{						
				$segments[] = $query['book'];

				unset( $query['book'] );				
			}				
			if(isset($query['chapter']))
			{
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );
			}
			if(isset($query['verse']))
			{			
				$segments[] = $query['verse'];
				unset( $query['verse'] );
			}
			break;
			
		case "player":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}			
			if(isset($query['book']))
			{						
				$segments[] = $query['book'];
				unset( $query['book'] );				
			}		
			break;
			
		case "plan":
		case "planrss":
			if(isset($query['plan']))
			{		
				$segments[] = $query['plan'];
				unset( $query['plan'] );
			}
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['start']))
			{			
				$segments[] = $query['start'];
				unset( $query['start'] );
			}
			if(isset($query['items']))
			{				
				$segments[] = $query['items'];
				unset( $query['items'] );
			}
			if(isset($query['variant']))
			{
				$segments[] = $query['variant'];
				unset( $query['variant'] );		
			}				
			if(isset($query['type']))
			{
				$segments[] = $query['type'];
				unset( $query['type'] );		
			}			
			break;
			
		case "references":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}	
			if(isset($query['book']))
			{					
				$segments[] = $query['book'];
				unset( $query['book'] );				
			}
			if(isset($query['chapter']))
			{				
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );
			}
			if(isset($query['verse']))
			{				
				$segments[] = $query['verse'];
				unset( $query['verse'] );	
			}
			break;
			
		case "reading":				
		case "readingrss":
			if(isset($query['plan']))
			{		
				$segments[] = $query['plan'];
				unset( $query['plan'] );
			}
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['day']))
			{			
				$segments[] = $query['day'];
				unset( $query['day'] );	
			}
			if(isset($query['variant']))
			{
				$segments[] = $query['variant'];
				unset( $query['variant'] );		
			}				
			if(isset($query['type']))
			{
				$segments[] = $query['type'];
				unset( $query['type'] );		
			}
		
			break;
		case "sitemap":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}		
			break;
		case "scripture":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['book']))
			{							
				$segments[] = $query['book'];
				unset( $query['book'] );	
			}
			if(isset($query['chapter']))
			{			
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );				
			}
			if(isset($query['verse']))
			{						
				$segments[] = $query['verse'];
				unset( $query['verse'] );
			}
			if(isset($query['endchapter']))
			{			
				$segments[] = $query['endchapter'];
				unset( $query['endchapter'] );
			}
			if(isset($query['endverse']))
			{							
				$segments[] = $query['endverse'];
				unset( $query['endverse'] );					
			}
			break;
			
		case "strong":
			if(isset($query['dict']))
			{
				$segments[] = $query['dict'];
				unset( $query['dict'] );
			}
			if(isset($query['item']))
			{			
				$segments[] = $query['item'];
				unset( $query['item'] );		
			}
			break;
			
		case "standard":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['book']))
			{							
				$segments[] = $query['book'];
				unset( $query['book'] );	
			}
			if(isset($query['chapter']))
			{			
				$segments[] = $query['chapter'];
				unset( $query['chapter'] );				
			}
			if(isset($query['type']))
			{
				$segments[] = $query['type'];
				unset( $query['type'] );		
			}			
			break;
			
		case "verserss":
			if(isset($query['bible']))
			{
				$segments[] = $query['bible'];
				unset( $query['bible'] );
			}
			if(isset($query['day']))
			{			
				$segments[] = $query['day'];
				unset( $query['day'] );	
			}
			if(isset($query['variant']))
			{
				$segments[] = $query['variant'];
				unset( $query['variant'] );		
			}					
			break;
						
		default:
			break;
	}
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

	switch($segments[0])
	{			
		case "books":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['variant'] = $segments[$nextPos];
				$nextPos++;			
			}				
			break;
			
		case "biblerss":	
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['variant'] = $segments[$nextPos];
				$nextPos++;			
			}			
			break;	
			
		case "calendar":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['plan'] = $segments[$nextPos];
				$nextPos++;			
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['year'] = $segments[$nextPos];
				$nextPos++;			
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['month'] = $segments[$nextPos];
				$nextPos++;			
			}
			break;
						
		case "compare":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['bible2'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			break;
			
		case "commentary":
			if (isset($segments[$nextPos]))
			{			
				$vars['com'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['verse'] = $segments[$nextPos];
				$nextPos++;
			}
			break;
			
		case "plan":
			if (isset($segments[$nextPos]))
			{	
				$vars['plan'] = $segments[$nextPos];
				$nextPos++;	
			}
			if (isset($segments[$nextPos]))
			{					
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			break;
			
		case "player":
			if (isset($segments[$nextPos]))
			{			
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['book'] = $segments[$nextPos];
				$nextPos++;			
			}
			break;
			
		case "planrss":
			if (isset($segments[$nextPos]))
			{			
				$vars['plan'] = $segments[$nextPos];
				$nextPos++;		
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['start'] = $segments[$nextPos];
				$nextPos++;				
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['items'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['variant'] = $segments[$nextPos];
				$nextPos++;			
			}				
			if (isset($segments[$nextPos]))
			{				
				$vars['type'] = $segments[$nextPos];
				$nextPos++;			
			}			
			break;
			
		case "references":
			if (isset($segments[$nextPos]))
			{			
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['verse'] = $segments[$nextPos];
				$nextPos++;	
			}
			break;			
				
		case "reading":
		case "readingrss":
			if (isset($segments[$nextPos]))
			{			
				$vars['plan'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['day'] = $segments[$nextPos];
				$nextPos++;	
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['variant'] = $segments[$nextPos];
				$nextPos++;			
			}				
			if (isset($segments[$nextPos]))
			{				
				$vars['type'] = $segments[$nextPos];
				$nextPos++;			
			}	
			break;		
		case "sitemap":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}		
			break;
		case "scripture":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{		
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{		
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{						
				$vars['verse'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{		
				$vars['endchapter'] = $segments[$nextPos];
				$nextPos++;		
			}
			if (isset($segments[$nextPos]))
			{		
				$vars['endverse'] = $segments[$nextPos];
				$nextPos++;					
			}
			break;
			
		case "strong":
			if (isset($segments[$nextPos]))
			{		
				$vars['dict'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['item'] = $segments[$nextPos];
				$nextPos++;
			}
			break;
			
		case "standard":
			if (isset($segments[$nextPos]))
			{		
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['book'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{	
				$vars['chapter'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['type'] = $segments[$nextPos];
				$nextPos++;			
			}			
			break;
			
		case "verserss":
			if (isset($segments[$nextPos]))
			{			
				$vars['bible'] = $segments[$nextPos];
				$nextPos++;
			}
			if (isset($segments[$nextPos]))
			{			
				$vars['day'] = $segments[$nextPos];
				$nextPos++;	
			}
			if (isset($segments[$nextPos]))
			{				
				$vars['variant'] = $segments[$nextPos];
				$nextPos++;			
			}									
			break;
						
		default:
			break;
	}		
	return $vars;
}

