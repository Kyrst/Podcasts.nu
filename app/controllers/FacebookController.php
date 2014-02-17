<?php
class FacebookController extends BaseController
{
	public function login()
	{
		$input = Input::all();

		if ( !isset($input['code']) )
		{
			return Redirect::route('home');
		}

		$code = $input['code'];

		//$convert = file_get_contents('https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=' . Config::get('facebook.FACEBOOK_APP_ID') . '&client_secret=' . Config::get('facebook.FACEBOOK_APP_SECRET_KEY') . '&fb_exchange_token=' . $code);

		$response = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=' . Config::get('facebook.FACEBOOK_APP_ID') . '&redirect_uri=' . Config::get('facebook.FACEBOOK_REDIRECT_URL') . '&client_secret=' . Config::get('facebook.FACEBOOK_APP_SECRET_KEY') . '&code=' . $code);

		$params = NULL;

		parse_str($response, $params);

		$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $params['access_token']));

		// Add user if not exists
		if ( !$this->user_with_facebook_id_exists($user->id) )
		{
			$new_user = new User();
			$new_user->username = $user->username;
			$new_user->slug = Str::slug($user->username);
			$new_user->password = '';
			$new_user->email = '';
			$new_user->first_name = $user->first_name;
			$new_user->last_name = $user->last_name;
			$new_user->city = isset($user->hometown, $user->hometown->name) ? $user->hometown->name : '';
			$new_user->verified = 1;
			$new_user->facebook_id = $user->id;
			$new_user->save();

			Auth::login($new_user);
		}
		else
		{
			$db_user = User::where('facebook_id', $user->id)->firstOrFail();

			Auth::login($db_user);
		}

		return Redirect::route('min-sida');
	}

	private function user_with_facebook_id_exists($facebook_id)
	{
		return (User::where('facebook_id', $facebook_id)->count() === '1');
	}
}