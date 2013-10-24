<?php
use Intervention\Image\Image;

class HomeController extends BaseController
{
	public function index()
	{
		$latest_news_items = News_Item::orderBy('created_at', 'DESC');

		$this->assign('latest_news_items', $latest_news_items->get());

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
			$episodes = Episode::all();
		}

		$this->assign('podcast', $podcast);

		$this->assign('num_episodes', count($episodes));
		$this->assign('episodes', $episodes);

		$this->display('home.view_episodes', 'Avsnitt');
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

		$this->display('home.view_episode', $episode->title . ' - Filip Kongo');
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
		$this->display('home.my_page');
	}
    public function view_blogs()
    {
        $this->display('home.view_blogs', 'Bloggar');
    }
}