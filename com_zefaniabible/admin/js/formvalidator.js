function validateFields(id,name)
{
	var str_alias = document.forms["adminForm"][id].value;
	var str_iChars = "~`!#$%^&*+=-[]\\\';,/{}|\":<>?";
	var str_incorrect_image = "http://"+window.location.host+'/administrator/components/com_zefaniabible/images/incorrect.png';
	var str_correct_image = "http://"+window.location.host+'/administrator/components/com_zefaniabible/images/tick.png';
	var obj_div_element = document.getElementById(id+"_valid");
	var flg_error_found = 0;

	if(str_alias == "")
	{
		alert(name+'\n'+str_blank_char);	
		flg_error_found = 1;
	}
	if(id == 'alias')
	{
		if(str_alias.lastIndexOf(" ")>=0)
		{
			alert(name+'\n'+str_spaces_char);
			flg_error_found = 1;
		}
		for (var i = 0; i < str_alias.length; i++) 
		{
			if (str_iChars.indexOf(str_alias.charAt(i)) != -1) 
			{
				alert (name+'\n'+str_special_char);
				flg_error_found = 1;
			}
		}
	}
	
	if(flg_error_found == 0)
	{
		obj_div_element.innerHTML = '<img src="'+str_correct_image+'" width="16" height="16" border="0" >';
	}
	else
	{
		obj_div_element.innerHTML = '<img src="'+str_incorrect_image+'" width="16" height="16" border="0" >';		
	}
}
