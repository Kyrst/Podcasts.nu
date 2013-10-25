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
   'uses' => 'HomeController@view_toplist.php',
   'as' => 'topplista'
));

//Poddsnack, blir sen /poddsnack & /poddsnack/poddsnack-titel Samma som på Podcasts.nu
Route::get('/poddsnacks', array
(
    'uses' => 'HomeController@view_poddsnacks',
    'as' => 'poddsnacks'
));
Route::get('/poddsnack', array
(
    'uses' => 'HomeController@view_poddsnack',
    'as' => 'poddsnack'
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

	try
	{
		Auth::attempt(array
		(
			'email' => Input::get('email'),
			'password' => Input::get('password')
		), true);
	}
	catch ( Exception $e )
	{
		//$error = $e->getMessage();
		$error = 'E-mailen eller löseordet är fel.';
	}

	if ( $error === '' )
	{
		return Redirect::route('home');
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
	'uses' => 'AdminController@view_blogs',
	'as' => 'admin/blogg'
));

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

// Admin: Avsnitt
Route::get('/admin/episodes', array
(
	'uses' => 'AdminController@view_episodes',
	'as' => 'admin/episodes'
));

// Admin: Avsnitt: Lägg till
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
Route::get('/admin/anvandare', array
(
	'uses' => 'AdminController@view_users',
	'as' => 'admin/anvandare'
));

// AJAX: Save episode listen
Route::post('/save-listen', array
(
	'uses' => 'AjaxController@save_listen'
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