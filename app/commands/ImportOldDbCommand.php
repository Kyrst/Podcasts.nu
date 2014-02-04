<?php
use Illuminate\Console\Command;

use Toddish\Verify\Models\User as VerifyUser;
use Toddish\Verify\Models\Role as VerifyRole;

class ImportOldDbCommand extends Command
{
	protected $name = 'user:import_old_db';

	protected $description = 'Importera gamla podcasts.nu-databasen.';

	public function fire()
	{
		$this->clear_db_tables();
		$this->import_users();
		$this->import_artists();
		$this->import_songs();
	}

	private function clear_db_tables()
	{
		$table_names = array
		(
			'blogs',
			'blog_items',
			'categories',
			'episodes',
			'episode_comments',
			'news_items',
			'podcasts',
			'podtalks',
			'role_user',
			'users',
			'user_listens',
			'user_podcasts'
		);

		foreach ( $table_names as $table_name )
		{
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');

			DB::table($table_name)->truncate();

			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		}

		$this->line('Tables cleared!');
	}

	private function import_users()
	{
		// Users
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'users.php';

		$num_total = 0;

		foreach ( $users as $_user )
		{
			$this->line($_user['username']);

			try
			{
				$user = new VerifyUser();
				$user->id = $_user['id'];
				$user->username = trim($_user['username']);

				if ( Str::slug($user->username) === '' )
				{
					continue;
				}

				$user->slug = Str::slug($user->username);
				$user->password = '';
				$user->first_name = trim($_user['first_name']);
				$user->last_name = trim($_user['last_name']);
				$user->city = !empty($_user['city']) ? trim($_user['city']) : NULL;
				$user->birthdate = $_user['birthdate'] !== '0000-00-00' ? trim($_user['birthdate']) : NULL;
				$user->facebook_id = trim($_user['facebook_id']);
				$user->last_login = date('Y-m-d H:i:s', trim($_user['last_login']));
				$user->created_at = date('Y-m-d H:i:s', trim($_user['registered']));
				$user->save();
			}
			catch ( Exception $e )
			{
				continue;
			}

			$role = VerifyRole::find(2);
			$user->roles()->sync(array($role->id));

			$num_total++;
		}

		$this->line('Users Done (Num: ' . $num_total . ')!');

		// Subscriptions
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'user_subscriptions.php';

		$num_total = 0;

		foreach ( $user_subscriptions as $user_subscription )
		{
			$this->line('User Subscription...');

			try
			{
				$user_podcast = new User_Podcast();
				$user_podcast->user_id = $user_subscription['user_id'];
				$user_podcast->podcast_id = $user_subscription['artist_id'];
				$user_podcast->save();
			}
			catch ( Exception $e )
			{
				continue;
			}

			$num_total++;
		}

		$this->line('User Subscriptions Done (Num: ' . $num_total . ')!');
	}

	private function import_artists()
	{
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'artists.php';

		$num_total = 0;

		foreach ( $artists as $artist )
		{
			$this->line($artist['name']);

			$podcast = new Podcast();
			$podcast->id = $artist['id'];
			$podcast->name = trim($artist['name']);
			$podcast->slug = Str::slug($podcast->name);
			$podcast->description = trim($artist['description']);
			$podcast->rss = trim($artist['rss']);
			$podcast->category_id = $artist['category_id'];
			$podcast->homepage = trim($artist['homepage']);
			$podcast->facebook = trim($artist['facebook']);
			$podcast->twitter = trim($artist['twitter']);
			$podcast->itunes = trim($artist['itunes']);
			$podcast->save();

			$num_total++;
		}

		$this->line('Artists Done (Num: ' . $num_total . ')!');
	}

	private function import_songs()
	{
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'songs.php';

		// Songs
		$num_total = 0;

		foreach ( $songs as $song )
		{
			$this->line($song['title']);

			$episode = new Episode();
			$episode->id = $song['id'];
			$episode->unique_id = $song['unique_id'];
			$episode->podcast_id = $song['artist_id'];
			$episode->title = trim($song['title']);
			$episode->slug = Str::slug($episode->title);
			$episode->description = trim($song['description']);
			$episode->media_link = trim($song['media_link']);
			$episode->pub_date = trim($song['pub_date']);
			$episode->num_views = trim($song['views']);
			$episode->created_at = date('Y-m-d H:i:s', trim($song['added']));
			$episode->updated_at = date('Y-m-d H:i:s', trim($song['modified']));
			$episode->save();

			$num_total++;
		}

		$this->line('Songs Done (Num: ' . $num_total . ')!');

		// Comments
		$num_total = 0;

		foreach ( $song_comments as $song_comment )
		{
			$this->line('Song Comment #' . $song_comment['id']);

			$episode_comment = new Episode_Comment();
			$episode_comment->episode_id = $song_comment['song_id'];
			$episode_comment->user_id = $song_comment['user_id'];
			$episode_comment->comment = trim($song_comment['comment']);
			$episode_comment->created_at = date('Y-m-d H:i:s', trim($song_comment['added']));
			$episode_comment->save();

			$num_total++;
		}

		$this->line('Song Comments Done (Num: ' . $num_total . ')!');

		// Votes
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'votes.php';

		$num_total = 0;

		foreach ( $votes as $vote )
		{
			$this->line('Song Vote...');

			$episode_vote = new Episode_Vote();
			$episode_vote->episode_id = $vote['song_id'];
			$episode_vote->user_id = $vote['user_id'];
			$episode_vote->score = trim($vote['score']);
			$episode_vote->created_at = date('Y-m-d H:i:s', trim($vote['time']));
			$episode_vote->save();

			$num_total++;
		}

		$this->line('Songs Votes Done (Num: ' . $num_total . ')!');
	}
}