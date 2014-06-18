
/* Hook LIST */
(function($) {

	$.fn.jdomAjax.list = $.extend({}, $.fn.jdomAjax.defaults,
	{
		method:'POST',
		
		loading: function(object)
		{
			$('<div/>', {'class':'jdom-ajax-spinner'}).appendTo($(object));
		},

		successJSON: function(object, data)
		{
			var html = 'CECI EST UNE LISTE';
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
			

			html = ''; //FLUSH
			var list = data.response.data;
			var row;
			var headers = [];
			html += '<table class="table">';
			if (typeof(data.response.headers) != 'undefined')
			{
				headers = data.response.headers;
			
				html += '<tr>';
				for (var i = 0 ; i < headers.length ; i++)
				{
					var header = headers[i];
					html += '<th>' + header.label + '</th>';
				}
				html += '</tr>';
				
			}
			
			
			for (var key in list)
			{
				html += '<tr>';
				if (key.substr(0, 1) == '$')
					break;

				row = list[key];

				for (var i = 0 ; i < headers.length ; i++)
				{
					var header = headers[i];
					html += '<td>' + row[header.name] + '</td>';
				}
				
				html += '</tr>';

			}
			html += '</table>';
			

			$(object).html('').html(html);
			if (alertMsg != '')
				alert(alertMsg);
			
			
			return html;			
		},
		
		

	});;
 
}(jQuery));