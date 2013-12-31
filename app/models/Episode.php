<?php
class Episode extends Eloquent
{
	protected $table = 'episodes';

	public function podcast()
	{
		return $this->belongsTo('Podcast');
	}

	public function comments()
	{
		return $this->hasMany('Episode_Comment');
	}

	public function getTitle()
	{
		return $this->podcast->name . ' - ' . $this->title;
	}

	public function getLink($section)
	{
		if ( !in_array($section, array('poddar', 'avsnitt')) )
			throw new Exception();

		return URL::to($section . '/' . $this->podcast->slug . '/' . $this->slug);
	}

	public function haveMedia()
	{
		return !empty($this->media_link);
	}

	public function printPlayButton()
	{
		return '<a href="javascript:" id="player_' . $this->id . '" class="play sm2_button" data-episode_id="' . $this->id . '" data-url="' . $this->media_link . '" data-id="player_' . $this->id . '" data-title="' . $this->podcast->name . ' - ' . $this->title . '" data-episode_link="' . $this->getLink('avsnitt') . '"></a>';
	}

	public function get_score()
	{
		$result = DB::table('episodes')
			->join('episode_votes', 'episodes.id', '=', 'episode_votes.episode_id')
			->select((DB::raw('AVG(episode_votes.score) AS avg_score')))
			->where('episode_votes.episode_id', $this->id)
			->first();

		return $result->avg_score;
	}

	public function print_rater()
	{
		return '<div data-rating="' . $this->get_score() . '" data-id="' . $this->id . '" data-type="episode" class="raty"></div>';
	}
}