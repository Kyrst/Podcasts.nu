<?php
use Intervention\Image\Image;

class HomeController extends BaseController
{
	public function index()
	{
		// Senaste kommentarer
		$latest_comments = array();

		$append_latest_comments = function($user, $episode) use(&$latest_comments)
		{
			$latest_comments[] = array
			(
				'avatar' => '<img src="' . $user->getAvatar() . '" width="32" height="32" alt="">',
				'comment' => $user->getDisplayName() . ' kommenterade <a href="' . $episode->getLink() . '">' . $episode->getTitle() . '</a>.'
			);
		};

		$latest_episode_comments = Episode_Comment::orderBy('created_at', 'DESC')
			->limit(2)
			->get();

		foreach ( $latest_episode_comments as $comment )
		{
			$append_latest_comments($comment->user, $comment->episode);
		}

		$this->assign('latest_comments', $latest_comments);

		// Senaste nyheter
		$latest_news_items = News_Item::orderBy('created_at', 'DESC');

		$this->assign('latest_news_items', $latest_news_items->get());

		// Lyssnas just nu
		$listens_right_now = array();

		$episode_listens_right_now = User_Listen::where('is_listening', 'yes')
			->orderBy('created_at', 'DESC')
			->limit(2)
			->groupBy('user_listens.user_id')
			->get();

		foreach ( $episode_listens_right_now as $listen )
		{
			$listens_right_now[] = array
			(
				'text' => $listen->user->getDisplayName() . ' lyssnar på <a href="' . $listen->episode->getLink() . '">' . $listen->episode->getTitle() . '</a>.'
			);
		}

		$this->assign('listens_right_now', $listens_right_now);

		$this->display('home.index');
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
			->take(100);

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

	public function view_podcast($slug)
	{
		$episodes = array();

		try
		{
			$podcast = Podcast::where('slug', '=', $slug)->firstOrFail();

			$episodes = $podcast->episodes;
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			$this->showAlert('Kunde inte hitta podcasten!');

			return Redirect::route('home');
		}

		$this->assign('podcast', $podcast);

		$this->assign('num_episodes', count($episodes));
		$this->assign('episodes', $episodes);

		$this->display('home.view_podcast');
	}

	public function view_episodes($podcast = NULL)
	{
		die('woot');

		$is_filtered = false;

		if ( $podcast !== NULL )
		{
			try
			{
				$podcast = Podcast::where('slug', '=', $podcast)->firstOrFail();

				$episodes = $podcast->episodes;

				$is_filtered = true;
			}
			catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
				$this->showAlert('Kunde inte hitta podcasten!');

				return Redirect::route('avsnitt');
			}
		}
		else
		{
			$episodes = Episode::all()->take(10);
		}

		$this->assign('podcast', $podcast);

		$this->assign('num_episodes', count($episodes));
		$this->assign('episodes', $episodes);

		$this->display('home.view_episodes', ($podcast !== NULL ? $podcast->name : '') . ' - Avsnitt');
	}

	public function view_episode($podcast, $episode)
	{
		try
		{
			$episode = Episode::where('slug', '=', $episode)->firstOrFail();
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

		$this->display('home.view_episode', $episode->title . ' - ' . $episode->podcast->title);
	}

	public function login()
	{
		$this->display('home.login');
	}

	public function register()
	{
		$this->display('home.register');
	}

	public function my_page()
	{
		$this->assign('history', $this->user->get_history());

		$this->display('home.my_page');
	}

    public function view_blogs()
    {
 		// Bloggar
 	  	$blogs = Blog::all();

    	$this->assign('blogs', $blogs);

    	// Senaste blogginläggen
    	$latest_blog_items = Blog_Item::orderBy('created_at', 'DESC')->get();

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

        $this->display('home.view_poddsnack', 'poddsnack');
    }

    public function view_toplist()
    {
        $this->display('home.view_toplist', 'topplista');
    }
}