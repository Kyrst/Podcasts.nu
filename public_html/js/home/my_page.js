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

	$('#not_done_table').find('.mark-as-done').on('click', function()
	{
		var $this = $(this),
			episode_id = $this.data('episode_id');

		$.ajax(
		{
			type: 'POST',
			url: BASE_URL + 'markera-som-fardig',
			data:
			{
				episode_id: episode_id
			}
		}).done(function()
		{
			if ( $('#not_done_table').find('.not-done-row').length === 1 )
			{
				$('#not_done_container').fadeOut();
			}
			else
			{
				$('#not_done_row_' + episode_id).fadeOut(function()
				{

				});
			}
		});
	});
});