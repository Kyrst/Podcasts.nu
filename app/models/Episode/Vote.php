<?php
class Episode_Vote extends Eloquent
{
	protected $table = 'episode_votes';

	public function episode()
	{
		return $this->belongsTo('Episode');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}
}