<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook           (100% Vitamin) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		1.6
* @package		ZefaniaBible
* @subpackage	
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
	if (!JComponentHelper::getComponent('com_zefaniabible', true)->enabled)
	{
		JError::raiseWarning('5', 'ZefaniaBible - Weekly Bible Reading Module - ZefaniaBible component is not installed or not enabled.');
		return;	
	}		
	$moduleclass_sfx	= htmlspecialchars($params->get('moduleclass_sfx'));
	
		$document = JFactory::getDocument();
		$document->addStyleSheet('/modules/mod_weekly_reading/css/weekly-reading.css');
				
		// load languages
		$jlang = JFactory::getLanguage();
		JFactory::getLanguage()->load('com_zefaniabible', JPATH_BASE, null, true);
		$jlang->load('mod_readingplan', JPATH_BASE, 'en-GB', true);
		$jlang->load('mod_readingplan', JPATH_BASE, null, true);
		
		// import component classes
		require_once('components/com_zefaniabible/models/default.php');
		require_once('components/com_zefaniabible/helpers/common.php');
		$mdl_default 	= new ZefaniabibleModelDefault;
		$mdl_common 	= new ZefaniabibleCommonHelper;
				
		$item = new stdClass();				
		$item->int_current_year 				= date("Y");
		$item->int_current_month 				= date("n");
		$item->int_current_month_max_days 		= date("t");
		$item->int_current_week_day 			= date("w");
		$item->int_current_day 					= date("j");
		$item->int_week_number 					= date("W");
				
		$item->int_month 						= 	$item->int_current_month;
		$item->int_year 						= 	$item->int_current_year;
		$item->int_day							= 	$item->int_current_day;		
					
		$item->int_week_begin_weekday			= 	date("w", mktime(0, 0, 0, $item->int_month, $item->int_day, $item->int_year));	
		$item->int_month_max_days				= 	date("t", mktime(0, 0, 0, $item->int_month, 1, $item->int_year));
		$item->int_week_end_weekday				=	date("w", mktime(0, 0, 0, $item->int_month, $item->int_month_max_days, $item->int_year));
		$item->int_prev_month_max_days			=	date("t", mktime(0, 0, 0, $item->int_month-1, 1, $item->int_year));
		
		$item->int_week_begin_day				= 	$item->int_day - $item->int_week_begin_weekday;
		$item->int_current_week_begin_day		= 	$item->int_day - $item->int_current_week_day;
		
		// code to for months that begin mid week.
		if($item->int_week_begin_day < 1)
		{
			$item->int_month--;
			$item->int_week_end_weekday			=	date("w", mktime(0, 0, 0, $item->int_month, $item->int_prev_month_max_days, $item->int_year));			
			$item->int_week_begin_day 			= 	$item->int_prev_month_max_days - $item->int_week_end_weekday;
			$item->int_month_max_days			= 	$item->int_prev_month_max_days;
		}
		$item->str_primary_bible 				= 	$params->get('str_bible_alias', $mdl_default->_buildQuery_first_record());	
		$item->str_primary_reading 				= 	$params->get('str_reading_plan', $mdl_default->_buildQuery_first_plan());
		$item->flg_long_text 					= 	$params->get('flg_short_name', 0);		
		
		$item->str_menuItem 					= 	$params->get('rp_mo_menuitem', 0);		
		$item->str_start_reading_date 			= 	$params->get('str_reading_start_date', '1-1-2012');
		$item->str_begin_reading_date		 	= 	$item->int_day.'-'.$item->int_month.'-'.$item->int_year;
		$item->int_max_days						=  	$mdl_default->_buildQuery_max_reading_days($item->str_primary_reading);
		$item->int_day_diff						= 	$mdl_common->fnc_calcualte_day_diff($item->str_start_reading_date, $item->int_max_days, $item->int_month,$item->int_year, $item->int_week_begin_day);
		$item->arr_reading						=	$mdl_default->_buildQuery_reading_plan($item->str_primary_reading,$item->int_day_diff, 7);

		$item->arr_reading_2 					= "";
		$item->int_day_diff_remain				= -1;
		
		$item->int_days_remain_month 			= 	7-(7 - $item->int_week_end_weekday);

		// add 2nd reading if plan ends
		if(($item->int_day_diff+7) > $item->int_max_days)
		{
			$item->int_day_diff_remain			= 	$item->int_max_days - $item->int_day_diff;
			$item->arr_reading_2				=	$mdl_default->_buildQuery_reading_plan($item->str_primary_reading,1, 7 - $item->int_day_diff_remain);
		}
			
	require JModuleHelper::getLayoutPath('mod_weekly_reading', $params->get('layout', 'default'));

?>

