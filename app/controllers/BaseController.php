<?php
class BaseController extends Controller
{
	const DEFAULT_PAGE_TITLE = 'Vi samlar olika Svenska Podcasts';
	const PAGE_TITLE_APPENDIX = 'Podcasts.nu';
	const PAGE_TITLE_SEPARATOR = '-';

	public $layout = 'layouts.front';

	private $debug = FALSE;

	private $data = array
	(
		'layout' => array(),
		'content' => array(),
		'js' => array()
	);

	private $assets = array
	(
		'css' => array(),
		'js' => array()
	);

	private $libs = array
	(
		'jquery-ui' => array
		(
			'css' => 'libs/jquery-ui/themes/base/jquery-ui.css',
			'js' => 'libs/jquery-ui/ui/jquery-ui.js'
		),
		'bootstrap' => array
		(
			'css' => 'libs/bootstrap/css/bootstrap.css',
			'js' => 'libs/bootstrap/js/bootstrap.js'
		),
		'bootbox' => array
		(
			'js' => 'libs/bootbox/bootbox.js'
		),
		'ckeditor' => array
		(
			'js' => 'libs/ckeditor/ckeditor.js'
		),
		'soundmanager2' => array
		(
			'js' => 'libs/soundmanager2/soundmanager2-jsmin.js'
		),
		'jplayer' => array
		(
			'css' => 'css/player.css',
			'js' => array('libs/jplayer/jquery.jplayer/jquery.jplayer.js', 'libs/jplayer/add-on/jplayer.playlist.js')
		),
		'raty' => array
		(
			'js' => 'libs/raty/jquery.raty.min.js'
		),
		'jgrowl' => array
		(
			'css' => 'libs/jgrowl/jquery.jgrowl.css',
			'js' => 'libs/jgrowl/jquery.jgrowl.min.js'
		),
		'datepicker' => array
		(
			'css' => 'libs/datepicker/css/datepicker.css',
			'js' => 'libs/datepicker/js/bootstrap-datepicker.js'
		)
	);

	private $loaded_libs = array
	(
		'pre_loaded' => array(),
		'user_loaded' => array()
	);

	protected $user = NULL;

	private $from_controller;
	protected $current_route_action = '';

	public function __construct()
	{
		$this->user = Auth::user();
	}

	// Initialize
	public function setupLayout($from_no_controller = false)
	{
		$this->debug = App::environment() !== 'live' ? true : false;

		if ( !is_null($this->layout) )
		{
			$this->from_controller = !$from_no_controller;
			$current_route = Route::getCurrentRoute();
			$this->current_route_action = $current_route !== NULL ? $current_route->getAction() : NULL;

			//App::setLocale('es');
			$this->assign('user', $this->user, array('layout', 'content'));
			$this->assign('user_id', $this->user !== NULL ? $this->user->id : 0, 'js');

			$this->layout = View::make($this->layout);

			// Load default assets
			// Normalize
			$this->addCSS('css/normalize.css');

			// Bootstrap
			$this->loadLib('bootstrap', true);

			// Bootbox
			$this->loadLib('bootbox', true);

			// Soundmanager2
			//$this->loadLib('soundmanager2', true);

			// jPlayer
			$this->loadLib('jplayer', true);

			// Raty
			$this->loadLib('raty', true);

			$this->loadLib('jgrowl', true);

			// Add current route to views
			if ( $current_route !== NULL )
				$this->assign('current_route', $current_route->getPath(), array('layout', 'content'));

			// Alert
			if ( Session::has('bootbox_alert') )
			{
				$bootbox_alert = Session::get('bootbox_alert');

				Session::remove('bootbox_alert');

				$this->assign('bootbox_alert', $bootbox_alert, array('layout'));
			}

			// Playing cookie
			if ( isset($_COOKIE['playing']) )
			{
				$this->assign('playing_cookie_object', json_decode($_COOKIE['playing'], TRUE), array('js', 'layout'));
			}

			// Player open?
			if ( isset($_COOKIE['player_open']) )
			{
				$this->assign('player_open', $_COOKIE['player_open'] === '"yes"' ? true : false, array('js', 'layout'));
			}

			// Load jQuery
			$this->assign('jquery_script', View::make('partials/layouts/jquery_script'), array('layout'));

			$this->assign('BASE_URL', URL::route('home', array(), false), 'js');
			$this->assign('DEBUG', $this->debug, array('layout', 'content', 'js'));

			// Check for player state
			//$player_state = isset($_COOKIE['player_state']) ? str_replace('"', '', $_COOKIE['player_state']) : 'closed';

			//$this->assign('player_state', $player_state, array('layout'));
		}
	}

