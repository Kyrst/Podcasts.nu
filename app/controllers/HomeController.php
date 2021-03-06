<?php
use Intervention\Image\Image;

class HomeController extends BaseController
{
	const NUM_PER_PAGE = 20;

	public function index()
	{
		// Senaste kommentarer
		$latest_comments = array();

		$append_latest_comments = function($user, $episode) use(&$latest_comments)
		{
			$latest_comments[] = array
			(
				'avatar' => $user->get_avatar_image('index_kommentar'),
				'comment' => ucfirst($user->first_name) . ' kommenterade <a href="' . $episode->getLink() . '">' . $episode->getTitle() . '</a>.'
			);
		};

		$latest_episode_comments = Episode_Comment::orderBy('created_at', 'DESC')
			->limit(5)
			->get();

		foreach ( $latest_episode_comments as $comment )
		{
			$append_latest_comments($comment->user, $comment->episode);
		}

		$this->assign('latest_comments', $latest_comments);

		// Senaste nyheter och blogginlägg
		$latest_blog_items = Blog_Item::where('important', 'yes')->orderBy('created_at', 'DESC')->limit(2);
		$latest_news_items = News_Item::orderBy('created_at', 'DESC')->limit(3);

		$latest_news_and_blog_items = array();

		foreach ( $latest_blog_items->get() as $blog_item )
		{
			$latest_news_and_blog_items[] = array
			(
				'id' => $blog_item->id,
				'title' => $blog_item->title,
				'link' => $blog_item->getLink(),
				'content' => $blog_item->body,
				'timestamp' => strtotime($blog_item->created_at)
			);
		}

		foreach ( $latest_news_items->get() as $news_item )
		{
			$latest_news_and_blog_items[] = array
			(
				'id' => $news_item->id,
				'title' => $news_item->title,
				'link' => $news_item->getLink(),
				'content' => $news_item->content,
				'timestamp' => strtotime($news_item->created_at)
			);
		}

		usort($latest_news_and_blog_items, array($this, 'sort_blog_and_news_items'));

		$this->assign('latest_news_and_blog_items', $latest_news_and_blog_items);

		// Lyssnas just nu
		$listens_right_now = array();

		$episode_listens_right_now = DB::select('SELECT users.first_name, e.id AS episode_id, e.episode_slug, e.title AS episode_title, p.podcast_slug FROM (SELECT * FROM user_listens ORDER BY updated_at DESC) as my_table_tmp INNER JOIN users ON my_table_tmp.user_id = users.id INNER JOIN episodes e ON my_table_tmp.episode_id = e.id INNER JOIN podcasts p ON e.podcast_id = p.id WHERE my_table_tmp.is_listening = "yes" GROUP BY my_table_tmp.user_id HAVING user_id IS NOT NULL ORDER BY my_table_tmp.updated_at DESC LIMIT 5');

		foreach ( $episode_listens_right_now as $listen )
		{
			$listens_right_now[] = array
			(
				'text' => ucfirst($listen->first_name) . ' lyssnar på <a href="' . Episode::getLinkStatic($listen->podcast_slug, $listen->episode_slug) . '">' . $listen->episode_title . '</a>.'
			);
		}

		$this->assign('listens_right_now', $listens_right_now);

		if ( $this->user !== NULL )
		{
			$latest_user_episodes = Episode::join('podcasts', 'podcasts.id', '=', 'episodes.podcast_id')
				->join('user_podcasts', 'user_podcasts.podcast_id', '=', 'podcasts.id')
				->where('user_podcasts.user_id', $this->user->id)
				->where('episodes.hide', 'no')
				->orderBy('episodes.pub_date', 'DESC')
				->take(10)
				->select('episodes.*', 'podcasts.*', 'episodes.episode_slug', 'podcasts.podcast_slug', 'episodes.id AS episode_id')
				->get();

			$latest_user_episodes_view = View::make('home/partials/get_episodes');
			$latest_user_episodes_view->user = $this->user;
			$latest_user_episodes_view->episodes = $latest_user_episodes;
			$latest_user_episodes_view->_podcast = NULL;

			$this->assign('num_latest_user_episodes', $latest_user_episodes->count());
			$this->assign('latest_user_episodes_html', $latest_user_episodes_view->render());
		}

		$this->display('home.index');
	}

