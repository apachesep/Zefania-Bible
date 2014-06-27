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
* @author		Andrei Chernyshev - www.zefaniabible.com - andrei.chernyshev1@gmail.com
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
* Form validator rule for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Form
*/
class ZefaniabibleCkFormRuleFile extends ZefaniabibleClassFormRule
{
	/**
	* Indicates that this class contains special methods (ie: get()).
	*
	* @var boolean
	*/
	public $extended = true;

	/**
	* Unique name for this rule.
	*
	* @var string
	*/
	protected $handler = 'file';

	/**
	* Use this function to customize your own javascript rule.
	* $this->regex must be null if you want to customize here.
	*
	* @access	public
	* @param	JXMLElement	$fieldNode	The JXMLElement object representing the <field /> tag for the form field object.
	*
	* @return	string	A JSON string representing the javascript rules validation.
	*/
	public function getJsonRule($fieldNode)
	{
		$regex = '';
		$allowedExtensionsText = '*.*';
		if (isset($fieldNode['allowedExtensions']))
		{
			$allowedExtensions = $fieldNode['allowedExtensions'];

			$allowedExtensionsText = preg_replace("/\|/", "<br/>", $allowedExtensions);
			//Remove eventual '*.'
			$allowedExtensions = preg_replace("/\*\./", "", $allowedExtensions);

			$regex = '\.(' . $allowedExtensions . ')$';
		}


		$values = array(
			"#regex" => 'new RegExp("' . $regex . '", \'i\')'
		);

		if (isset($fieldNode['msg-incorrect']))
			$values["alertText"] = LI_PREFIX . JText::_($fieldNode['msg-incorrect']);
		else
			$values["alertText"] = LI_PREFIX . JText::sprintf('ZEFANIABIBLE_ERROR_ALLOWED_FILES', $allowedExtensionsText);

		$json = ZefaniabibleHelperHtmlValidator::jsonFromArray($values);
		return "{" . LN . $json . LN . "}";
	}

	/**
	* Method to test the field.
	*
	* @access	public
	* @param	SimpleXMLElement	$element	The JXMLElement object representing the <field /> tag for the form field object.
	* @param	mixed	$value	The form field value to validate.
	* @param	string	$group	The field name group control value. This acts as as an array container for the field.
	* @param	JRegistry	$input	An optional JRegistry object with the entire data set to validate against the entire form.
	* @param	JForm	$form	The form object for which the field is being tested.
	*
	* @return	boolean	True if the value is valid, false otherwise.
	*
	* @since	11.1
	*/
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		// Common test : Required, Unique
		if (!self::testDefaults($element, $value, $group, $input, $form))
			return false;


		return true;
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JFormRuleFile')){ class JFormRuleFile extends ZefaniabibleCkFormRuleFile{} }