	protected function assign($key, $value, $section = 'content')
	{
		if ( is_array($section) )
		{
			$types = (array)$section;

			foreach ( $types as $section )
			{
				if ( isset($this->data[$section][$key]) )
					throw new Exception('Var "' . $key . '" already assiged.');

				$this->data[$section][$key] = $value;
			}
		}
		else
		{
			if ( isset($this->data[$section][$key]) )
				throw new Exception('Var "' . $key . '" already assiged.');

			$this->data[$section][$key] = $value;
		}
	}

	public function addCSS($css)
	{
		if ( in_array($css, $this->assets['css']) )
			throw new Exception('Stylesheet "' . $css . '" already added.');

		$this->assets['css'][] = array
		(
			'file' => $css
		);
	}

	public function addJS($js)
	{
		if ( in_array($js, $this->assets['js']) )
			throw new Exception('JavaScript "' . $js . '" already added.');

		$this->assets['js'][] = $js;
	}

	public function loadLib($lib_name, $pre_loaded = false)
	{
		if ( !isset($this->libs[$lib_name]) )
			throw new Exception('Library "' . $lib_name . '" does not exist.');

		$this->loaded_libs[$pre_loaded ? 'pre_loaded' : 'user_loaded'][] = $lib_name;
	}

	public function loadMidLib($lib_name)
	{ // FIX THIS LATER
		if ( !isset($this->libs[$lib_name]) )
			throw new Exception('Library "' . $lib_name . '" does not exist.');

		$this->loaded_libs['mid_loaded'][] = $lib_name;
	}

	protected function showAlert($message)
	{
		Session::put('bootbox_alert', $message);
	}

	public function display($route, $page_title = '', $page_title_appendix = true)
	{
		$this->layout->page_title = ($page_title !== '') ? $page_title . ($page_title_appendix ? ' ' . self::PAGE_TITLE_SEPARATOR . ' ' . self::PAGE_TITLE_APPENDIX : '') : self::DEFAULT_PAGE_TITLE;

		foreach ( $this->loaded_libs['pre_loaded'] as $lib_name )
		{
			$lib = $this->libs[$lib_name];

			if ( isset($lib['css']) )
				if ( is_array($lib['css']) )
					foreach ( $lib['css'] as $css_file )
						$this->addCSS($css_file);
				else
					$this->addCSS($lib['css']);

			if ( isset($lib['js']) )
				if ( is_array($lib['js']) )
					foreach ( $lib['js'] as $js_file )
						$this->addJS($js_file);
				else
					$this->addJS($lib['js']);
		}

		$this->addJS('js/jquery.cookie.js');
		$this->addJS('js/base.js');

		foreach ( $this->loaded_libs['user_loaded'] as $lib_name )
		{
			$lib = $this->libs[$lib_name];

			if ( isset($lib['css']) )
				if ( is_array($lib['css']) )
					foreach ( $lib['css'] as $css_file )
						$this->addCSS($css_file);
				else
					$this->addCSS($lib['css']);

			if ( isset($lib['js']) )
				if ( is_array($lib['js']) )
					foreach ( $lib['js'] as $js_file )
						$this->addJS($js_file);
				else
					$this->addJS($lib['js']);
		}

		$this->addCSS('css/global.css');

		// Auto load layout
		// CSS
		$public_path = public_path();
		$public_path = !empty($public_path) ? $public_path . '/' : '';

		$css_layout_filename = 'css/' . str_replace('.', '/', $this->layout->getName()) . '.css';
		$css_layout_path = $public_path . $css_layout_filename;

		if ( file_exists($css_layout_path) )
			$this->addCSS($css_layout_filename, '', false);

		// JS
		$js_layout_filename = 'js/' . str_replace('.', '/', $this->layout->getName()) . '.js';
		$js_layout_path = $public_path . $js_layout_filename;

		if ( file_exists($js_layout_path) )
			$this->addJS($js_layout_filename);

		// Load based of /layout
		if ( $this->from_controller )
		{
			$_route = $this->current_route_action;

			$_route = strtolower(str_replace('Controller', '', $_route));
			list($controller, $action) = explode('@', $_route);

			$css_short_auto_path = 'css/' . $controller . '/' . $action . '.css';
			$css_auto_path = $public_path . $css_short_auto_path;

			if ( file_exists($css_auto_path) )
				$this->addCSS($css_short_auto_path);

			$js_short_auto_path = 'js/' . $controller . '/' . $action . '.js';
			$js_auto_path = $public_path . $js_short_auto_path;

			if ( file_exists($js_auto_path) )
				$this->addJS($js_short_auto_path);
		}

		$this->layout->assets = $this->assets;

		$this->layout->js_vars = $this->data['js'];

		foreach ( $this->data['layout'] as $key => $value )
		{
			$this->layout->$key = $value;
		}

		$content_data = array_merge
		(
			$this->data['content'],
			array('js_vars' => $this->data['js'])
		);

		return $this->layout
			->nest('content', $route, $content_data);
	}
}