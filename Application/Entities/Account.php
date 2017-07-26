<?php
namespace Application\Entities;

class Account
{
	public function login()
	{
	}
	
	public function validToken(string $token)
	{
		return true;
	}
}