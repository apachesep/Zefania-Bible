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



// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport( '0');

/**
 * HTML View class for the Zefaniabible component
 *
 * @static
 * @package		Joomla
 * @subpackage	Zefaniabible
 *
 */
class ZefaniabibleViewReading extends JViewLegacy
{
	/*
	 * Define here the default list limit
	 */
	protected $_default_limit = null;

	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$config = JFactory::getConfig();
		$option	= JRequest::getCmd('option');
		$view	= JRequest::getCmd('view');
		$layout = $this->getLayout();
		switch($layout)
		{
			case 'default':
				$fct = "display_" . $layout;
				$this->$fct($tpl);
				break;
		}
	}
	function display_default($tpl = null)
	{
		/*
			a = plan
			b = bible
			c = day
			d = commentary
		*/		
		$app = JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		$user 	= JFactory::getUser();
		$document	= JFactory::getDocument();
		// menu item overwrites
		$params = JComponentHelper::getParams( 'com_zefaniabible' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		require_once(JPATH_COMPONENT_SITE.'/models/reading.php');
		$biblemodel = new ZefaniabibleModelReading;		
		
		require_once(JPATH_COMPONENT_SITE.'/models/default.php');
		$mdl_default = new ZefaniabibleModelDefault;

		$str_primary_reading = 		$params->get('primaryReading', $mdl_default->_buildQuery_first_plan());
		$str_primary_bible = 		$params->get('primaryBible', $mdl_default->_buildQuery_first_record());	
		$str_start_reading_date = 	$params->get('reading_start_date', '1-1-2012');
	
		$str_reading_plan = JRequest::getCmd('a', $str_primary_reading);	
		$str_bibleVersion = JRequest::getCmd('b', $str_primary_bible);
		
		// time zone offset.
		$config = JFactory::getConfig();
		date_default_timezone_set($config->get('offset'));		
		if($user->id > 0)
		{
			$arr_user_data = $biblemodel->_buildQuery_getUserData($user->id);
			foreach($arr_user_data as $obj_user_data)
			{
				$str_start_reading_date = $obj_user_data->reading_start_date;
				$str_bibleVersion = $obj_user_data->bible_alias;
				$str_reading_plan = $obj_user_data->plan_alias;
			}
		}	
		
		$arr_start_date = new DateTime($str_start_reading_date);	
		$arr_today = new DateTime(date('Y-m-d'));
		$int_day_diff = round(abs($arr_today->format('U') - $arr_start_date->format('U')) / (60*60*24))+1;
		$int_day_number = 	JRequest::getInt('c', $int_day_diff);
		
		$arr_bibles 		=	$biblemodel->_buildQuery_Bibles();
		$arr_reading 		=	$biblemodel->_buildQuery_plan($str_reading_plan, $str_start_reading_date);
		$arr_reading_plans 	= 	$biblemodel->_buildQuery_readingplan();
		$arr_plan 			=	$biblemodel->_buildQuery_current_reading($arr_reading, $str_bibleVersion);
		$int_max_days		=  	$biblemodel->_buildQuery_max_days($str_reading_plan);


		$flg_show_commentary = $params->get('show_commentary', '0');
		$flg_show_references = $params->get('show_references', '0');
		if($flg_show_references)
		{
			$obj_references = $biblemodel->_buildQuery_References($arr_reading);
		}
		// commentary code
		$obj_commentary_dropdown = '';
		if($flg_show_commentary)
		{
			require_once(JPATH_COMPONENT_SITE.'/models/commentary.php');
			$mdl_commentary = new ZefaniabibleModelCommentary;			
			$str_primary_commentary = $params->get('primaryCommentary');
			$str_commentary = JRequest::getCmd('d', $str_primary_commentary);
			$x = 0;
			foreach($arr_reading as $obj_reading)
			{
				for($y = $obj_reading->begin_chapter; $y <= $obj_reading->end_chapter; $y++)
				{
					$arr_commentary[$x] =	$mdl_commentary-> _buildQuery_commentary_chapter($str_commentary,$obj_reading->book_id,$y);
					$x++;
				}
			}

			$arr_commentary_list =	$mdl_commentary-> _buildQuery_commentary_list();	
			foreach($arr_commentary_list as $obj_comm_list)
			{
				if($str_commentary == $obj_comm_list->alias)
				{
					$obj_commentary_dropdown = $obj_commentary_dropdown.'<option value="'.$obj_comm_list->alias.'" selected>'.$obj_comm_list->title.'</option>';
				}
				else
				{
					$obj_commentary_dropdown = $obj_commentary_dropdown.'<option value="'.$obj_comm_list->alias.'">'.$obj_comm_list->title.'</option>';
				}
			}
		}	
		//Filters
		$user = JFactory::getUser();
		$this->assignRef('user',				$user);
		$this->assignRef('int_day_number',		$int_day_number);
		$this->assignRef('access',				$access);
		$this->assignRef('lists',				$lists);
		$this->assignRef('bibles',				$arr_bibles);
		$this->assignRef('plan',				$arr_plan);
		$this->assignRef('reading',				$arr_reading);
		$this->assignRef('arr_reading_plans',	$arr_reading_plans);
		$this->assignRef('config',				$config);
		$this->assignRef('arr_commentary',		$arr_commentary);
		$this->assignRef('obj_commentary_dropdown',	$obj_commentary_dropdown);
		$this->assignRef('int_orig_day',		$int_day_diff);
		$this->assignRef('int_max_days',		$int_max_days);
		$this->assignRef('str_reading_plan',	$str_reading_plan);
		$this->assignRef('str_bible_version',	$str_bibleVersion);
		$this->assignRef('obj_references',		$obj_references);
				
		parent::display($tpl);
	}
}