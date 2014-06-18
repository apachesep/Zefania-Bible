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

class JDomHtmlFormFooter extends JDomHtmlForm
{
	var $dataKey;
	var $dataObject;
	var $dataValue;


	var $values;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 *
	 *
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 * 	@values		: Added or override values for the form footer
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('dataKey'	, null, $args);
		$this->arg('dataObject'	, null, $args);
		$key = $this->dataKey;
		$this->arg('dataValue'	, null, $args, (($this->dataObject && $key)?(isset($this->dataObject->$key)?$this->dataObject->$key:null):null));
		$this->arg('domID'		, null, $args, $this->dataKey);
		$this->arg('values'		, null, $args, $this->values);


	}

	function build()
	{

		$jinput = new JInput;

		//Initialize default form
		$keys = array(
			'option' => $this->getExtension(),
			'view' => $this->getView(),
			'layout' => $jinput->get('layout', null, 'CMD'),
			'task' => "",
		);

		//For item layout
		if (isset($this->dataObject))
		{
			$keys['id'] = (isset($this->dataObject->id)?$this->dataObject->id:0);

			//Deprecated
			$keys['cid[]'] = (isset($this->dataObject->id)?$this->dataObject->id:0);
		}


		//Specifics values or overrides
		if (isset($this->values))
			foreach($this->values as $key => $value)
				$keys[$key] = $value;


		//Reproduce current query in the form
		$followers = array('lang', 'Itemid', 'tmpl');  //Cmd types only for the moment

		foreach($followers as $follower)
		{
			$val = $jinput->get($follower, null, 'CMD');
			if ($val) $keys[$follower] = $val;
		}

		$html = "";
		foreach($keys as $key => $value)
		{
			$html .= JDom::_('html.form.input.hidden', array(
					'dataKey' => $key,
					'dataValue' => $value));
		}


		//Token
		$html .= JHTML::_( 'form.token' );

		return $html;


	}


}