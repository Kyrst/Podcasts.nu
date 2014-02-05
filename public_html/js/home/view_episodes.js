var $selected_category_text;

$(function()
{
	$selected_category_text = $('#selected_category_text');

	$('#category_select').find('.category').on('click', function()
	{
		var category_id = $(this).attr('data-id'),
			category_title = $(this).attr('data-title');

		$selected_category_text.html(category_title);

		get_episodes(category_id);
	});
});

function get_episodes(category_id)
{
	$.getJSON(BASE_URL + 'ajax/get-episodes', { category_id: category_id }, function(result)
	{
		$('#episodes_container').html(result.html);
	});
}