<?php

namespace BOS\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BOSUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BOSUserRepository extends EntityRepository
{
	public function usernameExists($username){
		if($this->findOneBy(array("username" => $username))){
			return true;
		}
		return false;
	}
	
	public function emailExists($email){
		if($this->findOneBy(array("email" => $email))){
			return true;
		}
		return false;		
	}
	
	public function login($username, $password){
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user = $this->findOneBy(array("username" => $username));
		if(password_verify($password, $user->getPassword())){
			return $user;
		}else{
			throw new \Exception("User credentials are incorrect");
		}
	}
	
}