	private function sort_blog_and_news_items(array $a, array $b)
	{
		return $b['timestamp'] - $a['timestamp'];
	}

	public function view_news_items()
	{
		$news_item = News_Item::orderBy('created_at', 'DESC');

		$this->assign('news_items', $news_item->get());

		$this->display('home.view_news_items');
	}

	public function view_news_item($date, $slug)
	{
		try
		{
			$news_item = News_Item::where('slug', '=', $slug)->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta nyheten!');

			return Redirect::route('home');
		}

		$this->assign('news_item', $news_item);

		$this->display('home.view_news_item');
	}

	public function view_podcasts()
	{
		$podcasts = Podcast::all()
			->take(400);

		/*header('Content-Type: image/jpeg');
		$image = Image::make($artists[0]->getImage('standard', true))->resize(800, 200);
		die($image);*/

		$podcasts_html = View::make('home/partials/get_podcasts');
		$podcasts_html->num_podcasts = count($podcasts);
		$podcasts_html->podcasts = $podcasts;
		$podcasts_html->user = $this->user;

		$this->assign('podcasts_html', $podcasts_html->render());

		$this->assign('categories', Category::all());

		$this->display('home.view_podcasts', 'Poddar');
	}

	public function ajax_get_podcasts()
	{
		$category_id = Input::get('category_id');

		if ( $category_id > 0 )
			$podcasts = Podcast::where('category_id', '=', $category_id)->get();
		else
			$podcasts = Podcast::all();

		$podcasts->take(16);

		$podcasts_html = View::make('home/partials/get_podcasts');
		$podcasts_html->selected_view_type = Input::get('view_type');
		$podcasts_html->num_podcasts = count($podcasts);
		$podcasts_html->podcasts = $podcasts;
		$podcasts_html->user = $this->user;

		die(json_encode(array
		(
			'html' => $podcasts_html->render()
		)));
	}

	public function ajax_get_episodes()
	{
		$podcast_id = Input::get('podcast_id');
		$category_id = Input::get('category_id');
		$page = Input::get('page');
		$type = Input::get('type', 'episodes');

		DB::getPaginator()->setCurrentPage($page);

		$episodes = Episode::join('podcasts', 'podcasts.id', '=', 'episodes.podcast_id')
			->where('episodes.hide', 'no')
			->orderBy('episodes.pub_date', 'DESC');

		$num_total_episodes_count = $num_total_episodes_count = Episode::join('podcasts', 'podcasts.id', '=', 'episodes.podcast_id');

		if ( $category_id > 0 )
		{
			$episodes = $episodes->where('podcasts.category_id', $category_id);
			$num_total_episodes_count = $num_total_episodes_count->where('podcasts.category_id', $category_id);
		}

		if ( $podcast_id > 0 )
		{
			$episodes = $episodes->where('podcasts.id', $podcast_id);
			$num_total_episodes_count = $num_total_episodes_count->where('podcasts.id', $podcast_id);
		}

		//$episodes = $episodes->select(DB::raw('episodes.slug AS episode_slug'), DB::raw('podcasts.slug AS podcast_slug'))

		$episodes = $episodes->paginate(self::NUM_PER_PAGE);;

		$paginator = Paginator::make($episodes->getItems(), $num_total_episodes_count->count(), self::NUM_PER_PAGE);
		$pagination_view = View::make('home/partials/pagination');
		$pagination_view->paginator = $paginator;
		$pagination_view->total_pages = ceil($paginator->getTotal() / self::NUM_PER_PAGE);

		if ( $type === 'podcast_episodes' )
		{
			$episodes_html = View::make('home/partials/get_podcast_episodes');
			$episodes_html->num_episodes = count($episodes);
			$episodes_html->episodes = $episodes;
			$episodes_html->user = $this->user;
		}
		else
		{
			$episodes_html = View::make('home/partials/get_episodes');
			$episodes_html->num_episodes = count($episodes);
			$episodes_html->episodes = $episodes;
			$episodes_html->user = $this->user;
			$episodes_html->_podcast = NULL;
		}

		die(json_encode(array
		(
			'html' => $episodes_html->render(),
			'pagination_html' => $pagination_view->render()
		)));
	}

