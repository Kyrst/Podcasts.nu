<?php
class Blog_Item extends Eloquent
{
	protected $table = 'blog_items';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function blog()
	{
		return $this->belongsTo('Blog');
	}

	public function getLink()
	{
		return URL::to($this->blog->getLink() . '/' . date('Y-m-d', strtotime($this->created_at)) . '/' . $this->slug);
	}
}