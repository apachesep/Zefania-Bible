
function requiredMooToolsVersion(required)
{
	if (typeof MooTools == 'undefined')
		return false;

	var framework = getJsFramework();
	return framework.checkVersion(required);
}

function getJsFramework()
{
	var framework = {
			name:null,
			version:null,
			klass:null,
			checkVersion:function(required){return false}
	};

	var checkVersion = function(version, required, a)
	{
		if (typeof(a) == 'undefined')
			var a = version.split('.');

		var b = required.split('.');

		for (var i = 0; i < a.length; ++i) {
			a[i] = Number(a[i]);
		}

		for (var i = 0; i < b.length; ++i) {
			b[i] = Number(b[i]);
		}
		if (a.length == 2) {
			a[2] = 0;
		}

		if (a[0] > b[0]) return true;
		if (a[0] < b[0]) return false;


		if (a[1] > b[1]) return true;
		if (a[1] < b[1]) return false;


		if (a[2] > b[2]) return true;
		if (a[2] < b[2]) return false;

		return true;

	}


	if (typeof jQuery != 'undefined')
	{
	    framework.name = 'jQuery';
	    framework.version = jQuery.fn.jquery;
	    framework.klass = jQuery;


		framework.checkVersion = function(required)
		{
			return checkVersion(this.version, required);
	    };


	}
	else if (typeof MooTools != 'undefined')
	{
	    framework.name = 'MooTools';
	    framework.version = MooTools.version;
	    framework.klass = MooTools;
	    framework.checkVersion = function(required)
	    {
			var a = this.version.split('.');

	    	//Correct the mootools second num
		    for (var i = 0; i < a.length; ++i) {
				//a[i] = Number(a[i]);
				//	1.11   <  1.2  !!!
				a[i] = a[i].substr(0,1);   //Limit to only one char
			}

			return checkVersion(this.version, required, a);
	    };
	}
	return framework;

}








var onBeforeValidation = new Array();
var onAfterValidation = new Array();
var onBeforeValidationAlert = null;




