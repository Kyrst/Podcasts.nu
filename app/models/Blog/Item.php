<?php
class Blog_Item extends Eloquent
{
	protected $table = 'blog_items';

	public function user()
	{
		return $this->belongsTo('User');
	}
}