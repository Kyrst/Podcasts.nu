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

	public static function format_seconds($seconds)
	{
		$seconds = round($seconds);

		$minutes = floor($seconds / 60);
		$minutes = ($minutes >= 10) ? $minutes : '0' . $minutes;

		$seconds = floor($seconds % 60);
		$seconds = ($seconds >= 10) ? $seconds : '0' . $seconds;

		return $minutes . ':' . $seconds;
	}
}