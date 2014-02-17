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
die(var_dump($response));
		$params = NULL;

		parse_str($response, $params);

		die(print_r('<pre>' . print_r($params, TRUE) . '</pre>'));

		$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $params['access_token']));

		die(print_r('<pre>' . print_r($user, TRUE) . '</pre>'));

		// Add user if not exists
		if ( !$this->facebookIdExists($user->id) )
		{
			$this->register($user->username, '', '', $user->first_name, $user->last_name, $user->id);
		}

		$this->login($user->username, '');
	}
}