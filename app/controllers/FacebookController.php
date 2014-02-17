<?php
class FacebookController extends BaseController
{
	public function login($code)
	{
		$response = file_get_contents('https://graph.facebook.com/oauth/access_token?client_id=' . Config::get('facebook.FACEBOOK_APP_ID') . '&redirect_uri=' . Config::get('facebook.FACEBOOK_REDIRECT_URL') . '&client_secret=' . Config::get('facebook.FACEBOOK_APP_SECRET_KEY') . '&code=' . $code);
		die(var_dump($code));
die(var_dump($response));
		$params = NULL;

		parse_str($response, $params);

		$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $params['access_token']));

		// Add user if not exists
		if ( !$this->facebookIdExists($user->id) )
		{
			$this->register($user->username, '', '', $user->first_name, $user->last_name, $user->id);
		}

		$this->login($user->username, '');
	}
}