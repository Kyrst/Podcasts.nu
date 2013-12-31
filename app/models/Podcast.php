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
		return $this->slug . '-' . $type . '.jpg';
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

		return URL::to($section . '/' . $this->slug);
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
}