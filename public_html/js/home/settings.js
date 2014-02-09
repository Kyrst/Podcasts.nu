document.getElementById('avatar_file_input').addEventListener('change', avatar_file_change, false);

function avatar_file_change(e)
{
	var files = e.target.files,
		num_files = files.length;

	var okay = false;

	if ( num_files === 1 )
	{
		var file = files[0];

		if ( file.type.match('image.*') )
		{
			okay = true;
		}
	}

	if ( okay )
	{
		$('#save_avatar_button').removeClass('disabled');
	}
	else
	{
		$('#save_avatar_button').addClass('disabled');
	}
}