/*
*	FORMVALIDATOR - OPTIMIZATION OF THE JOOMLA NATIVE CLASS
*
*
*	-> New functions to handle the test
*	-> Pass the element object in the handler function (and much more arguments in the triggers)
*	-> Triggers before and after test
*	-> Messages boxes next to the fields
*	-> Global error message customizable
*	-> Handle radio and checkboxes
*	-> Handle editor
*
*
*
*	TO ADD A TRIGGER :
*
*
*	/**
*	* Functions to add a trigger
*	*
*	* @arg output object (described as below)
*	*
*	* @args.value 				: the tested value. You can transform the value before the test. This value will not be rewrited inside the input.
*	* @args.regex 				: the regexp
*	* @args.element		 		: input element. Use this if you want rewrite inside the input, or what you want...
*	* @args.name 				: field name
*	* @args.valid 				: result of the validation. Override here the result of a your custom test before of after
*	* @args.msgInfo				: the information message
*	* @args.msgIncorrect 		: the incorrect message
*	* @args.msgRequired 		: the required message
*	* @args.displayInfo 		: Displays the info box
*	* @args.displayIncorrect 	: Displays the incorrect box
*	* @args.displayRequired 	: Displays the required box
*	*
*	* @args.message 			: the message to show in alert box when submit (include field name. You can use <%LABEL%> pattern)*
*	*
*	**
*
*
*	onBeforeValidation['my_handle'] = function(args)	// my_handle : ie : email, username, password, ...
*	onAfterValidation['my_handle'] = function(args)
*	onBeforeValidation['required'] = function(args)		// For 'required' check  (Before)
*	onAfterValidation['required'] = function(args)		// For 'required' check  (After)
*	{
*	}
*
*
*	/**
*	* Functions to add a trigger
*	*
*	* @arg output object (described as below)
*	*
*	* @args.doit 				: Show the alert box (args.doit = false to disable the 'invasive' alert box)
*	* @args.message 			:  Formated message, (for instance, you can add a title)
*	*
*	**
*
*	onBeforeValidationAlert = function(args)
*	{
*	}
*
*/
window.addEvent('domready', function()
{

	if (typeof(document.formvalidator) == 'undefined')
		return;

	document.formvalidator.attachToForm = function(form)
	{
		var thisp = this;
		// Iterate through the form object and attach the validate method to all input fields.
		$A(form.elements).each(function(el)
		{
			//alert(el);
			el = $(el);
			var tag = el.tagName;
			var type = thisp.getProperty(el, 'type');
			if ((tag == 'input' || tag == 'button') && type == 'submit') {
				if (el.hasClass('validate')) {
					el.onclick = function(){return document.formvalidator.submitform(this.form);};
				}
			} else {

				if (tag == 'fieldset')
				{
					//Radio and checkboxes
					el.getElements('input').each(function(elem)
					{
						elem.addEvent('change', function(){return document.formvalidator.validate(el);});
					});
				}
				else if (thisp.checkInput(el))
					el.addEvent('blur', function(){return document.formvalidator.validate(this);});

			}
		});
	};


	document.formvalidator.submitform = function(form, pressbutton, callback)
	{
		this.form = form;

		// Do not validate form for these tasks
		if (pressbutton.test(/[a-zA-Z0-9\_\-]+\.cancel$/)
		|| pressbutton.test(/[a-zA-Z0-9\_\-]+\.close$/))
		{
			callback(pressbutton);
			return;
		}

		var valid = true;
		var message = "";

		// Validate form fields
		for (var i=0;i < form.elements.length; i++)
		{
			var args = new Object();

			var el = form.elements[i];
			el = this.checkInput(el);

			if (el	&& (this.validate(el, false, args) == false))
			{
				//args is now initializated with the returned message
				valid = false;
				args.message = args.message.replace("<%LABEL%>", this.getLabel(form, args.id));
				message += args.message + '\n';
			}
		}


		// Run custom form validators if present
		/*$A(this.custom).each(function(validator){
			if ((validator.exec) && (validator.exec() != true))
			{
				valid = false;
			}
		});*/


		var argsAlert = new Object();
		argsAlert.message = message;
		argsAlert.doit = true;


		if (onBeforeValidationAlert)
			onBeforeValidationAlert(argsAlert);

		if (valid)
			callback( pressbutton );
		else if (argsAlert.doit)
			alert(argsAlert.message);

	};

	document.formvalidator.prepareForm = function(form)
	{
		this.form = form;
		var valid = true;

		// Validate form fields
		for (var i=0;i < form.elements.length; i++)
		{
			var el = form.elements[i];
			el = this.checkInput(el);

			if (el	&& (this.validate(el, true) == false)) {
				valid = false;
			}
		}

		return valid;
	};

	document.formvalidator.checkInput = function(el)
	{
		var tag = el.tagName;

		switch(tag.toLowerCase())
		{
			case 'input':
			case 'textarea':
			case 'select':
			case 'fieldset':		//Radio / Checkboxes
			case 'submit':
				break;

			default:
				return null;

		}

		//if (el.hasClass('required') || el.className.test(/validate-[a-zA-Z0-9\_\-]+/))
			return el;

		return null;
	}

	document.formvalidator.validate = function(el, prepare, args)
	{

		if (args == undefined)
			var args = new Object();


		var current = null;


		//In case of file input, we read the current previous value.
		var type = this.getProperty(el, 'type');
		if (type == 'file')
		{
			var current = $('__' + this.getProperty(el, 'id'));
		}


		if (prepare)
		{
			this.validatorTest('', null, el, '', args);
			return;
		}

		// If the field is required make sure it has a value
		if ($(el).hasClass('required') && !current)
		{

			if (!this.validatorTest('required', null, el, '', args))
			{
				return false;
			}
		}
		else
		{
			//Simple check
			this.validatorTest('', null, el, '', args);
		}


		// Only validate the field if the validate class is set
		var handler = (el.className && el.className.search(/validate-([a-zA-Z0-9\_\-]+)/) != -1) ? el.className.match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
		if (handler == '') {
			this.handleResponse(true, el);
			return true;
		}

		// Check the additional validation types
		if ((handler) && (handler != 'none') && (this.handlers[handler]) && this.getValue($(el)))
		{
			// Execute the validation handler and return result
			if (!this.validatorTest(handler, this.handlers[handler].exec, el, this.handlers[handler].regex, args))
			{
				return false;
			}
		}

		// Return validation state
		this.handleResponse(true, el);
		return true;
	};

	document.formvalidator.validatorTest = function(handler, call, el, regex, args)
	{
		el = $(el);


		args.valid = false;

		args.value = this.getValue(el);
		if (!args.value)
			args.value = '';

		args.regex = regex;
		args.element = el;
		args.name = this.getProperty(el, 'name');
		args.id = this.getProperty(el, 'id');

		args.msgInfo = $('message_' + args.id + '_info');
		args.msgIncorrect = $('message_' + args.id + '_incorrect');
		args.msgRequired = $('message_' + args.id + '_required');
		args.icon = $('validatoricon_' + args.id);



		args.displayInfo = (args.value.trim() == '');
		args.displayIncorrect = false;
		args.displayRequired = false;

		args.message = "";
		args.status = "";


		//Trigger before test
		if (handler != '')
		{
			var onBefore = onBeforeValidation[handler];
			if (onBefore != undefined)
				onBefore(args);
		}



		//Refresh message display
		args.displayInfo = (args.value.trim() == '');
		args.displayIncorrect = (!args.valid);



	//DO THE TEST
		if (handler == 'required')
		{
			args.valid = ((args.valid) || (args.value.trim() != ''));

		}
		else if (handler == '')  //Not required field
		{
			if (args.value.trim() == '')
				args.valid = true;
		}
		else if (!args.valid)
		{
			if (call == null)
			{
				args.valid = args.regex.test(args.value);
			}
			else
			{
				args.valid = call(args.value);
			}
		}




		//Refresh message display
		args.displayIncorrect = (!args.valid);



		//Refresh message display
		args.displayInfo = (args.value.trim() == '');
		args.displayIncorrect = (!args.valid);




		if (el.hasClass('required') && ((!args.valid) && (args.value.trim() == '')))
			args.displayRequired = true;

		if (handler == '')
			args.displayRequired = false;

		if ((handler == '') || (args.displayRequired))
			args.displayIncorrect = false;


		if ((handler != '') && ((args.displayIncorrect) || (args.displayRequired)))
			args.displayInfo = false;


		//Prepare messages (alert box)
		if (args.displayIncorrect)
		{
			if (args.msgIncorrect)
				args.message = "<%LABEL%> : " + this.getText(args.msgIncorrect);
			else
				args.message = FIELDS_VALIDATOR_INCORRECT;
		}

		if (args.displayRequired)
		{
			if (args.msgRequired)
				args.message = "<%LABEL%> : " + this.getText(args.msgRequired);
			else
				args.message = FIELDS_VALIDATOR_REQUIRED;
		}



		//Trigger after test
		if (handler != '')
		{
			var onAfter = onAfterValidation[handler];
			if (onAfter != undefined)
				onAfter(args);
		}



		this.showElement(args.msgInfo, args.displayInfo);
		this.showElement(args.msgIncorrect, args.displayIncorrect);
		this.showElement(args.msgRequired, args.displayRequired);



		if (handler != '')  // Quiet Call
		{
			this.handleResponse(args.valid, el);
		}


		if (args.icon)
		{
			args.icon.removeClass('validatoricon_incorrect');
			args.icon.removeClass('validatoricon_valid');

			if ((args.displayIncorrect) || (args.displayRequired))
				args.icon.addClass('validatoricon_incorrect');
			else if ((handler != '') && (!args.displayRequired) && (args.valid))
				args.icon.addClass('validatoricon_valid');

		}

		return args.valid;

	}

	document.formvalidator.handleResponse = function(state, el)
	{
		var thisp = this;
		// Find the label object for the given field if it exists
		if (!(el.labelref)) {
			var labels = $$('label');
			labels.each(function(label){
				if (thisp.getProperty(label, 'for') == thisp.getProperty(el, 'id')) {
					el.labelref = label;
				}
			});
		}

		// Set the element and its label (if exists) invalid state
		if (state == false) {
			el.addClass('invalid');
			el.set('aria-invalid', 'true');
			if (el.labelref) {
				$(el.labelref).addClass('invalid');
			}
		} else {
			el.removeClass('invalid');
			el.set('aria-invalid', 'false');
			if (el.labelref) {
				$(el.labelref).removeClass('invalid');
			}
		}
	};

	document.formvalidator.getValue = function(el)
	{
		var value = (el.value == undefined?'':el.value); //Default
		var tag = el.tagName;

		var thisp = this;
		switch(tag)
		{
			case 'fieldset':
				var inputs = [];
				el.getElements('input').each(function(elem)
				{
					if (thisp.getProperty(elem, 'checked'))
						inputs.push(thisp.getProperty(elem, 'value'));
				});
				value = inputs.join(",");
				break;
		}

		//Legacy Cook 1.4
		if (!value)
		{
			var type = this.getProperty(el, 'type');
			switch(tag + ':' + type)
			{
				case 'input:radio':
				case 'input:checkbox':

					var inputs = new Array();

					el.getElements('input').each(function(inp)
					{

						if ((thisp.getProperty(inp, 'name') == name)
							&& thisp.getProperty(inp, 'checked'))
						{
							inputs.push(inp);
						}
					});


					if (inputs.length == 0)
						value = '';
					else
						value = this.getProperty(inputs[0], 'value');

					break;

			}


		}
		return value;
	};

	document.formvalidator.getLabel = function(form, id)
	{
		var labelText = "";
		var thisp = this;
		form.getElements('label').each(function(label)
		{
			if (thisp.getProperty(label, 'for') == id)
			{
				labelText = thisp.getText(label);

			}
		});

		return labelText;
	};

	/**
	*	Abstraction class between javascript frameworks :
	*	Mootools / JQuery
	**/
	document.formvalidator.getProperty = function(el, property)
	{
		var framework = getJsFramework();
		switch(framework.name)
		{
			case 'jQuery':
				//return el.attr(property);
				break;


			case 'MooTools':
				return el.getProperty(property);
				break;
		}

	};

	document.formvalidator.getText = function(el)
	{
		return el.innerHTML.trim();
	};

	document.formvalidator.showElement = function(el, show)
	{
		if (!el)
			return;

		if (requiredMooToolsVersion('1.3'))
		{
			if (show)
				el.show();
			//else
				//el.hide();
		}
		else
		{
			if (show)
				el.setStyle('display','inherit');
			else
				el.setStyle('display','none');
		}
	};

});