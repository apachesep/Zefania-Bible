<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.4   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		3.2
* @package		ZefaniaBible
* @subpackage	
* @copyright	Missionary Church of Grace
* @author		Andrei Chernyshev - www.propoved.org - andrei.chernyshev1@gmail.com
* @license		GNU/GPL
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');



/**
* Helper Static Class
*
* @package	Zefaniabible
* @subpackage	Dates
*/
class ZefaniabibleCkHelperDates
{
	/**
	* Decode a date string.
	*
	* @access	static
	* @param	string	$time	the strftime formated date.
	* @param	string	$format	the strftime format used to decode. (/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S)
	*
	* @return	array	Associative array of the date values.
	*/
	static function explodeDate($time, $format)
	{
		$regex = self::strftime2regex($format);

		//Prepare the search depending on attempted format
		$pos = array();
		$pos['year4'] = strpos($format, "Y");
		$pos['year2'] = strpos($format, "y");
		$pos['month'] = strpos($format, "m");
		$pos['day'] = strpos($format, "d");

		$pos['hour'] = strpos($format, "H");
		$pos['minute'] = strpos($format, "i");
		$pos['second'] = strpos($format, "s");

		asort($pos);

		$i = 1;
		foreach($pos as $key => $value)
		{
			if ($value === false)
			{
				unset($pos[$key]);
				continue;
			}
			$pos[$key] = $i;
			$i++;
		}

		//Split the values
		preg_match_all($regex, $time, $matches);

		//Choose year on 2 or 4 digits
		$pos['year'] = ((isset($pos['year4']) && (int)$pos['year4'] > 0)?$pos['year4']:(isset($pos['year2'])?$pos['year2']:null));

		//Retreive the independant values in the matches
		$v = array();
		$defaults = array('year' => '0000','month' => '00','day' => '00','hour' => '00','minute' => '00','second' => '00',);
		foreach($defaults as $key => $default)
		{
			if ((isset($pos[$key])) && ($p = (int)$pos[$key]) && ($p > 0) && (count($matches[$p])))
				$v[$key] = $matches[$p][0];
			else
				$v[$key] = $default;
		}

		return $v;
	}

	/**
	* Decode a date string from a given format.
	*
	* @access	static
	* @param	string	$strtime	The date/time string to decode.
	* @param	array	$formats	An array of accepted formats.
	* @param	boolean	$toSqlString	Stringify the date for SQL.
	*
	* @return	JDate	A JDate Object.
	*/
	static function getSqlDate($strtime, $formats, $toSqlString = false)
	{
		//Push the default SQL date format
		if (!in_array("Y-m-d", $formats))
			$formats[] = "Y-m-d";

		//Push the default SQL datetime format
		if (!in_array("Y-m-d H:i:s", $formats))
			$formats[] = "Y-m-d H:i:s";

		//Push the default SQL time format
		if (!in_array("H:i:s", $formats))
			$formats[] = "H:i:s";

		jimport('joomla.utilities.date');
		foreach($formats as $format)
		{
			$regex = self::strftime2regex($format);
			if (preg_match($regex, $strtime))
			{
				$date = new JDate(self::timeFromFormat($strtime, $format));
				if ($toSqlString)
					return self::toSql($date);
				return $date;
			}
		}
	}

	/**
	* Decode a time string from a given format and return a Unix timestamp.
	*
	* @access	static
	* @param	string	$strtime	The date/time string to decode.
	* @param	array	$formats	An array of accepted formats.
	*
	* @return	int	Unix timestamp
	*/
	static function getUnixTimestamp($strtime, $formats = array('Y-m-d H:i:s'))
	{
		//Check if the string is already a timestamp
		if (preg_match("/^[0-9]{1,10}$/", $strtime))
			return $strtime;

		foreach($formats as $format)
		{
			$regex = self::strftime2regex($format);
			if (preg_match($regex, $strtime))
				return self::timeFromFormat($strtime, $format);
		}
	}

	/**
	* Check if a date string is null.
	*
	* @access	static
	* @param	string	$datetimeStr	the DB datetime string to check (not formated).
	*
	* @return	bool	True is the date string is equivalent to a null date/time/datetime.
	*/
	static function isNull($datetimeStr)
	{
		if (in_array((string)$datetimeStr, array("0","","0000-00-00 00:00:00","0000-00-00","00:00:00")))
			return true;
	}

	/**
	* Convert a strftime format to REGEX language.
	*
	* @access	static
	* @param	string	$format	the strftime format used to decode. (/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S)
	* @param	boolean	$replace	Determines if the numbers are extracted (preg_replace)
	* @param	boolean	$wrap	Adds the slashes around.
	*
	* @return	string	Standard regular expression
	*/
	static function strftime2regex($format, $replace = true, $wrap = true)
	{
		$d2 = "[0-9]{2}";
		$d4 = "[1-9][0-9]{3}";

		if ($replace)
		{
			$d2 = "(" . $d2 . ")";
			$d4 = "(" . $d4 . ")";
		}


		$patterns = array(
			"\\", 	"/", 	"#",	"!", 	"^", "$", "(", ")", "[", "]", "{", "}", "|", "?", "+", "*", ".",
			"%Y", 	"%y",	"%m",	"%d", 	"%H", 	"%M", 	"%S", 	
			"Y", 	"y",	"m",	"d", 	"H", 	"i", 	"s", 	
		);

		$replacements = array(
			"\\\\", "\/", 	"\#",	"\!", 	"\^", "$", "\(", "\)", "\[", "\]", "\{", "\}", "\|", "\?", "\+", "\*", "\.",
			$d4,	$d2,	$d2,	$d2,	$d2,	$d2,	$d2,	
			$d4,	$d2,	$d2,	$d2,	$d2,	$d2,	$d2,	
		);

		$regex = str_replace($patterns, $replacements, $format);
		$regex = "^" . $regex . "$";

		if ($wrap)
			$regex = "/" . $regex . "/";

		return $regex;
	}

	/**
	* Create a unix timestamp from a given format.
	*
	* @access	static
	* @param	string	$datetime	the formated datetime to decode.
	* @param	string	$format	the strftime format used to decode. (/!\ Only supports : %Y, %y, %m, %d, %H, %M, %S)
	* @param	boolean	$gmt	GMT timestamp.
	*
	* @return	integer	A Unix timestamp.
	*/
	static function timeFromFormat($datetime, $format, $gmt = true)
	{
		//PHP 5.3 of later. Not yet available.
		/*
		if (version_compare(phpversion(), "5.3", ">="))
		{
			$format = str_replace("%", "", $format);
			$time = DateTime::createFromFormat($format, $datetime);
			return $time->getTimestamp();
		}
		*/

		$v = self::explodeDate($datetime, $format);

		// Check gregorian valid date
		if (trim($v['month'], '0') && trim($v['day'], '0') && trim($v['year'], '0'))
		if (!checkdate($v['month'], $v['day'], $v['year']))
			return null;

		if ($gmt)
			return gmmktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);

		return mktime($v['hour'], $v['minute'], $v['second'], $v['month'], $v['day'], $v['year']);
	}

	/**
	* Cross compatibility interface.
	*
	* @access	public static
	* @param	JDate	$date	JDate object to parse.
	*
	* @return	string	Sql formated date.
	*
	* @since	Cook 2.0
	*/
	public static function toSql($date)
	{
		$version = new JVersion();
		if ($version->isCompatible('3.0'))
			return $date->toSql();	
		else
			return $date->toMySQL();
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleHelperDates')){ class ZefaniabibleHelperDates extends ZefaniabibleCkHelperDates{} }

