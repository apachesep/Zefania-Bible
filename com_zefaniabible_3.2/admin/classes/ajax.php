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
* Ajax Class for Zefaniabible.
*
* @package	Zefaniabible
* @subpackage	Class
*/
class ZefaniabibleCkClassAjax extends JObject
{
	/**
	* Handle transaction informations in JSON response.
	*
	* @access	public
	* @param	array	$options	Options
	* @return	void
	*
	* @since	Cook 2.6.3
	*/
	public function responseJson($options = array())
	{
		$return = (object)array(
			//format of the JSON transaction. May change in future, this header is for preventing conflicts.
			'header' => 'hook-1.0',

			// Transaction handle the ajax system, errors, events, etc...
			'transaction' => new stdClass(),
	
			// Response handle datas in different formats (HTML, JSON, ...)
			'response' => new stdClass()
		);

		// Handle errors
		if (!isset($options['renderExceptions']))
			$options['renderExceptions'] = null;

		switch(strtoupper($options['renderExceptions']))
		{
			case 'HTML': 
				$return->transaction->htmlExceptions = ZefaniabibleClassView::renderMessages(false);
				break;

			case 'TEXT': 
				$return->transaction->rawExceptions = ZefaniabibleClassView::renderMessages(true);
				break;

			case 'JSON':
			default:
				$return->transaction->exceptions = JFactory::getApplication()->getMessageQueue();
				break;
		}


		// Optional vars in TRANSACTION
		if (isset($options['result']))
			$return->transaction->result = $options['result'];

		if (isset($options['message']))
			$return->transaction->message = $options['message'];

		if (isset($options['refresh']))
			$return->transaction->refresh = $options['refresh'];

		if (isset($options['redirect']))
			$return->transaction->redirect = $options['redirect'];

		if (isset($options['redirectTarget']))
			$return->transaction->redirectTarget = $options['redirectTarget'];



		// Optional vars in RESPONSE
		if (isset($options['data']))
			$return->response->data = $options['data'];

		if (isset($options['headers']))
			$return->response->headers = $options['headers'];


		$buffer = ob_get_clean();
		// At the very end.
		$return->response->html = $buffer;
		if (isset($options['html']))
			$return->response->html .= $options['html'];				


		jexit(json_encode($return));
	}


}

// Load the fork
ZefaniabibleHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('ZefaniabibleClassAjax')){ class ZefaniabibleClassAjax extends ZefaniabibleCkClassAjax{} }

