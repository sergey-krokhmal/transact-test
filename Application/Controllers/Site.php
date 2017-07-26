<?php
namespace Application\Controllers;

use Application\Core\BaseController;
use Application\Entities\Account;

class Site extends BaseController
{
    public function index()
	{
		$account = new Account();
		$account->validToken("asd");
		header("Location: /Site/login");
		header("Location: /Site/index");
	}
	
	public function login()
	{
		$this->loadTemplate('login');
	}
	
	public function logout()
	{
	}
	
	
}