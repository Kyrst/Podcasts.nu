<?php
class Episode_Listen extends Eloquent
{
	protected $table = 'episode_listens';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function episode()
	{
		return $this->belongsTo('Episode');
	}
}