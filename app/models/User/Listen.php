<?php
class User_Listen extends Eloquent
{
	protected $table = 'user_listens';

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function episode()
	{
		return $this->belongsTo('Episode');
	}
}