var truncutted_description;

$(function()
{
	show_more_description_bind();
});

function show_more_description_bind()
{
	$('#show_more_description').on('click', function()
	{
		truncutted_description = $('#description').html();

		$('#description').html($('#full_description').html() + ' <a href="javascript:" id="hide_description" class="btn btn-xs btn-primary">Dölj</a>');

		hide_description_bind();
	});
}

function hide_description_bind()
{
	$('#hide_description').on('click', function()
	{
		$('#description').html(truncutted_description);

		show_more_description_bind();
	});
}