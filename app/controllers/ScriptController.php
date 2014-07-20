<?php
set_time_limit(0);

class ScriptController extends Controller
{
	private function fix_imported_str($str)
	{
		return html_entity_decode($str, ENT_COMPAT, 'UTF-8');
	}

	public function download_podcasts($podcast_id = NULL)
	{
		$podcasts = array();
        ini_set('memory_limit','150M');
		if ( $podcast_id !== NULL )
		{
			try
			{
				$podcast = Podcast::find($podcast_id)->firstOrFail();

				$podcasts[] = $podcast;
			}
			catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
				die('Kunde inte hitta podcasten med ID "' . $podcast_id . '".');
			}
		}
		else
		{
			$podcasts = Podcast::all();
		}

		$simple_pie = new SimplePie();
		$simple_pie->set_cache_location(storage_path() . DIRECTORY_SEPARATOR . 'cache');
		$simple_pie->set_cache_duration(100);

		foreach ( $podcasts as $podcast )
		{
			//error_log('Laddar podcast "' . $podcast->name . '"...');

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
					$episode = Episode::where('unique_id', $unique_id)->firstOrFail();
				}
				catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
				{
					$episode = new Episode();
					$episode->podcast_id = $podcast->id;
				}

				$episode->unique_id = $unique_id;
				$episode->title = Str::limit($this->fix_imported_str($item->get_title()), 250);
				$episode->episode_slug = Str::slug($episode->title);
				$episode->description = preg_replace('/<(\s*)img[^<>]*>/i', '', $this->fix_imported_str($item->get_description()));
				$episode->media_link = $media_link;
				$episode->pub_date = $date->getTimestamp();

				$episode->save();
			}
		}
	}
}