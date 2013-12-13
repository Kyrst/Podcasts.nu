$(function()
{
	CKEDITOR.replace('_content',
	{
		filebrowserUploadUrl: BASE_URL + 'admin/upload_news_item_image',
		height: 345
	});

	$('#title').on('blur', function()
	{
		$.get
		(
			BASE_URL + 'slugify',
			{
				text: $('#title').val()
			},
			function(slug)
			{
				$('#slug').val(slug);
			}
		);
	});
});