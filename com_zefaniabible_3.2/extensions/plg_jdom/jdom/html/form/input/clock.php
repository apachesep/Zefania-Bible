<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormInputClock extends JDomHtmlFormInput
{
	var $level = 4;			//Namespace position
	var $last = true;		//This class is last call

	var $timeFormat;


	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 *	@timeFormat	: Time Format
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('timeFormat'		, null, $args, "%H:%M");
		if ($this->timeFormat)
		{
			//Instance the validator
			$this->validatorRegex = $this->regexFromTimeFormat();
		}

		$this->addValidatorHandler();

		if ($this->dataValue
			&& ($this->dataValue != "0000-00-00")
			&& ($this->dataValue != "00:00:00")
			&& ($this->dataValue != "0000-00-00 00:00:00"))
		{
			jimport('joomla.utilities.date');
			$date = new JDate($this->dataValue);
			$this->dataValue = $date->format($this->timeFormat);
		}
		else
			$this->dataValue = "";

	}

	function regexFromTimeFormat()
	{
		$d2 = '[0-9]{2}';
		$d4 = '[1-9][0-9]{3}';

		$patterns =
array(	'\\','/','#','!','^','$','(',')','[',']','{','}','|','?','+','*','.',
		'%Y','%y','%m','%d', '%H', '%I', '%l', '%M', '%S', ' ');
		$replacements =
array(	'\\\\', '\\/','\\#','\\!','\\^','\\$','\\(','\\)','\\[','\\]','\\{','\\}','\\|','\\?','\\+','\\*','\\.',
		$d4,$d2,$d2,$d2,$d2,$d2,$d2,$d2,$d2,'\s');

		return "^" . str_replace($patterns, $replacements, $this->timeFormat) . "$";
	}

	function build()
	{

		$html = JDom::_('html.form.input.text', array_merge($this->options, array(
												'dataValue' => $this->dataValue,
												'size' => 6,
												)));

		return $html;
	}
}