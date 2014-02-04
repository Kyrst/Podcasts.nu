<?php
class AjaxController extends BaseController
{
	public function save_listen()
	{
		$result = array
		(
			'error' => ''
		);

		$input = Input::all();

		if ( $this->user !== NULL )
		{
			$user_listen = User_Listen::find($input['episode_id']);

			$time = date('Y-m-d H:i:s');

			if ( $user_listen === NULL )
			{
				$user_listen = new User_Listen();
				$user_listen->episode_id = $input['episode_id'];
				$user_listen->user_id = $this->user->id;
				$user_listen->first_time = $time;
			}

			$user_listen->time = $time;
			$user_listen->is_listening = 'yes';
			$user_listen->save();
		}

		return Response::json($result);
	}

	public function stop_listening()
	{
		$result = array
		(
			'error' => ''
		);

		if ( $this->user !== NULL )
		{
			$input = Input::all();

			try
			{
				$user_listen = User_Listen::where(array('episode_id' => $input['episode_id'], 'user_id' => $this->user->id))->firstOrFail();
				$user_listen->is_listening = 'no';
				$user_listen->save();
			}
			catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
			{
			}
		}

		return Response::json($result);
	}

	public function comment_episode()
	{
		$result = array
		(
			'error' => ''
		);

		$input = Input::all();

		if ( !isset($input['id']) )
		{
			$result['error'] = 'EPISODE_PARAM_MISSING';

			die(json_encode($result));
		}

		$episode = Episode::find($input['id']);

		if ( $episode === NULL )
		{
			$result['error'] = 'EPISODE_NOT_FOUND';

			die(json_encode($result));
		}

		$comment = new Episode_Comment;
		$comment->episode_id = $episode->id;
		$comment->user_id = $this->user->id;
		$comment->comment = trim($input['comment']);
		$comment->save();

		return Response::json($result);
	}

	public function get_episode_comments($id)
	{
		$result = array
		(
			'error' => '',
			'html' => ''
		);

		$episode = Episode::find($id);

		if ( $episode === NULL )
		{
			$result['error'] = 'EPISODE_NOT_FOUND';

			die(json_encode($result));
		}

		$view = View::make('ajax/get_episode_comments')
			->with('num_comments', $episode->comments->count())
			->with('comments', $episode->comments);

		$result['html'] = $view->render();

		return Response::json($result);
	}

	public function slugify()
	{
		$slug = Str::slug(Input::get('text'));

		die($slug);
	}

	public function play()
	{
		$url = Input::get('url');

		$content_length = $this->get_content_length($url);

		$ch = curl_init($url);

		$writefn = function($ch, $chunk)
		{
			static $data = '';
			static $limit = 1024;

			$len = strlen($data) + strlen($chunk);

			if ( $len >= $limit )
			{
				$data .= substr($chunk, 0, $limit-strlen($data));

				echo strlen($data) , ' ', $data;

				return -1;
			}

			$data .= $chunk;

			return strlen($chunk);
		};

		$begin = 0;
		$end = $content_length - 1;

		if ( isset($_SERVER['HTTP_RANGE']) )
		{
			if ( preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches) )
			{
				$begin = intval($matches[1]);

				if ( !empty($matches[2]) )
				{
					$end = intval($matches[2]);
				}

				error_log('yes: ' . $begin . ' - ' . $end);
			}
		}
		else
		{
			error_log('no: ' . $begin . ' - ' . $end);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RANGE, '0-1024');
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_WRITEFUNCTION, $writefn);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$data = curl_exec($ch);

		curl_close($ch);

		if ( $begin > 0 || $end < $content_length )
		{
			header('HTTP/1.1 206 Partial Content');
		}
		else
		{
			header('HTTP/1.1 200 OK');
		}

		//header('Content-Type: audio/mpeg');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Accept-Ranges: bytes');
		header('Content-Length:' . ($end - $begin) + 1);

		if ( isset($_SERVER['HTTP_RANGE']) )
		{
			header('Content-Range: bytes ' . ($begin - $end / $content_length));
		}

		header('Content-Transfer-Encoding: binary' . "\n");
		//header("Last-Modified: $time");

		echo $data;

		//readfile($url);
		exit;
	}

	private function get_content_length($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);

		$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

		return intval($size);
	}

	public function rate_episode()
	{
		$result = array
		(
			'data' => array(),
			'error' => ''
		);

		if ( $this->user !== NULL )
		{
			$input = Input::all();

			$episode_id = $input['episode_id'];

			$episode = Episode::find($episode_id);

			// Check if user already has voted for this
			$result_already_voted = Episode_Vote::where('episode_id', $episode_id)->where('user_id', $this->user->id);

			$already_voted = ($result_already_voted->count() === 1);

			if ( !$already_voted ) // If not voted already
			{
				$episode_vote = new Episode_Vote();
				$episode_vote->episode_id = $episode_id;
				$episode_vote->user_id = $this->user->id;
				$episode_vote->score = $input['score'];
				$episode_vote->save();

				$episode->score = $episode->get_score();
				$episode->save();

				$result['data']['voted_before'] = 'no';
				$result['data']['new_score'] = $episode->score;
			}
			else // If voted already
			{
				$already_voted_row = $result_already_voted->first();
				$already_voted_row->score = $input['score'];
				$already_voted_row->save();

				$result['data']['voted_before'] = 'yes';
				$result['data']['new_score'] = $already_voted_row->score;
			}
		}
		else
		{
			$result['data']['error'] = 'NOT_LOGGED_IN';
		}

		return Response::json($result);
	}

	public function subscribe_podcast()
	{
		$result = array
		(
			'error' => ''
		);

		$input = Input::all();

		$podcast_id = $input['podcast_id'];

		$user_podcast = new User_Podcast();
		$user_podcast->user_id = $this->user->id;
		$user_podcast->podcast_id = $podcast_id;
		$user_podcast->save();

		return Response::json($result);
	}

	public function unsubscribe_podcast()
	{
		$result = array
		(
			'error' => ''
		);

		$input = Input::all();

		$podcast_id = $input['podcast_id'];

		User_Podcast::where('user_id', $this->user->id)->where('podcast_id', $podcast_id)->delete();

		return Response::json($result);
	}

	public function save_listen_position()
	{
		$result = array
		(
			'error' => ''
		);

		$input = Input::all();

		$episode_id = $input['episode_id'];

		try
		{
			$user_listen = User_Listen::where('episode_id', $episode_id)->where('user_id', $this->user->id)->firstOrFail();
			$user_listen->current_position = $input['position'];
			$user_listen->save();
		}
		catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e )
		{
		}

		return Response::json($result);
	}
}