$(function()
{
	$('#set_password_form').on('submit', function()
	{
		var password = $('#password').val(),
			password_verify = $('#password_verify').val();

		if ( password_verify === '' )
		{
			$('#password_verify').focus();
			return false;
		}
		else if ( password === '' )
		{
			$('#password').focus();
			return false;
		}
		else if ( password !== password_verify )
		{
			alert('Lösenorden är inte samma!');

			return false;
		}

		return true;
	});
});