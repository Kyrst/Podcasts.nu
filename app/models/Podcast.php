<?php
class Podcast extends Eloquent
{
	protected $table = 'podcasts';

	public function category()
	{
		return $this->belongsTo('Category');
	}

	public function episodes()
	{
		return $this->hasMany('Episode');
	}

	public function getImageFolder($create_if_not_exists = false)
	{
		$dir = PODCAST_IMAGES_DIR_ABSOLUTE . $this->id;

		if ( !file_exists($dir) && $create_if_not_exists )
		{
			mkdir($dir);
		}

		return $dir;
	}

	public function getImageFilename($type)
	{
		return $this->podcast_slug . '-' . $type . '.jpg';
	}

	public function hasImage($type)
	{
		return file_exists(PODCAST_IMAGES_DIR_ABSOLUTE . $this->id . '/' . $this->getImageFilename($type));
	}

	public function getImage($type, $absolute = false, $base_url = false)
	{
		if ( $this->hasImage($type) )
		{
			return ($base_url ? BASE_URL : '') . ($absolute ? PODCAST_IMAGES_DIR_ABSOLUTE : PODCAST_IMAGES_DIR) . $this->id . '/' . $this->getImageFilename($type);
		}
	}

	public function getLink($section)
	{
		if ( !in_array($section, array('poddar', 'avsnitt')) )
			throw new Exception();

		return self::getLinkStatic($this->podcast_slug, $section);
	}

	public static function getLinkStatic($podcast_slug, $section)
	{
		return URL::to($section . '/' . $podcast_slug);
	}

	public function get_score($decimals = NULL)
	{
		$result = DB::table('podcasts')
			->join('episodes', 'podcasts.id', '=', 'episodes.podcast_id')
			->join('episode_votes', 'episodes.id', '=', 'episode_votes.episode_id')
			->select((DB::raw('AVG(episode_votes.score) AS avg_score')))
			->where('podcasts.id', $this->id)
			->first();

		return ($decimals === NULL) ? $result->avg_score : number_format($result->avg_score, $decimals);
	}

	public function print_rater()
	{
		return '<div data-rating="' . $this->get_score() . '" data-id="' . $this->id . '" data-type="podcast" data-readOnly="true" class="raty"></div>';
	}

	public function get_subscription_link($subscribe_link_text, $unsubscribe_link_text, $user, $classes = '')
	{
		$is_subscribing = false;

		if ( $user !== NULL )
		{
			$is_subscribing = (User_Podcast::where('user_id', '=', $user->id)
				->where('podcast_id', '=', $this->id)
				->count() === 1);
		}

		return '<a href="javascript:" data-id="' . $this->id . '" data-' . (!$is_subscribing ? 'unsubscribe_text="' . e($unsubscribe_link_text) . '"' : 'subscribe_text="' . e($subscribe_link_text) . '"') . ' class="' . ($is_subscribing ? 'unsubscribe' : 'subscribe') . ($classes !== '' ? ' ' . $classes : '') . '">' . ($is_subscribing ? $unsubscribe_link_text : $subscribe_link_text) . '</a>';
	}

	public function get_num_listens($user_id)
	{
		$num_listens = DB::table('user_listens')
			->join('episodes', 'episodes.id', '=', 'user_listens.episode_id')
			->where('user_listens.user_id', $user_id)
			->where('episodes.podcast_id', $this->id)
			->count();

		return $num_listens;
	}

	public function get_latest_epsiode()
	{
		try
		{
			$latest_episode = $this->episodes()->orderBy('created_at', 'DESC')->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			return NULL;
		}

		return $latest_episode;
	}
}