(function($){
	var getRelOption = function(object, property, defaultValue)
	{
		if (typeof(defaultValue) == 'undefined')
			defaultValue = null;

		var rel = $(object).attr('rel');
		if (typeof(rel) == 'undefined')
			return defaultValue;
			
		var options = $.parseJSON(rel);
	
		if (typeof(options[property]) != 'undefined')
			return options[property];
			
		return defaultValue;
	}
	
	$('document').ready(function(){
		$(".btn-group label").click(function() {			
			var label = $(this);
			var input = $('#' + label.attr('for'));
			if (!input.prop('checked'))
			{
				var color = getRelOption(input, 'color', 'success');
					
				label.addClass('active btn-' + color);
				input.prop('checked', true);
								
				label.closest('.btn-group').find("label").each(function(){
					var input = $('#' + $(this).attr('for'));
					if (input.prop('checked'))
						return;
						
					var color = getRelOption(input, 'color', 'success');
					$(this).removeClass('active btn-success btn-' + color);
				});
			}
		});
		
		$(".btn-group input[checked=checked]").each(function()
		{
			if (!$(this).prop('checked'))
				return;
				
			var color = getRelOption($(this), 'color', 'success');
			$("label[for=" + $(this).attr('id') + "]").addClass('active btn-' + color);
		});
	});
})(jQuery);