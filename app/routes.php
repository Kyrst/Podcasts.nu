<?php
// Index
Route::get('/', array
(
	'uses' => 'HomeController@index',
	'as' => 'home'
));

// Nyheter
Route::get('/nyheter', array
(
	'uses' => 'HomeController@view_news_items',
	'as' => 'nyheter'
));

// Nyhet
Route::get('/nyheter/{date}/{slug}', array
(
	'uses' => 'HomeController@view_news_item'
 //))->where(array('date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}', 'slug' => '[a-z0-9\-]+'));
))->where(array('date' => '[0-9\-]+', 'slug' => '[a-z0-9\-]+'));

//Bloggar blir sen /blogg & /blogg/bloggnamn
Route::get('/bloggar', array
(
    'uses' => 'HomeController@view_blogs',
    'as' => 'bloggar'
));

// Blogg
Route::get('/bloggar/{slug}', array
(
	'uses' => 'HomeController@view_blog'
))->where(array('slug' => '[a-z0-9\-]+'));

// Blogginläggg
Route::get('/bloggar/{blog_slug}/{blog_item_date}/{blog_item_slug}', array
(
	'uses' => 'HomeController@view_blog_item'
))->where(array('blog_slug' => '[a-z0-9\-]+', 'blog_item_date' => '[0-9]{4}-[0-9]{2}-[0-9]{2}', 'blog_item_slug' => '[a-z0-9\-]+'));

//För varje blogg, kanske Route::get('/blogg/{namn}')
Route::get('/blogg', array
(
    'uses' => 'HomeController@view_blog',
    'as' => 'blogg'
));

// Podcasts
Route::get('/poddar', array
(
	'uses' => 'HomeController@view_podcasts',
	'as' => 'poddar'
));

// Podcasts
Route::get('/poddar/{podcast}', array
(
	'uses' => 'HomeController@view_podcast'
))->where('podcast', '[a-z0-9\-]+');

// Get podcasts (AJAX)
Route::get('/ajax/get-podcasts', array
(
	'uses' => 'HomeController@ajax_get_podcasts',
	'as' => '/ajax/get-podcasts'
));

// Avsnitt
Route::get('/avsnitt', array
(
	'uses' => 'HomeController@view_episodes',
	'as' => 'avsnitt'
));

// Avsnitt (Per Podcast)
Route::get('/avsnitt/{podcast}', array
(
	'uses' => 'HomeController@view_episodes'
))->where(array('podcast' => '[a-z0-9\-]+'));

// Avsnitt: Avsnitt
Route::get('/avsnitt/{podcast}/{episode}', array
(
	'uses' => 'HomeController@view_episode'
))->where(array('podcast' => '[a-z0-9\-]+', 'episode' => '[a-z0-9\-]+'));

//Topplista
Route::get('/topplista', array
(
   'uses' => 'HomeController@view_toplist',
   'as' => 'topplista'
));

Route::get('/poddsnack', array
(
    'uses' => 'HomeController@view_poddsnacks',
    'as' => 'poddsnacks'
));

Route::get('/poddsnack/{slug}', array
(
    'uses' => 'HomeController@view_poddsnack'
));

// Logga in
Route::get('/logga-in', array
(
	'uses' => 'HomeController@login',
	'as' => 'logga-in'
));

// Logga in (POST)
Route::post('logga-in', function()
{
	$error = '';

	$email = trim(Input::get('email'));

	try
	{
		$user = Auth::attempt(array
		(
			'email' => $email,
			'password' => trim(Input::get('password'))
		), true);
	}
	catch ( Exception $e )
	{
		// Find user with email
		try
		{
			$login = Auth::attempt(array
			(
				'email' => $email,
				'password' => 'have_to_reset'
			), true);

			if ( $login === true )
			{
				$user = Auth::user();

				Auth::logout();

				return Redirect::route('set-password')->with('user_id', $user->id);
			}
		}
		catch ( Exception $e )
		{
			$error = 'E-mail eller löseordet är fel.';
		}
	}

	if ( $error === '' )
	{
		return Redirect::route('min-sida');
	}
	else
	{
		return Redirect::route('logga-in')
			->with('log_in_error', $error)
			->withInput();
	}
});

// Logga out
Route::get('logga-ut', array
(
	'as' => 'logga-ut',
	function ()
	{
		Auth::logout();

		return Redirect::home();
	}
))->before('auth');

// Min sida
Route::get('/min-sida', array
(
	'uses' => 'HomeController@my_page',
	'as' => 'min-sida'
));

// Bli medlem
Route::get('/bli-medlem', array
(
	'uses' => 'HomeController@register',
	'as' => 'bli-medlem'
));

// Admin: Blogg
Route::get('/admin/blogg', array
(
	'uses' => 'AdminController@view_blog_items',
	'as' => 'admin/blogg'
));

// Admin: Blogg: Lägg till
Route::get('/admin/blogginlagg/{id?}', array
(
	'uses' => 'AdminController@blog_item',
	'as' => 'admin/blogginlagg'
))->where('id', '[0-9]+');

// Admin: Blogg: Lägg till/Ändra (POST)
Route::post('/admin/blogginlagg/{id?}', array
(
	'uses' => 'AdminController@blog_item'
))->where('id', '[0-9]+');


// Admin: Nyheter
Route::get('/admin/nyheter', array
(
	'uses' => 'AdminController@view_news_items',
	'as' => 'admin/nyheter'
));

// Admin: Nyheter: Lägg till
Route::get('/admin/nyhet/{id?}', array
(
	'uses' => 'AdminController@news_item',
	'as' => 'admin/nyhet'
))->where('id', '[0-9]+');

