var $comments_container,
	$comment_button, original_comment_button_text,
	$comment;

$(function()
{
	$comments_container = $('#comments_container');

	$comment_button = $('#comment_button');
	original_comment_button_text = $comment_button.html();

	$comment = $('#comment');

	$('#comment_form').on('submit', function()
	{
		$comment_button.html('Kommenterar...');

		$.ajax(
		{
			url: BASE_URL + 'ajax/comment-episode',
			type: 'POST',
			data: { id: episode_id, comment: $comment.val() },
			dataType: 'json'
		}).done(function(result)
		{
			$comment.val('');

			refresh_comments();
		}).always(function()
		{
			$comment_button.html(original_comment_button_text);
		});

		return false;
	});
});

function refresh_comments()
{
	$comments_container.html('Laddar...');

	$.getJSON(BASE_URL + 'ajax/get-episode-comments/' + episode_id, function(result)
	{
		$comments_container.html(result.html);
	});
}