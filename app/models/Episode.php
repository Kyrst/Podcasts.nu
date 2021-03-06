<?php
class Episode extends Eloquent
{
	protected $table = 'episodes';

	public function podcast()
	{
		return $this->belongsTo('Podcast');
	}

	public function listens()
	{
		return $this->belongsToMany('Podcast', 'user_podcasts');
	}

	public function votes()
	{
		return $this->hasMany('Episode_Vote');
	}

	public function comments()
	{
		return $this->hasMany('Episode_Comment');
	}

	public function getTitle()
	{
		return $this->podcast->name . ' - ' . $this->title;
	}

	public function getLink($section = 'avsnitt')
	{
		if ( !in_array($section, array('poddar', 'avsnitt')) )
			throw new Exception();

		//error_log($this->podcast->slug . ' - ' . $this->slug);

		return self::getLinkStatic($this->podcast->podcast_slug, $this->episode_slug, $section);

		//return URL::to($section . '/' . $this->podcast->slug . '/' . $this->slug);
	}

	public static function getLinkStatic($podcast_slug, $episode_slug, $section = 'avsnitt')
	{
		return URL::to($section . '/' . $podcast_slug . '/' . $episode_slug);
	}

	public function haveMedia()
	{
		return !empty($this->media_link);
	}

	public function printPlayButton()
	{
		// Look for user listen
		$user = Auth::user();

		$position = 0;

		if ( $user !== NULL )
		{
			$user_listen = User_Listen::where('user_id', $user->id)->where('episode_id', $this->id)->first();

			if ( $user_listen )
			{
				$position = $user_listen->current_position;
			}
		}

		//$media_link = URL::to('play?url=' . urlencode($this->media_link));
		$media_link = urlencode($this->media_link);

		return '<a href="javascript:" id="player_' . $this->id . '" class="play sm2_button" data-podcast_id="' . $this->podcast_id . '" data-episode_id="' . $this->id . '" data-url="' . $media_link . '" data-id="player_' . $this->id . '" data-title="' . $this->podcast->name . ' - ' . $this->title . '" data-episode_link="' . $this->getLink('avsnitt') . '" data-position="' . $position . '"></a>';
	}

	public function get_score($decimals = null)
	{
		$result = DB::table('episodes')
			->join('episode_votes', 'episodes.id', '=', 'episode_votes.episode_id')
			->select((DB::raw('AVG(episode_votes.score) AS avg_score')))
			->where('episode_votes.episode_id', $this->id)
			->first();

		return $decimals !== NULL ? number_format($result->avg_score, $decimals) : $result->avg_score;
	}

	public function print_rater($disabled = false)
	{
		return '<div data-rating="' . $this->get_score() . '" data-id="' . $this->id . '" data-type="episode"' . ($disabled ? ' data-readOnly="1"' : '') . ' class="raty"></div>';
	}
}