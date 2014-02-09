var $selected_category_text,
	selected_category_id,
	current_page = 1;

$(function()
{
	$selected_category_text = $('#selected_category_text');

	$('#category_select').find('.category').on('click', function()
	{
		selected_category_id = $(this).attr('data-id');

		var category_title = $(this).attr('data-title');

		$selected_category_text.html(category_title);

		get_episodes();
	});

	bind_pagination_click();
});

function bind_pagination_click()
{
	$('#episodes').find('.pagination a').on('click', function()
	{
		current_page = $(this).data('page');

		get_episodes();
	});
}

function get_episodes()
{
	var episodes_container_height = $('#episodes_container').height();

	$('#episodes_container').css('height', episodes_container_height).html('Laddar...');

	$.getJSON(BASE_URL + 'ajax/get-episodes', { category_id: selected_category_id, page: current_page, type: 'episodes' }, function(result)
	{
		$('#episodes_container').html(result.html).css('height', 'auto');
		$('#pagination_container').html(result.pagination_html);

		bind_pagination_click();
	});
}