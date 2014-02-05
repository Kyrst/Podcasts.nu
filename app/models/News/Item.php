<?php
class News_Item extends Eloquent
{
	protected $table = 'news_items';

	public function getLink()
	{
		return URL::to('nyheter/' . date('Y-m-d', strtotime($this->created_at)) . '/' . $this->slug);
	}
}