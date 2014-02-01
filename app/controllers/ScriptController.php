<?php
class ScriptController extends Controller
{
	private function fix_imported_str($str)
	{
		return html_entity_decode($str, ENT_COMPAT, 'UTF-8');
	}

	public function download_podcasts()
	{
		$simple_pie = new SimplePie();
		$simple_pie->set_cache_location(storage_path() . '/cache');
		$simple_pie->set_cache_duration(100);

		$podcasts = Podcast::all();

		foreach ( $podcasts as $podcast )
		{
			error_log('Loading podcast "' . $podcast->name . '"...');

			$simple_pie->set_feed_url($podcast->rss);
			$simple_pie->init();
			$simple_pie->handle_content_type();

			foreach ( $simple_pie->get_items() as $item )
			{
				$unique_id = $item->get_id(true);

				$media_link = ($enclosure = $item->get_enclosure()) ? $enclosure->get_link() : $simple_pie->get_link(0);

				if ( !$media_link )
				{
					$media_link = NULL;
				}

				$date = DateTime::createFromFormat('Y-m-d H:i:s', $item->get_date('Y-m-d H:i:s'));

				try
				{
					$episode = Episode::where('unique_id', $unique_id)->get();
				}
				catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
				{
					$episode = new Episode();
					$episode->podcast_id = $podcast->id;
				}

				$episode->unique_id = $unique_id;
				$episode->title = $this->fix_imported_str($item->get_title());
				$episode->slug = Str::slug($episode->title);
				$episode->description =  $this->fix_imported_str($item->get_description());
				$episode->media_link = $media_link;
				$episode->pub_date = $date->getTimestamp();

				$episode->save();

				die('save');
			}
		}
	}
}