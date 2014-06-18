/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <  Generated with Cook       (by Jocelyn HUARD) |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo----------------------------------------------------- +
* @version		2.0
* @package		Cook Self Service
* @subpackage	JDom
* @copyright	Copyright 2011 - 100% vitamin
* @author		100% Vitamin - www.cpcv.net - info@cpcv.net
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


// Static AJAX caller : Entry point to instance a node
(function($) {
	$.fn.jdomAjax = function(options)
	{
		var thisp = this;
		
		// Load the correct Hook node plugin
		var plugin = 'defaults';
		if (typeof(options.plugin) != 'undefined')
			plugin = options.plugin;

	//Merge the options
	    var opts = $.extend({}, $.fn.jdomAjax[plugin], options);
		if (!opts.data)
			opts.data = new Object();


	//Data Object init
		var data = opts.data;
		if (typeof(opts.token) != 'undefined')
			data.token = opts.token;


	//Use a dotted namespace to find the MVC context (Cook Self Service)
		if (typeof(opts.namespace) != 'undefined')
		{
			var urlParts = opts.namespace.split('.');

			data.option = 'com_' + urlParts[0];
			data.view = urlParts[1];
			data.layout = urlParts[2];
			data.render = (urlParts[3]?urlParts[3]:'');
		}

	//Merge the vars with data
		if (typeof(opts.vars) != 'undefined')
		{
			for (var key in opts.vars)
			{
				data[key] = opts.vars[key];
			}
		}

		// End of definition : init script
		opts.initialize(this);
		
		//Return object class to sender
		return opts;
	};
	
	
	
// Hook base class	
	$.fn.jdomAjax.hook = 
	{
		domContents:null,
		
		// Default function : Define all div as ajax wrapper, then send the request
		initialize: function(object)
		{
			this.domContents = object;
			this.request();
		},
		
	};
	
	

// Hook Ajax Base Class: Contains all actions and application layer
	
	$.fn.jdomAjax.ajax = $.extend({}, $.fn.jdomAjax.hook,	
	{

		url:'index.php?tmpl=component',
		method:'POST',
		data:null,
		dom:null,
		token:parseInt(Math.random() * 9999999999),


		request: function()
		{
			var div = this.domContents;
			//Loading scripts (spinner eventually)
			this.loading(div);

	// Deprecated var : opts.result - Use opts.format
	if (typeof(this.format) != 'undefined')
		this.result = this.format;
	
		
			//Choose the type of rendering (JSON, or HTML)
			if (this.result == 'JSON')
				this.getJSON(div);
			else
				this.getHTML(div);
			
		},


	//getHTML
		getHTML: function(div)
		{
			var thisp = this;
			jQuery.ajax({
				'type': this.method,
				'url': this.url,
				'data': this.data,
				'success': function(data, textStatus, jqXHR){
					if (typeof(thisp.success) != 'undefined')
						thisp.success(div, data, textStatus, jqXHR);

				},
				'error' : function(jqXHR, textStatus, errorThrown){
					if (typeof(thisp.error) != 'undefined')
						opts.error(div, textStatus, errorThrown);
				}

			});

		},

		getJSON: function(div)
		{
			
			var thisp = this;
			
			jQuery.getJSON(this.url, this.data, function(data)
			{
				thisp.successJSON(div, data);
			});
		},
		
				
		
		loading: function(div)
		{
			$('<div/>', {'class':'jdom-ajax-spinner'}).appendTo($(div));
		},

		success: function(object, data, textStatus, jqXHR)
		{
			var thisp = this;

			//fill the object with the returned html
			$(object).html('').html(data);
			$(object).ready(function()
			{
				if (typeof(thisp.ready) != 'undefined')
					thisp.ready(object, data, textStatus, jqXHR);	
			});
		},
		
		ready: function(object, data, textStatus, jqXHR)
		{
			if (typeof(callback['_' + this.token]) == 'function')
			{
				(callback['_' + this.token])();
				callback['_' + this.token] = null;
			}
		},


//TODO Test coverage
		successJSON: function(object, data)
		{			
			var html = '';
			var alertMsg = '';
			var exceptions = [];
			
			
			// Errors in HTML properly handled
			if (typeof(data.transaction.htmlExceptions) != 'undefined')
				html += data.transaction.htmlExceptions;
				
			// Alert message
			if (typeof(data.transaction.rawExceptions) != 'undefined')
				alertMsg += data.transaction.rawExceptions;
			
			// JSON exceptions
			if (typeof(data.transaction.exceptions) != 'undefined')
				exceptions = data.transaction.exceptions;

			// Raw HTML
			if (typeof(data.response.html) != 'undefined')
				html += data.response.html;			
					
					
			// Outputs
			$(object).html('').html(html);
			if (alertMsg != '')
				alert(alertMsg);

		},
		
		
				
	});
	
	
// Hook Node Base Class
	$.fn.jdomAjax.node = $.extend({}, $.fn.jdomAjax.ajax,
	{
		
		
		
		
		
		
		
	});


	
	
	
	
	
// Hook Default Node Controller Class: Contains all actions and application layer
	$.fn.jdomAjax.defaults = $.extend({}, $.fn.jdomAjax.node,
	{
		
// EVENTS
		error: function(object, jqXHR, textStatus, errorThrown)
		{
//TODO


		},
		

//CONTROLLER

		display: function()
		{

			//Create the HTML structure

			//create the contents div
			
			
		},
		
		edit: function()
		{
			//TODO s ...
			// Use jQuery namespacing to find the strings and replace with inputs.
			
			// Create the FORM
			// Create every single input
			// Optionaly show extra informations or controls bigger than Fly > problem.


			// Names conventions must be the same for grid, form, everithing...
			
		},
		
		save: function()
		{
			
		},
		
		
		remove: function()
		{
			
		},
		
		addRow: function()
		{
			
			
		},
		
		
		refresh: function()
		{
			
			
		},
		
		reorder: function()
		{
			
		},




//VIEW		
		renderToolbar: function()
		{
			
			
		},
		
		renderForm: function()
		{
			
			
		},
		
		
		renderContents: function()
		{
			
		},
		
		
		
	});
	
})(jQuery);






(function($) {
	'use strict';
	 
	/**
	* Multiple parallel getScript > getScripts()
	* https://gist.github.com/vseventer/1378913
	* 
	* @access public
	* @param Array|String url (one or more URLs)
	* @param callback fn (oncomplete, optional)
	* @returns void
	*/
	$.getScripts = function(url, fn)
	{
		if(!$.isArray(url)) {
			url = [url];
		}
		 
		$.when.apply(null, $.map(url, $.getScript)).done(function() {
			fn && fn();
		});
	};
 
}(jQuery));

var callback = {};
var registerCallback = function(token, fct)
{
	callback['_' + token] = fct;
}