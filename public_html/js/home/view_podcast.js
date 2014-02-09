var truncutted_description,
	current_page;

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

	bind_pagination_click();
}

function hide_description_bind()
{
	$('#hide_description').on('click', function()
	{
		$('#description').html(truncutted_description);

		show_more_description_bind();
	});
}

function bind_pagination_click()
{
	$('#pagination_container').find('a').on('click', function()
	{
		if ( $(this).parents('li').hasClass('disabled') )
		{
			return;
		}

		current_page = $(this).data('page');

		get_episodes();
	});
}

function get_episodes()
{
	var episodes_container_height = $('#episodes_container').height();

	$('#episodes_container').css('height', episodes_container_height).html('Laddar...');

	$.getJSON(BASE_URL + 'ajax/get-episodes', { podcast_id: podcast_id, category_id: 0, page: current_page, type: 'podcast_episodes' }, function(result)
	{
		$('#episodes_container').html(result.html).css('height', 'auto');
		$('#pagination_container').html(result.pagination_html);

		bind_pagination_click();

		init_raty();
	});
}