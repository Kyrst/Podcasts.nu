<?php
use Toddish\Verify\Models\User as VerifyUser;

class User extends VerifyUser
{
	protected $table = 'users';

	protected $hidden = array('password');

	public function getAvatar()
	{
		return URL::to('images/avatars/default.png', array(), false);
	}

	public function is_admin()
	{
		return $this->is('Admin');
	}

	public function getDisplayName()
	{
		return !empty($this->first_name) && !empty($this->last_name) ? $this->first_name . ' ' . $this->last_name : $this->email;
	}

	public function blog()
	{
		return $this->belongsTo('Blog');
	}

	public function get_history()
	{
		$history = array();

		$user = Auth::user();

		$history[] = array
		(
			'message' => 'Registrerade sig medlem på Podcasts.nu.',
			'timestamp' => strtotime($user->created_at)
		);

		// Hämta lyssningar
		$episode_listens = Episode_Listen::all();

		foreach ( $episode_listens as $episode_listen )
		{
			$history[] = array
			(
				'message' => 'Lyssnade på <a href="' . $episode_listen->episode->getLink('poddar') . '">' . $episode_listen->episode->getTitle() . '</a>.',
				'timestamp' => strtotime($episode_listen->time)
			);
		}

		usort($history, array($this, 'sort_history'));

		return $history;
	}

	private function sort_history(array $a, array $b)
	{
		return $b['timestamp'] - $a['timestamp'];
	}
}