$(function()
{
	CKEDITOR.replace('_content',
	{
		filebrowserUploadUrl: BASE_URL + '/admin/upload_news_item_image',
		height: 345
	})
});