<?php
use Toddish\Verify\Models\User as VerifyUser;

class User extends VerifyUser
{
	protected $table = 'users';

	protected $softDelete = true;

	protected $hidden = array('password');

	public function episode_listens()
	{
		return $this->belongsToMany('Episode', 'user_listens');
	}

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

	public function podcasts()
	{
		return $this->belongsToMany('Podcast', 'user_podcasts');
	}

	public function get_history($num = NULL)
	{
		$history = array();

		$user = Auth::user();

		$history[] = array
		(
			'message' => 'Registrerade sig som medlem på Podcasts.nu.',
			'timestamp' => strtotime($user->created_at)
		);

		// Hämta lyssningar
		$user_listens = User_Listen::all();

		if ( $num !== NULL )
		{
			$user_listens = $user_listens->take($num);
		}

		foreach ( $user_listens as $user_listen )
		{
			$history[] = array
			(
				'message' => 'Lyssnade på <a href="' . $user_listen->episode->getLink('poddar') . '">' . $user_listen->episode->getTitle() . '</a>.',
				'timestamp' => strtotime($user_listen->created_at)
			);
		}

		usort($history, array($this, 'sort_history'));

		return $history;
	}

	private function sort_history(array $a, array $b)
	{
		return $b['timestamp'] - $a['timestamp'];
	}

	public function get_age()
	{
		$now = new DateTime();
		$birthday = new DateTime($this->birthdate);
		$interval = $now->diff($birthday);

		return $interval->format('%y');
	}

	public function get_profile_page()
	{
		return URL::route('home');
	}

	public function get_latest_listened_episode($podcast_id)
	{
		try
		{
			$latest_listened_episode = $this->episode_listens()->where('episodes.podcast_id', $podcast_id)->orderBy('user_listens.created_at', 'DESC')->firstOrFail();
		} catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			return NULL;
		}

		return $latest_listened_episode;
	}

	public function get_episode_listens()
	{
		return array();
	}
}