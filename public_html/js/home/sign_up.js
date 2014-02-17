$(function()
{
	$('#sign_up_form').on('submit', function()
	{
		var username = $('#username').val(),
			first_name = $('#first_time').val(),
			last_name = $('#last_name').val(),
			email = $('#email').val(),
			password = $('#password').val(),
			password_verify = $('#password_verify').val();

		var error = '';

		// Check for empty fields
		if ( username === '' || first_name === '' || last_name === '' || email === '' || password === '' || password_verify === '' )
		{
			error = 'Ett eller flera obligatoriska fält är tomma.';
		}
		else if ( !validate_email(email) ) // Check email
		{
			error = 'Ange en giltig e-mailadress.';
		}
		else if ( password !== password_verify )
		{
			error = 'Lösenorden är inte samma.';
		}
		else
		{
			$.ajax(
			{
				type: 'POST',
				url: BASE_URL + 'user-exists',
				data:
				{
					username: username,
					email: email
				},
				async: false
			}).done(function(result)
			{
				if ( result.error === 'USERNAME_EXISTS' )
				{
					error = 'Det finns redan en användare med det användarnamnet.';
				}
				else if ( result.error === 'EMAIL_EXISTS' )
				{
					error = 'Det finns redan en användare med den e-mailadressen.';
				}
			});
		}

		return false;

		if ( error !== '' )
		{
			alert(error);

			return false;
		}
	});
});

function validate_email(email)
{
	var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	return regex.test(email);
}