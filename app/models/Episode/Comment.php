<?php
class Episode_Comment extends Eloquent
{
	protected $table = 'episode_comments';

	public function user()
	{
		return $this->belongsTo('User');
	}
}