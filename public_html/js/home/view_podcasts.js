var $grid_view, $list_view, selected_view_type = 'grid',
	$selected_category_text,
	$podcasts;

$(function()
{
	$grid_view = $('#grid_view');
	$list_view = $('#list_view');

	$selected_category_text = $('#selected_category_text');

	$podcasts = $('#podcasts');

	$('.change-view-type').on('click', function()
	{
		var view_type = $(this).attr('data-view_type');

		if (view_type === 'grid')
		{
			selected_view_type = 'grid';

			$list_view.hide();
			$grid_view.show();
		}
		else
		{
			selected_view_type = 'list';

			$grid_view.hide();
			$list_view.show();
		}
	});

	$('.category').click(function()
	{
		var category_id = $(this).attr('data-id'),
			category_title = $(this).attr('data-title');

		$selected_category_text.html(category_title);

		getPodcasts(category_id);
	});
});

function getPodcasts(category_id)
{
	$.getJSON(BASE_URL + 'ajax/get_podcasts', { category_id: category_id, view_type: selected_view_type }, function(result)
	{
		$podcasts.html(result.html);

		$grid_view = $('#grid_view');
		$list_view = $('#list_view');
	});
}