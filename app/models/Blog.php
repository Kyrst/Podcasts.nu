<?php
class Blog extends Eloquent
{
	protected $table = 'blogs';

	public function users()
	{
		return $this->hasMany('User');
	}

	public function items()
	{
		return $this->hasMany('Blog_Item');
	}

	public function getLink()
	{
		return URL::to('bloggar/' . $this->slug);
	}
}