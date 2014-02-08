var $stats_container,
	selected_category_id = null, selected_type_id = 'most_played';

$(document).ready(function()
{
	$stats_container = $('#stats_container');

	$('.category').on('click', function()
	{
		var id = $(this).data('id'),
			title = $(this).data('title');

		selected_category_id = id;
		$('#selected_category_text').html(title);

		refresh_stats();
	});

	$('.type').on('click', function()
	{
		var id = $(this).data('id'),
			title = $(this).data('title');

		selected_type_id = id;
		$('#selected_type_text').html(title);

		refresh_stats();
	});

	refresh_stats();
});

function refresh_stats()
{
	$stats_container.html('Laddar...');

	$.ajax(
	{
		url: BASE_URL + 'topplista/hamta',
		data:
		{
			category_id: selected_category_id,
			type: selected_type_id
		}
	}).done(function(result)
	{
		$stats_container.html(result.html);
	});
}