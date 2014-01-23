$(function()
{
	$('#my_subscriptions').find('.stop-subscribe').on('click', function()
	{
		var $this = $(this),
			podcast_id = $this.data('id'),
			$tr = $this.parents('tr');

		$.ajax(
		{
			url: BASE_URL + 'unsubscribe-podcast',
			data:
			{
				podcast_id: podcast_id
			}
		}).done(function()
		{
			$tr.fadeOut();
		});
	});
});