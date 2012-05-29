<?php

namespace JawHare\Controllers;

class Login extends \JawHare\BaseController
{
	protected $parameters = array(
		'_POST' => array(
			'username' => 'string',
			'password' => 'string',
		),
	);
			
	public function action_index()
	{
		if (empty($this->_POST['username']) || empty($this->_POST['password']))
		{
			$this->route('bad_login');
		}

		try
		{
			if ($this->auth->validate($this->_POST['password'], $this->_POST['username']))
			{
				$this->auth->login();
				self::redirect_exit($this->settings->config('base_url'));
			}
			else
				$this->route('bad_login');
		}
		catch (NoUserException $e)
		{
			$this->route('bad_login');
		}
	}

	public function action_logout()
	{
		$this->auth->logout();
		self::redirect_exit($this->settings->config('base_url'));
	}

	protected function action_bad_login()
	{
		$this->load_view()->view('login', array('errors' => array('bad_login'), 'username' => isset($this->_POST['username']) ? $this->_POST['username'] : ''))->show();
	}
}
