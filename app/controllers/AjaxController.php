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

		$episode_listen = Episode_Listen::find($input['episode_id']);

		$time = date('Y-m-d H:i:s');

		if ( $episode_listen === NULL )
		{
			$episode_listen = new Episode_Listen();
			$episode_listen->episode_id = $input['episode_id'];
			$episode_listen->user_id = $this->user->id;
		}

		$episode_listen->first_time = $time;
		$episode_listen->time = $time;
		$episode_listen->save();

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

		die('-->'.strlen(file_get_contents($url)));

		header('Content-type: audio/mpeg');

		header('Content-Length: '. filesize($path)); // provide file size

		header("Expires: -1");

		header("Cache-Control: no-store, no-cache, must-revalidate");

		header("Cache-Control: post-check=0, pre-check=0", false);

		readfile($path);
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
}