	public function view_podcast($slug)
	{
		$episodes = array();

		try
		{
			$podcast = Podcast::where('podcast_slug', '=', $slug)->firstOrFail();

			$episodes = $podcast->episodes()->where('episodes.hide', 'no')->orderBy('pub_date', 'DESC')->paginate(self::NUM_PER_PAGE);
			$num_total_episodes = $podcast->episodes()->count();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta podcasten!');

			return Redirect::route('home');
		}

		$this->assign('podcast', $podcast);

		$paginator = Paginator::make($episodes->getItems(), $num_total_episodes, self::NUM_PER_PAGE);

		$podcast_episodes_view = View::make('home/partials/get_podcast_episodes');
		$podcast_episodes_view->num_episodes = count($episodes);
		$podcast_episodes_view->episodes = $episodes;
		$podcast_episodes_view->user = $this->user;
		$this->assign('podcast_episodes_html', $podcast_episodes_view->render());

		$pagination_view = View::make('home/partials/pagination');
		$pagination_view->paginator = $paginator;
		$pagination_view->total_pages = ceil($paginator->getTotal() / self::NUM_PER_PAGE);
		$this->assign('pagination_html', $pagination_view->render());

		$this->assign('podcast_id', $podcast->id, 'js');

		$this->display('home.view_podcast', $podcast->name );
	}

	public function view_episodes($podcast = NULL)
	{
		if ( $podcast !== NULL )
		{
			try
			{
				$podcast = Podcast::where('podcast_slug', '=', $podcast)->firstOrFail();

				$episodes = $podcast->episodes()->where('episodes.hide', 'no')->orderBy('pub_date', 'DESC')->paginate(self::NUM_PER_PAGE);
				$num_total_episodes = $podcast->episodes()->count();
			}
			catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
				$this->showAlert('Kunde inte hitta podcasten!');

				return Redirect::route('avsnitt');
			}
		}
		else
		{
			$episodes = Episode::orderBy('pub_date', 'DESC')->where('episodes.hide', 'no')->paginate(self::NUM_PER_PAGE);
			$num_total_episodes = Episode::count();
		}

		$paginator = Paginator::make($episodes->getItems(), $num_total_episodes, self::NUM_PER_PAGE);

		$pagination_view = View::make('home/partials/pagination');
		$pagination_view->paginator = $paginator;
		$pagination_view->total_pages = ceil($paginator->getTotal() / self::NUM_PER_PAGE);
		$this->assign('pagination_html', $pagination_view->render());

		$this->assign('podcast', $podcast);

		$this->assign('categories', Category::orderBy('position')->get());

		$episodes_view = View::make('home/partials/get_episodes');
		$episodes_view->user = $this->user;
		$episodes_view->_podcast = $podcast;
		$episodes_view->episodes = $episodes;
		$episodes_view->current_route = $this->current_route_action;

		$this->assign('episodes_html', $episodes_view->render());

