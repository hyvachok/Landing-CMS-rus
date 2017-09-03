<?php

/**
 * Landing CMS
 *
 * A simple CMS for Landing Pages.
 *
 * @package    Landing CMS
 * @author     Ilia Chernykh <landingcms@yahoo.com>
 * @copyright  2017, Landing CMS (https://github.com/Elias-Black/Landing-CMS)
 * @license    https://opensource.org/licenses/LGPL-2.1  LGPL-2.1
 * @version    Release: 0.0.4
 * @link       https://github.com/Elias-Black/Landing-CMS
 */

defined('CORE') OR die('403');

/**
* The Class for working with User and Session
*/

class User
{

	const HASHED_PASSWORD_LENGTH = 40;
	const AUTH_EXPIRE = 31536000;



	/* PUBLIC API */

	public static function auth()
	{

		$db_pwd = DB::getPassword();

		if($db_pwd['error'] === false)
		{
			$db_pwd = $db_pwd['db_password'];
		}
		else
		{

			if( $_SERVER['REQUEST_URI'] != Utils::getLink('cms/login/') )
				Utils::redirect('cms/login/');

			return $db_pwd;

		}

		if( empty($db_pwd) )
			return self::createPassword();

		$usr_cookie_pwd = self::getLoginCookie();

		$sys_cookie_pwd = self::getPasswordForCookie();

		$post_pwd = isset($_POST['password']) ? $_POST['password'] : false;

		$entered_pwd = self::preparePasswordForDb($post_pwd);

		if( $entered_pwd == $db_pwd || $usr_cookie_pwd == $sys_cookie_pwd )
		{

			define('IS_LOGIN', true);

			if( $usr_cookie_pwd != $sys_cookie_pwd )
				self::login();

			return;

		}

		if( $_SERVER['REQUEST_URI'] != Utils::getLink('cms/login/') )
			Utils::redirect('cms/login/');

		if( $entered_pwd && $entered_pwd != $db_pwd )
			return array('error_message' => 'Invalid password.');

	}

	public static function updatePassword()
	{

		$result = array();

		$pwd_is_valid = self::validateNewPassword();

		if( $pwd_is_valid['error'] === false )
		{

			$prepared_pwd = self::preparePasswordForDb($_POST['pwd1'], true);

			$updated = DB::updatePassword($prepared_pwd);

			if($updated['error'] == false)
			{

				self::setLoginCookie();

				$result['error'] = false;
				$result['success_message'] = 'Password successfully saved.';

			}
			else
			{
				$result = $updated;
			}

		}
		else
		{
			$result = $pwd_is_valid;
		}

		return $result;

	}

	public static function logout()
	{

		unset($_COOKIE['login']);

		setcookie('login','',time()-1, '/');

		Utils::redirect();

	}



	/* PRIVATE API */

	private static function getPasswordForCookie()
	{

		$db_pwd = DB::getPassword();

		if($db_pwd['error'] === false)
		{
			$db_pwd = $db_pwd['db_password'];
		}
		else
		{

			if( $_SERVER['REQUEST_URI'] != Utils::getLink('cms/login/') )
				Utils::redirect('cms/login/');

		}

		return md5($db_pwd);

	}

	private static function setLoginCookie()
	{
		setcookie('login', self::getPasswordForCookie(), time() + self::AUTH_EXPIRE, '/');
	}

	private static function getLoginCookie()
	{
		return isset($_COOKIE['login']) ? $_COOKIE['login'] : false;
	}

	private static function preparePasswordForDb($password, $is_new = false)
	{

		if(!$password)
			return false;

		if(!$is_new)
		{
			$salt = self::getSalt();
		}
		else
		{
			$salt = Utils::getRandomString();
		}

		$password = sha1($password . $salt);

		$result = $password . $salt;

		return $result;

	}

	private static function validateNewPassword()
	{

		$result = array();

		if( !isset($_POST['pwd1']) || !isset($_POST['pwd2']) )
		{
			$result['error'] = true;
			$result['error_message'] = 'Invalid data.';

			return $result;
		}

		if( empty($_POST['pwd1']) || empty($_POST['pwd1']) )
		{
			$result['error'] = true;
			$result['error_message'] = 'All fields are required.';

			return $result;
		}

		if( $_POST['pwd1'] !== $_POST['pwd2'] )
		{
			$result['error'] = true;
			$result['error_message'] = 'Password and Confirm Password doesn\'t match.';

			return $result;
		}

		$result['error'] = false;

		return $result;

	}

	private static function getSalt()
	{

		$db_pwd = DB::getPassword();

		if($db_pwd['error'] === false)
		{
			$db_pwd = $db_pwd['db_password'];
		}
		else
		{

			if( $_SERVER['REQUEST_URI'] != Utils::getLink('cms/login/') )
				Utils::redirect('cms/login/');

		}

		return substr($db_pwd, self::HASHED_PASSWORD_LENGTH);

	}

	private static function createPassword()
	{

		$result = array();

		if( $_SERVER['REQUEST_URI'] != Utils::getLink('cms/password/') )
		{
			Utils::redirect('cms/password/');
		}

		if( !empty($_POST) )
		{
			$pwd_updated = self::updatePassword();

			if( $pwd_updated['error'] === false )
			{
				Utils::redirect('cms/');
			}
			else
			{
				$result = $pwd_updated;
			}

		}
		else
		{
			$result['error'] = true;
			$result['error_message'] = 'Create password.';
		}

		return $result;

	}

	private static function login()
	{

		self::setLoginCookie();

		Utils::redirect('cms/');

	}

}
