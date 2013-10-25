<?php
class AjaxController extends BaseController
{
	public function save_listen()
	{
		die('save_listen');
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
}