		$this->display('home.view_episodes', ($podcast !== NULL ? $podcast->name : 'Avsnitt'));
	}

	public function view_episode($podcast, $episode)
	{
		try
		{
			$episode = Episode::where('episode_slug', '=', $episode)->where('hide', 'no')->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta avsnittet!');

			return Redirect::route('home');
		}

		$this->assign('episode', $episode);

		// Comments
		$comments_html = View::make('ajax/get_episode_comments')
			->with('num_comments', $episode->comments->count())
			->with('comments', $episode->comments)
			->render();

		$this->assign('comments_html', $comments_html);

		$this->assign('episode_id', $episode->id, array('js'));

		$this->display('home.view_episode', $episode->podcast->name . ' - ' . $episode->title );
	}

	public function login()
	{
		$facebook_login_link = 'https://www.facebook.com/dialog/oauth?client_id=' . Config::get('facebook.FACEBOOK_APP_ID') . '&amp;redirect_uri=' . Config::get('facebook.FACEBOOK_REDIRECT_URL');

		$this->assign('facebook_login_link', $facebook_login_link);

		$this->display('home.login', 'Logga In');
	}

	public function register()
	{
		$this->display('home.register');
	}

	public function my_page()
	{
		// Historik
		$this->assign('history', $this->user->get_history(5));

		// Oklara lyssningar
		$this->assign('num_user_listens', $this->user->episode_listens()->where('done', 'no')->count());
		$this->assign('user_listens', $this->user->get_episode_listens());

		$this->display('home.my_page', 'Min Sida');
	}

    public function stats()
    {
        $view_count = DB::table('banner_views')->count();
        $click_count = DB::table('banner_clicks')->count();
        $banner_salary =  $view_count*0.05;
        $listen_count = DB::table('sound_ad')->count();
        $listen_salary = $listen_count*0.25;
        $total_salary = $banner_salary+$listen_salary;
        $this->assign('sound_ad_listens', $listen_count);
        $this->assign('banner_clicks', $click_count);
        $this->assign('banner_views', $view_count);
        $this->assign('banner_salary', $banner_salary);
        $this->assign('listen_salary', $listen_salary);
        $this->assign('total_salary', $total_salary);
        $this->display('home.stats', 'Statistik');
    }

    public function view_blogs()
    {
 		// Bloggar
 	  	$blogs = Blog::all();

    	$this->assign('blogs', $blogs);

    	// Senaste blogginläggen
    	$latest_blog_items = Blog_Item::orderBy('created_at', 'DESC')->get()->take(3);

    	$this->assign('latest_blog_items', $latest_blog_items);

        $this->display('home.view_blogs', 'Bloggar');
    }

    public function view_blog($slug)
    {
		try
		{
			$blog = Blog::where('slug', '=', $slug)->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta bloggen!');

			return Redirect::route('bloggar');
		}

		$this->assign('blog', $blog);

        $this->display('home.view_blog', $blog->name);
    }

    public function view_blog_item($blog_slug, $blog_item_date, $blog_item_slug)
	{
		try
		{
			$blog_item = Blog_Item::where('slug', '=', $blog_item_slug)->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta blogginlägget!');

			return Redirect::route('bloggar');
		}

		$this->assign('blog_item', $blog_item);

		$this->display('home.view_blog_item', $blog_item->title . ' - ' . $blog_item->blog->name);
	}

    public function view_poddsnacks()
    {
		$podtalks = Podtalk::all();

		$this->assign('podtalks', $podtalks);

        $this->display('home.view_poddsnacks', 'Poddsnack');
    }

    public function view_poddsnack($slug)
    {
    	try
		{
    		$podtalk = Podtalk::where('slug', '=', $slug)->firstOrFail();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta poddsnacket!');

			return Redirect::route('poddsnacks');
		}

		$this->assign('podtalk', $podtalk);

        $this->display('home.view_poddsnack', 'Poddsnack');
    }

    public function view_toplist()
    {
		$types = array
		(
			'most_played' => 'Mest spelat',
			'highest_score' => 'Högst betyg',
			'most_comments' => 'Mest kommenterat'
		);

    	$input = Input::all();

    	if ( $input/* && Request::ajax()*/ )
		{
			$result = array
			(
				'html' => ''
			);

			$type_id = $input['type'];
			$category_id = $input['category_id'];

			if ( !isset($types[$type_id]) )
			{
				return Response::json(array('html' => 'Det gick inte att hamta statisiken just nu.'));
			}

			if ( $type_id === 'most_played' )
			{
				// Most played episodes (this week)
				$most_played_this_week = DB::table('user_listens')
					->join('episodes', 'user_listens.episode_id', '=', 'episodes.id')
					->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
					->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('podcasts.name AS podcast_name'), DB::raw('COUNT(user_listens.episode_id) AS num_listens'))
					->where('user_listens.updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
					->where('episodes.hide', 'no');

				if ( $category_id )
				{
					$most_played_this_week = $most_played_this_week->where('podcasts.category_id', $category_id);
				}

				$most_played_this_week = $most_played_this_week
					->orderBy('num_listens', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				// Most played episodes (this month)
				$most_played_this_month = DB::table('user_listens')
					->join('episodes', 'user_listens.episode_id', '=', 'episodes.id')
					->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
					->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('podcasts.name AS podcast_name'), DB::raw('COUNT(user_listens.episode_id) AS num_listens'))
					->where('user_listens.updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
					->where('episodes.hide', 'no');

				if ( $category_id )
				{
					$most_played_this_month = $most_played_this_month->where('podcasts.category_id', $category_id);
				}

				$most_played_this_month = $most_played_this_month
					->orderBy('num_listens', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				// Most played episodes (total)
				$most_played_total = DB::table('user_listens')
					->join('episodes', 'user_listens.episode_id', '=', 'episodes.id')
					->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
					->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('podcasts.name AS podcast_name'), DB::raw('COUNT(user_listens.episode_id) AS num_listens'));

				if ( $category_id )
				{
					$most_played_total = $most_played_total->where('podcasts.category_id', $category_id);
				}

				$most_played_total = $most_played_total
					->where('episodes.hide', 'no')
					->orderBy('num_listens', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				$most_played_view = View::make('home/partials/stats/most_played');
				$most_played_view->most_played_this_week = $most_played_this_week;
				$most_played_view->most_played_this_month = $most_played_this_month;
				$most_played_view->most_played_total = $most_played_total;

				$result['html'] = $most_played_view->render();
			}
			else if ( $type_id === 'highest_score' )
			{
				// Higest score (podcasts)
				$podcasts = DB::table('podcasts')
					->join('episodes', 'podcasts.id', '=', 'episodes.podcast_id')
					->join('episode_votes', 'episodes.id', '=', 'episode_votes.episode_id');

				if ( $category_id )
				{
					$podcasts = $podcasts->where('podcasts.category_id', $category_id);
				}

				$podcasts = $podcasts->select('podcasts.name', 'podcasts.podcast_slug', DB::raw('AVG(episode_votes.score) AS avg_score'))
					->orderBy('avg_score', 'DESC')
					->groupBy('podcasts.id')
					->take(10)
					->get();

				// Higest score (episodes)
				$episodes = DB::table('episodes')
					->join('podcasts', 'episodes.podcast_id', '=', 'podcasts.id')
					->join('episode_votes', 'episodes.id', '=', 'episode_votes.episode_id')
					->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('AVG(episode_votes.score) AS avg_score'));

				if ( $category_id )
				{
					$episodes = $episodes->where('podcasts.category_id', $category_id);
				}

				$episodes = $episodes
					->where('episodes.hide', 'no')
					->orderBy('avg_score', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				$highest_score_view = View::make('home/partials/stats/highest_score');
				$highest_score_view->podcasts = $podcasts;
				$highest_score_view->episodes = $episodes;

				$result['html'] = $highest_score_view->render();
			}
			else if ( $type_id === 'most_comments' )
			{
				// Most comments this week
				$episodes_this_week = DB::table('podcasts')
					->join('episodes', 'podcasts.id', '=', 'episodes.podcast_id')
					->join('episode_comments', 'episodes.id', '=', 'episode_comments.episode_id')
					->where('episode_comments.updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'))
					->where('episodes.hide', 'no');

				if ( $category_id )
				{
					$episodes_this_week = $episodes_this_week->where('podcasts.category_id', $category_id);
				}

				$episodes_this_week = $episodes_this_week->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('COUNT(episode_comments.id) AS num_comments'))
					->orderBy('num_comments', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				// Most comments this month
				$episodes_this_month = DB::table('podcasts')
					->join('episodes', 'podcasts.id', '=', 'episodes.podcast_id')
					->join('episode_comments', 'episodes.id', '=', 'episode_comments.episode_id')
					->where('episode_comments.updated_at', '>', DB::raw('DATE_SUB(NOW(), INTERVAL 1 MONTH)'))
					->where('episodes.hide', 'no');

				if ( $category_id )
				{
					$episodes_this_month = $episodes_this_month->where('podcasts.category_id', $category_id);
				}

				$episodes_this_month = $episodes_this_month->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('COUNT(episode_comments.id) AS num_comments'))
					->orderBy('num_comments', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				// Most comments in total
				$episodes_total = DB::table('podcasts')
					->join('episodes', 'podcasts.id', '=', 'episodes.podcast_id')
					->join('episode_comments', 'episodes.id', '=', 'episode_comments.episode_id');

				if ( $category_id )
				{
					$episodes_total = $episodes_total->where('podcasts.category_id', $category_id);
				}

				$episodes_total = $episodes_total->select('episodes.title', 'episodes.episode_slug', 'podcasts.podcast_slug', DB::raw('COUNT(episode_comments.id) AS num_comments'))
					->where('episodes.hide', 'no')
					->orderBy('num_comments', 'DESC')
					->groupBy('episodes.id')
					->take(10)
					->get();

				$most_comments_view = View::make('home/partials/stats/most_comments');
				$most_comments_view->most_commented_this_week = $episodes_this_week;
				$most_comments_view->most_commented_this_month = $episodes_this_month;
				$most_comments_view->most_commented_total = $episodes_total;

				$result['html'] = $most_comments_view->render();
			}

			return Response::json($result);
		}

		$this->assign('categories', Category::all());
		$this->assign('types', $types);

        $this->display('home.view_toplist', 'Topplista');
    }

	public function set_password()
	{
		$input = Input::all();

		if ( $input )
		{
			$user_id = $input['user_id'];

			try
			{
				$user = User::find($user_id);
				$password = trim($input['password']);

				$user->password = $password;
				$user->save();

				Auth::attempt(array
				(
					'email' => $user->email,
					'password' => $password
				), true);

				return Redirect::route('min-sida');
			}
			catch ( Exception $e )
			{
				return Redirect::route('home');
			}
		}

		$user_id = Session::get('user_id');

		if ( !$user_id )
		{
			return Redirect::route('home');
		}

		$this->assign('user_id', $user_id);

		$this->display('home.set_password', 'Sätt nytt lösenord');
	}

	public function sign_up()
	{
		$input = Input::all();

		if ( $input )
		{
			$username = trim($input['username']);
			$email = trim($input['email']);
			$password = trim($input['password']);
			$first_name = trim($input['first_name']);
			$last_name = trim($input['last_name']);
			$city = trim($input['city']);
			$birthdate = trim($input['birthdate']);

			try
			{
				$user = new User();
				$user->username = $username;
				$user->slug = Str::slug($username);
				$user->password = $password;
				$user->email = $email;
				$user->first_name = $first_name;
				$user->last_name = $last_name;
				$user->city = $city;
				$user->birthdate = $birthdate;
				$user->verified = 1;
				$user->save();

				$user->roles()->sync(array(2));

				Auth::attempt
				(
					array
					(
						'email' => $email,
						'password' => $password
					),
					true
				);
			}
			catch ( Exception $e )
			{
				die(var_dump($e->getMessage()));
				return Redirect::route('sign-up')->with('sign_up_error', 'Det gick inte att bli medlem just nu.');
			}
		}

		$this->assign('sign_up_error', Session::get('sign_up_error'));

		$this->loadLib('datepicker');

		$this->display('home.sign_up', 'Bli medlem');
	}

	public function search()
	{
		$input = Input::all();

		$search_term = isset($input['q']) ? trim($input['q']) : '';

		$num_found = 0;

		// Podcasts
		$podcasts = Podcast::where('name', 'LIKE', '%' . $search_term . '%')->get();

		if ( $podcasts->count() > 0 )
		{
			$num_found += $podcasts->count();
		}

		// Avsnitt
		$episodes = Episode::where('title', 'LIKE', '%' . $search_term . '%')->where('episodes.hide', 'no')->get();

		if ( $episodes->count() > 0 )
		{
			$num_found += $episodes->count();
		}

		$page_title = $search_term;

		$this->assign('search_term', $search_term, array('content', 'layout'));
		$this->assign('num_found', $num_found);
		$this->assign('podcasts', $podcasts);
		$this->assign('episodes', $episodes);

		$this->display('home.search', $page_title);
	}

	public function settings()
	{
		$default_tab = Session::get('default_tab', 'general');

		$input = Input::all();

		if ( isset($input['save_general']) )
		{
			$this->user->first_name = trim($input['first_name']);
			$this->user->last_name = trim($input['last_name']);
			$this->user->email = trim($input['email']);
			$this->user->city = trim($input['city']);
			$this->user->birthdate = trim($input['birthdate']);
			$this->user->save();

			$this->showAlert('Inställningar sparade!');

			return Redirect::route('installningar')->with('default_tab', 'general');
		}
		else if ( isset($input['save_avatar']) )
		{
			User::upload_avatar(Input::file('avatar'), $this->user->id);

			$this->user->avatar = 'yes';
			$this->user->save();

			return Redirect::route('installningar')
				->with('default_tab', 'avatar');
		}

		$this->assign('default_tab', $default_tab);

		$this->display('home.settings', 'Inställningar');
	}

	public function info()
	{
		$this->display('home.info', 'Info');
	}
}