// Admin: Nyheter: Lägg till/Ändra (POST)
Route::post('/admin/nyhet/{id?}', array
(
	'uses' => 'AdminController@news_item'
))->where('id', '[0-9]+');

// Admin: Poddar
Route::get('/admin/poddar', array
(
	'uses' => 'AdminController@view_podcasts',
	'as' => 'admin/poddar'
));

// Admin: Poddar: Lägg till
Route::get('/admin/podd/{id?}', array
(
	'uses' => 'AdminController@podcast',
	'as' => 'admin/podd'
))->where('id', '[0-9]+');

// Admin: Poddar: Lägg till/Ändra (POST)
Route::post('/admin/podd/{id?}', array
(
	'uses' => 'AdminController@podcast'
))->where('id', '[0-9]+');

// Admin: Poddsnack
Route::get('/admin/poddsnacks', array
(
	'uses' => 'AdminController@view_podtalks',
	'as' => 'admin/poddsnacks'
));

// Admin: Poddsnack
Route::get('/admin/poddsnack', array
(
	'uses' => 'AdminController@podtalk',
	'as' => 'admin/poddsnack'
));

// Admin: Poddsnack: Lägg till/Ändra
Route::get('/admin/poddsnack/{id?}', array
(
	'uses' => 'AdminController@podtalk'
))->where('id', '[0-9]+');

// Admin: Poddsnack: Lägg till/Ändra (POST)
Route::post('/admin/poddsnack/{id?}', array
(
	'uses' => 'AdminController@podtalk'
))->where('id', '[0-9]+');

// Admin: Avsnitt
Route::get('/admin/episodes', array
(
	'uses' => 'AdminController@view_episodes',
	'as' => 'admin/episodes'
));

// Admin: Avsnitt: Lägg till/Ändra
Route::get('/admin/episode/{id?}', array
(
	'uses' => 'AdminController@episode',
	'as' => 'admin/episode'
))->where('id', '[0-9]+');

// Admin: Avsnitt: Lägg till/Ändra (POST)
Route::post('/admin/episode/{id?}', array
(
	'uses' => 'AdminController@episode'
))->where('id', '[0-9]+');

// Admin: Användare
Route::get('/admin/users', array
(
	'uses' => 'AdminController@view_users',
	'as' => 'admin/users'
));

// Admin: Användare: Ändra
Route::get('/admin/user/{id?}', array
(
	'uses' => 'AdminController@user'
))->where('id', '[0-9]+');

// Admin: Användare: Ändra (POST)
Route::post('/admin/user/{id?}', array
(
	'uses' => 'AdminController@user'
))->where('id', '[0-9]+');

// AJAX: Save episode listen
Route::post('/save-listen', array
(
	'uses' => 'AjaxController@save_listen'
));

Route::post('/save-listen-position', array
(
	'uses' => 'AjaxController@save_listen_position'
));

// AJAX: Stop episode listen
Route::post('/stop-listening', array
(
	'uses' => 'AjaxController@stop_listening'
));

// AJAX: Subscribe to podcast
Route::post('/subscribe-podcast', array
(
	'uses' => 'AjaxController@subscribe_podcast'
));

// AJAX: Unsubscribe podcast
Route::post('/unsubscribe-podcast', array
(
	'uses' => 'AjaxController@unsubscribe_podcast'
));

// AJAX: Get episode comments
Route::get('/ajax/get-episode-comments/{id}', array
(
	'uses' => 'AjaxController@get_episode_comments'
))->where('id', '[0-9]+');

// AJAX: Comment episode
Route::post('/ajax/comment-episode', array
(
	'uses' => 'AjaxController@comment_episode'
));

// AJAX: Slugify
Route::get('/slugify', array
(
	'uses' => 'AjaxController@slugify'
));

// AJAX: Play
Route::get('/play', array
(
	'uses' => 'AjaxController@play'
));

Route::post('/admin/upload_news_item_image', array
(
	'uses' => 'AdminController@upload_news_item_image'
));

Route::get('/bild/{type}/{id}/{size}', array
(
	'uses' => 'ImageController@init'
))->where(array('type' => '(poddsnack)', 'id' => '\d+', 'size' => '.*'));

Route::post('/rate-episode', array
(
	'uses' => 'AjaxController@rate_episode'
));

// Import
Route::get('/import-old', array
(
	'uses' => 'ImportController@import',
	'as' => 'import-old'
));

Event::listen('illuminate.query', function($query)
{
	if ( app()->environment() === 'local' && Input::get('profiler') )
	{
		error_log($query);
	}
});

Route::get('/scripts/download-podcasts/{podcast_id?}', array
(
	'uses' => 'ScriptController@download_podcasts',
	'as' => 'scripts/download-podcasts'
))->where('podcast_id', '\d+');

Route::get('/set-password', array
(
	'uses' => 'HomeController@set_password',
	'as' => 'set-password'
));

Route::post('/set-password', array
(
	'uses' => 'HomeController@set_password'
));

Route::post('/save-episode-duration', array
(
	'uses' => 'AjaxController@save_episode_duration'
));

Route::get('/sign-up', array
(
	'uses' => 'HomeController@sign_up',
	'as' => 'sign-up'
));

Route::post('/sign-up', array
(
	'uses' => 'HomeController@sign_up'
));

Route::post('/user-exists', array
(
	'uses' => 'AjaxController@user_exists'
));

Route::get('/facebook-login/{code}', array
(
	'uses' => 'FacebookController@login'
))->where('code', '*');