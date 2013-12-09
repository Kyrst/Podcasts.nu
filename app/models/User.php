<?php
use Toddish\Verify\Models\User as VerifyUser;

class User extends VerifyUser
{
	protected $table = 'users';

	protected $hidden = array('password');

	public function getAvatar()
	{
		return URL::to('images/avatars/default.png', array(), false);
	}

	public function isAdmin()
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
}