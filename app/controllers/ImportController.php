<?php
set_time_limit(0);

use Toddish\Verify\Models\User as VerifyUser;
use Toddish\Verify\Models\Role as VerifyRole;

class ImportController extends BaseController
{
	public function import()
	{
		$this->clear_db_tables();
		$this->import_users();
		$this->import_categories();
		$this->import_news();
		$this->import_blogs();
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

		print('Tables cleared!');
	}

	private function import_categories()
	{
		// Categories
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'categories.php';

		$num_total = 0;

		foreach ( $categories as $_category )
		{
			print($_category['title']);

			$category = new Category();
			$category->id = $_category['id'];
			$category->title = trim($_category['title']);
			$category->slug = Str::slug($category->title);
			$category->save();

			$num_total++;
		}

		print('Categories Done (Num: ' . $num_total . ')!');
	}

	private function import_users()
	{
		// Users
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'users.php';
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'blog_writers.php';

		$num_total = 0;

		foreach ( $users as $_user )
		{
			print($_user['username']);

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

				$blog_id = NULL;

				foreach ( $blog_writers as $blog_writer )
				{
					if ( $blog_writer['user_id'] == $_user['id'] )
					{
						$blog_id = $blog_writer['blog_id'];
					}
				}

				$user->blog_id = $blog_id;

				$user->save();
			}
			catch ( Exception $e )
			{
				print($e->getMessage());

				continue;
			}

			$role = VerifyRole::find(2);
			$user->roles()->sync(array($role->id));

			$num_total++;
		}

		print('Users Done (Num: ' . $num_total . ')!');

		// Subscriptions
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'user_subscriptions.php';

		$num_total = 0;

		foreach ( $user_subscriptions as $user_subscription )
		{
			print('User Subscription...');

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

		print('User Subscriptions Done (Num: ' . $num_total . ')!');
	}

	private function import_artists()
	{
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'artists.php';

		$num_total = 0;

		foreach ( $artists as $artist )
		{
			print($artist['name']);

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

		print('Artists Done (Num: ' . $num_total . ')!');
	}

	private function import_songs()
	{
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'songs.php';

		// Songs
		$num_total = 0;

		foreach ( $songs as $song )
		{
			print($song['title']);

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

		print('Songs Done (Num: ' . $num_total . ')!');

		// Comments
		$num_total = 0;

		foreach ( $song_comments as $song_comment )
		{
			print('Song Comment #' . $song_comment['id']);

			$episode_comment = new Episode_Comment();
			$episode_comment->episode_id = $song_comment['song_id'];
			$episode_comment->user_id = $song_comment['user_id'];
			$episode_comment->comment = trim($song_comment['comment']);
			$episode_comment->created_at = date('Y-m-d H:i:s', trim($song_comment['added']));
			$episode_comment->save();

			$num_total++;
		}

		print('Song Comments Done (Num: ' . $num_total . ')!');

		// Votes
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'votes.php';

		$num_total = 0;

		foreach ( $votes as $vote )
		{
			//
			print('Song Vote...');

			$episode_vote = new Episode_Vote();
			$episode_vote->episode_id = $vote['song_id'];
			$episode_vote->user_id = $vote['user_id'];
			$episode_vote->score = trim($vote['score']);
			$episode_vote->created_at = date('Y-m-d H:i:s', trim($vote['time']));
			$episode_vote->save();

			$num_total++;
		}

		print('Songs Votes Done (Num: ' . $num_total . ')!');

		// Listens
		$num_total = 0;

		if ( ($handle = fopen(public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'views.csv', 'r')) !== FALSE )
		{
			while ( ($data = fgetcsv($handle, 0, ',')) !== FALSE )
			{
				$_views_song_id = $data[1];
				$_views_date = $data[2];

				$created_at = date('Y-m-d H:i:s', $_views_date);

				print('$num_total: ' . $num_total . ' / $_views_song_id: ' . $_views_song_id . ' / $created_at: ' . $created_at);

				$user_listen = new User_Listen();
				$user_listen->user_id = 0;
				$user_listen->episode_id = $_views_song_id;
				$user_listen->first_time = $created_at;
				$user_listen->created_at = $created_at;
				$user_listen->save();

				$num_total++;
			}

			fclose($handle);
		}

		print('User Listen Done (Num: ' . $num_total . ')!');
	}

	private function import_news()
	{
		// Users
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'news.php';

		$num_total = 0;

		foreach ( $news as $_news_item )
		{
			print($_news_item['title']);

			try
			{
				$news_item = new News_Item();
				$news_item->title = trim($_news_item['title']);
				$news_item->slug = Str::slug($news_item->title);
				$news_item->content = trim($_news_item['content']);
				$news_item->created_at = date('Y-m-d H:i:s', trim($_news_item['added']));
				$news_item->updated_at = date('Y-m-d H:i:s', trim($_news_item['modified']));
				$news_item->save();
			} catch ( Exception $e )
			{
				continue;
			}

			$num_total++;
		}

		print('News Done (Num: ' . $num_total . ')!');
	}

	private function import_blogs()
	{
		// Blogs
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'blogs.php';

		$num_total = 0;

		foreach ( $blogs as $_blog )
		{
			print($_blog['title']);

			$blog = new Blog();
			$blog->id = $_blog['id'];
			$blog->name = trim($_blog['title']);
			$blog->slug = Str::slug($blog->name);
			$blog->description = trim($_blog['description']);
			$blog->save();

			$num_total++;
		}

		print('Blogs Done (Num: ' . $num_total . ')!');

		// Blog Items
		include public_path() . DIRECTORY_SEPARATOR . 'import' . DIRECTORY_SEPARATOR . 'blog_items.php';

		$num_total = 0;

		foreach ( $blog_items as $_blog_item )
		{
			print($_blog_item['title']);

			$user_id = $_blog_item['blog_writer_id'];

			$user = User::find($user_id);

			$blog_item = new Blog_Item();
			$blog_item->blog_id = $user->blog_id;
			$blog_item->user_id = $user_id;
			$blog_item->title = trim($_blog_item['title']);
			$blog_item->slug = Str::slug($blog_item->title);
			$blog_item->body = trim($_blog_item['content']);
			$blog_item->created_at = date('Y-m-d H:i:s', trim($_blog_item['added']));
			$blog_item->save();

			$num_total++;
		}

		print('Blog Items Done (Num: ' . $num_total . ')!');

		// Fix user_id
		foreach ( Blog_Item::all() as $blog_item )
		{
			try
			{
				$user = User::where('blog_id', $blog_item->blog_id)->firstOrFail();

				if ( $user )
				{
					$blog_item->user_id = $user->id;
					$blog_item->save();
				}
			}
			catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
				continue;
			}
		}

		print('Fixed user_id.');
	}
}