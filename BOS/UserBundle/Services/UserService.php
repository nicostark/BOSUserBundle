<?php
namespace BOS\UserBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use BOS\UserBundle\Entity\BOSUser;
use BOS\UserBundle\Entity\BOSUserRepository;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\AnnotationReader;

class UserService
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	/**
	 * @var EntityManager 
	 */
	private $em;
	/**
	 * @var BOSUserRepository
	 */
	private $bos;
	
	public function __construct(ContainerInterface $container){
		$this->container = $container;
		$this->em = $this->container->get('doctrine')->getEntityManager();
		$this->bos = $this->em->getRepository("BOSUserBundle:BOSUser");
	}
	
	public function onKernelController(FilterControllerEvent $event){
		$request = $event->getRequest();
		$method = $request->attributes->get('_controller');
		$reflectionMethod = new \ReflectionMethod($method);
		$reader = new AnnotationReader();
		$data = $reader->getMethodAnnotation($reflectionMethod, 'BOS\\UserBundle\\Annotations\\BOSUserFilter');
		if($data){
			if($data->loginRequired){
				if(!$this->isLoggedIn()){
					$routeName = null;
					try{
						$routeName = $this->container->getParameter('bos_login_name');
					}catch(\Exception $e){
						die("BOSUser: 'bos_login_name' is needed in your parameters.yml");
					}
					$url = $this->container->get('router')->generate($routeName);
					$event->setController(function() use ($url) {
						return new RedirectResponse($url);
					});
				}
			}
		}
	}
	
	public function create($username, $password, $email = ""){
		if(!$this->isValid($username)){
			throw new \Exception("The username is mandatory");
		}
		if(!$this->isValid($password)){
			throw new \Exception("The password is mandatory");
		}
		$user = $this->bos->findOneBy(array("username" => $username));
		if($user){
			throw new \Exception("This username is already taken");
		}
		if($this->isValid($email)){
			$user = $this->bos->findOneBy(array("email" => $email));
			if($user){
				throw new \Exception("This email is already taken");
			}
		}
		$user = new BOSUser();
		$user->setUsername($username);
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user->setPassword($hash);
		if($email!=""){
			$user->setEmail($email);
		}
		$this->em->persist($user);
		$this->em->flush();
		return $user->getId();
	}
	
	public function usernameExists($username){
		return $this->bos->usernameExists($username);
	}
	
	public function emailExists($email){
		return $this->bos->emailExists($email);
	}
	
	public function login($username, $password){
		if($this->isLoggedIn()){
			throw new \Exception("The user is already logged in");
		}
		$user = $this->bos->login($username, $password); 
		if($user){
			$session = $this->getSession();
			$session->set('bos_user', $user);
			return true;
		}else{
			return false;
		}
	}
	
	public function getUser(){
		if(!$this->isLoggedIn()){
			throw new \Exception("The user data is not available");
		}
		$session = $this->getSession();
		return $session->get('bos_user');
	}
	
	public function logout(){
		$session = $this->getSession();
		$session->set('bos_user', null);		
	}
	
	public function isLoggedIn(){
		$session = $this->getSession();
		if(!$session->get('bos_user')){
			return false;
		}
		return true;
	}
	
	private function getSession(){
		$session = null;
		if($this->container->get('session')->isStarted()){
			$session = $this->container->get('session');
		}else{
			$session = new Session();
			$session->start();
		}
		return $session;
	}
	
	private function isValid($param){
		if(!$param||$param==""){
			return false;
		}
		return true;
	}
	
}