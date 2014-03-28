<?php
use Toddish\Verify\Models\User as VerifyUser;
use Intervention\Image\Image;

class User extends VerifyUser
{
	protected $table = 'users';

	protected $softDelete = true;

	protected $hidden = array('password');

	public static $avatar_sizes = array
	(
		'installningar' => array
		(
			'width' => NULL,
			'height' => 124,
			'proportional' => true,
			'canvas' => array
			(
				'width' => 124,
				'height' => 124
			)
		),
		'index_kommentar' => array
		(
			'width' => NULL,
			'height' => 32,
			'proportional' => true
		),
		'avsnitt_kommentar' => array
		(
			'width' => NULL,
			'height' => 80,
			'proportional' => true,
			'canvas' => array
			(
				'width' => 80,
				'height' => 80
			)
		)
	);

	public function episode_listens()
	{
		return $this->belongsToMany('Episode', 'user_listens');
	}

	public function is_admin()
	{
		return $this->is('Admin');
	}

	public function getDisplayName()
	{
		return !empty($this->first_name) && !empty($this->last_name) ? $this->first_name . ' ' . $this->last_name : $this->email;
	}

	public function blog()
	{
		return $this->belongsTo('Blog');
	}

	public function podcasts()
	{
		return $this->belongsToMany('Podcast', 'user_podcasts');
	}

	public function get_history($num = NULL)
	{
		$history = array();

		$user = Auth::user();

		$history[] = array
		(
			'message' => 'Registrerade sig som medlem på Podcasts.nu.',
			'timestamp' => strtotime($user->created_at)
		);

		// Hämta lyssningar
		$user_listens = User_Listen::where('user_id', $this->id);

		if ( $num !== NULL )
		{
			$user_listens = $user_listens->take($num);
		}

		foreach ( $user_listens->orderBy('created_at', 'DESC')->get() as $user_listen )
		{
			$history[] = array
			(
				'message' => 'Lyssnade på <a href="' . $user_listen->episode->getLink('poddar') . '">' . $user_listen->episode->getTitle() . '</a>.',
				'timestamp' => strtotime($user_listen->created_at)
			);
		}

		usort($history, array($this, 'sort_history'));

		return $history;
	}

	private function sort_history(array $a, array $b)
	{
		return $b['timestamp'] - $a['timestamp'];
	}

	public function get_age()
	{
		$now = new DateTime();
		$birthday = new DateTime($this->birthdate);
		$interval = $now->diff($birthday);

		return $interval->format('%y');
	}

	public function get_profile_page()
	{
		return URL::route('home');
	}

	public function get_latest_listened_episode($podcast_id)
	{
		try
		{
			$latest_listened_episode = $this->episode_listens()->where('episodes.podcast_id', $podcast_id)->orderBy('user_listens.created_at', 'DESC')->firstOrFail();
		} catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
			return NULL;
		}

		return $latest_listened_episode;
	}

	public function get_episode_listens()
	{
		$episode_listens = User_Listen::where('user_id', $this->id)->where('done', 'no')->get();

		return $episode_listens;
	}

	public function have_avatar()
	{
		return ($this->avatar === 'yes');
	}

	public static function get_avatar_dir($user_id, $create_if_not_exists = false)
	{
		$dir = base_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR;

		// Create dir if not exists
		if ( !file_exists($dir) )
		{
			mkdir($dir, 0775, true);
		}

		return $dir;
	}

	public static function get_avatar_filename($user_id, $with_extension = true)
	{
		return 'original' . ($with_extension ? '.jpg' : '');
	}

	public static function get_avatar_path($user_id, $create_dir_if_not_exists = true)
	{
		$dir = self::get_avatar_dir($user_id, $create_dir_if_not_exists);
		$filename = self::get_avatar_filename($user_id);
		$path = $dir . $filename;

		return $path;
	}

	public static function upload_avatar($image, $user_id)
	{
		$path = self::get_avatar_path($user_id, true);

		$img = Image::make($image->getRealPath())
			->resize(800, 600, false, false)
			->save($path);

		return $img;
	}

	public function get_avatar_image($size_name, $id = NULL, $rounded = true)
	{
		if ( !array_key_exists($size_name, self::$avatar_sizes) )
		{
			throw new Exception('Size "' . $size_name . '" does not exist.');
		}

		$size = self::$avatar_sizes[$size_name];

		//$img_src = URL::to('profile-picture/' . $this->id . '/' . ($size['width'] !== NULL ? $size['width'] : '0') . '/' . ($size['height'] !== NULL ? $size['height'] : '0') . ($size['proportional'] === false ? '/1' : '')) . '"';
		$img_src = URL::to('avatar/' . $this->id . '/' . $size_name);

		$img_width = isset($size['canvas']) && $size['canvas']['width'] !== NULL ? $size['canvas']['width'] : ($size['width'] !== NULL ? ' width="' . $size['width'] . '"' : '');
		$img_height = isset($size['canvas']) && $size['canvas']['height'] !== NULL ? $size['canvas']['height'] : ($size['height'] !== NULL ? ' height="' . $size['height'] . '"' : '');
		$img_id = ($id !== NULL ? ' id="' . $id . '"' : '');

		return '<img src="' . $img_src . '"' . $img_id . $img_width . $img_height . ' alt="' . $this->getDisplayName() . '"' . ($rounded ? ' class="img-rounded"' : '') . '>';
	}

	public static function get_avatar_size($size_name)
	{
		if ( !array_key_exists($size_name, self::$avatar_sizes) )
		{
			throw new Exception('Size "' . $size_name . '" does not exist.');
		}

		return self::$avatar_sizes[$size_name];
	}

	public function get_episode_status($episode_id)
	{
		$result = '';

		try
		{
			$user_listen = User_Listen::where('user_id', $this->id)
				->where('episode_id', $episode_id)
				->firstOrFail();

			if ( $user_listen->done === 'yes' )
			{
				$result = 'Lyssnad';
			}
			else
			{
				$result = 'Påbörjad';
			}
		}
		catch ( \Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
		}

		return $result;
	}
}