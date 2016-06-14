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
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use BOS\UserBundle\Entity\Role;
use BOS\UserBundle\Entity\Permission;
use BOS\UserBundle\Entity\RolePermission;
use BOS\UserBundle\Entity\System;
use BOS\UserBundle\Entity\UserRole;


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
	public $bos;
	/**
	 * @var string
	 */
	public $entityClass = "";
        
        /**
         * @var System
         */
        public $system = null;
	
	public function __construct(ContainerInterface $container){
		
                $this->container = $container;
		
                try{
                    $this->em = $this->container->get('doctrine')->getManager('bos');
		}catch(\Exception $e){
			 
		}
		if(!$this->em){
                    $this->em = $this->container->get('doctrine')->getEntityManager();
		}
		$custom = null;
		if(!$this->container->getParameter('bos_login_name')){
                    die("BOSUser needs the 'bos_login_name' parameter defined in config.yml. Please refer to the documentation.");
		}
		if(!$this->container->getParameter('bos_default_behaviour')){
                    die("BOSUser needs the 'bos_default_behaviour' parameter defined in config.yml. Please refer to the documentation.");
		}
		$custom = $this->container->getParameter('bos_user_entity');
		
                //chequeo el sistema en el que estoy y sus permisos
                //sistema
                //if(!$containerSystem){
                 //   die("BOSUser needs the 'bos_login_name' parameter defined in config.yml. Please refer to the documentation.");
               // }
                //permisos
                //die($containerSystem."");
                
                
                if($custom){
			$t = explode(":", $custom);
			$fBundleName = $t[0];
			$fEntityName = $t[1];
			$foundName = "";
			$foundDir = "";
			$bundles = $this->container->get('kernel')->getBundles();
			$bundleName = '';
			foreach($bundles as $type=>$bundle){
				$cBundle = new \ReflectionClass($bundle);
				$cName= $cBundle->getName();
				$temp = explode("\\", $cName);
				$bundleName = trim($temp[count($temp) - 1]);
				$bundleDir = "";
				for($i = 0; $i < count($temp) - 1; $i++){
					if($temp[$i]!="\\"){
						$bundleDir = $bundleDir . $temp[$i] . "\\";
					}
				}
				if($bundleName==$fBundleName){
					$foundName = $bundleName;
					$foundDir = $bundleDir;	
				}
			}
			if($foundName==""){
				die("BOSUser: Couldn't find the bundle '" . $fBundleName . "'. Check your parameters and try again.");
			}
			$this->entityClass = $foundDir . "Entity\\" . $fEntityName;
			try{
				$this->bos = $this->em->getRepository($custom);
			}catch(\Exception $e){
				die("BOSUser: " . $e->getMessage());
			}
		}else{
			$this->entityClass = "";
			$this->bos = $this->em->getRepository("BOSUserBundle:BOSUser");
		}
		if($this->isLoggedIn()){
			//Keep the user data updated
			$session = $this->getSession();
			$username = $this->getUser()->getUsername();
			$user = $this->bos->findOneBy(array("username" => $username));
			$session->set('bos_user', $user);
		}
                
                $containerSystem = $this->container->getParameter('bos_system');
                $containerRoles =  $this->container->getParameter('roles');
                
                $mSystem = $this->container->getParameter('bos_system');
                if(!isset($mSystem)||!$mSystem||$mSystem==""){
                    die("You must set a system name to use BOSUser.");
                }
                
                $actualSystem = $this->em->getRepository('BOSUserBundle:System')->findOneBy(array("name"=>$mSystem));
                
                $this->system = $actualSystem;
                
                /* If the system doesnt exist, we create it */

                if(!$actualSystem){
                    $actualSystem = new \BOS\UserBundle\Entity\System();
                    $actualSystem->setName($containerSystem);
                    $this->em->persist($actualSystem);
                    $this->em->flush();
                }
                
                //CREACION DE PARAMETROS DE SISTEMAS
                
                $rol_temp = NULL;
                                
                /* Revisamos para borrar los que quede de mas en la base */
                $manager = $this->em;
                $roles = $manager->getRepository("BOSUserBundle:Role")->findBy(array("system" => $actualSystem));
                foreach($roles as $role){
                    $cRole = $this->getRoleByName($role->getName(), $containerRoles);
                    /* cRole tiene el rol que esta en el config.yml con sus permisos */
                    if($cRole!=null){
                        $rolePermissions = $role->getRolePermissions();
                        /* Encontró el Rol de la base en el YML, ahora reviso sus permisos */;
                        foreach($rolePermissions as $rolePermission){
                            $permission = $rolePermission->getPermission();
                            /* Para cada permiso del rol en la base de datos */
                            //print_r($cRole["permissions"]);
                            if(!in_array($permission->getName(), $cRole["permissions"])){
                                $manager->remove($rolePermission);
                            }
                        }
                    }else{
                        $rp = $manager->getRepository("BOSUserBundle:RolePermission")->findBy(array("role" => $role));
                        foreach($rp as $rperm){
                            $manager->remove($rperm);
                            $manager->flush();
                        }
                        $manager->remove($role);
                        $manager->flush();
                    }
                }
                $manager->flush();
                
                $permissions = $manager->getRepository("BOSUserBundle:Permission")->findBy(array("system" => $actualSystem));
                foreach($permissions as $permission){
                    $q = $manager->createQuery("SELECT rp FROM BOSUserBundle:RolePermission rp "
                            . "JOIN BOSUserBundle:Role r WHERE r = rp.role "
                            . "WHERE r.system = :system AND rp.permission = :permission");
                    $q->setParameter("system", $actualSystem);
                    $q->setParameter("permission", $permission);
                    $result = $q->getResult();
                    $amountUsed = sizeof($result);
                    if($amountUsed==0){
                        /* No tiene ningún RolePermission */
                        $manager->remove($permission);
                        $manager->flush();
                    }
                }
                
                //guardo los roles que figuran en el yml si hay roles y sistema seteados
                if($containerRoles && $actualSystem){

                    //recorro los roles y los guardo
                    $mKeys = array_keys($containerRoles);
                    $i = -1;
                    foreach ($containerRoles as $actualRole){
                        $i++;
                        $mRoleName = $mKeys[$i];
                        $role = $this->em->getRepository('BOSUserBundle:Role')->findOneBy(array("name"=>$mRoleName,
                                                                                                        "system"=>$actualSystem));
                        
                        
                        //si el rol no existe lo creo
                        if(!$role){
                            $role = new Role();
                            $role->setName($mRoleName);
                            $role->setSystem($actualSystem);
                            $this->em->persist($role);
                            $this->em->flush();
                        }
                        foreach ($actualRole["permissions"] as $actualPermission){
                            $permission = $this->em->getRepository("BOSUserBundle:Permission")->findOneBy(array("system" => $actualSystem, "name" => $actualPermission));
                                                
                            //si el permiso no, existe lo creo
                            if(!$permission){
                                $permission = new Permission();
                                $permission->setName($actualPermission);
                                $permission->setSystem($actualSystem);
                                $this->em->persist($permission);   
                                $this->em->flush();
                            }
                            
                            $rPermission = $this->em->getRepository('BOSUserBundle:RolePermission')->findOneBy(array("role"=>$role, "permission" => $permission));
                            
                            if(!$rPermission){
                                $rPermission = new RolePermission();
                                $rPermission->setRole($role);
                                $rPermission->setPermission($permission);
                                $this->em->persist($rPermission);
                                $this->em->flush();
                            }
                         }    
                         
                    }
                }
                try{
                    
                }catch(\Exception $e){
                    die("Error: " . $e->getMessage());
                }
        }
        
        public function hasRole($username, $role){
            $bos = $this->bos;
            $user = $this->bos->findOneBy(array("username" => $username));
            if(!$user){
                throw new \Exception("User not found");
            }
            $ur = $this->em->getRepository("BOSUserBundle:UserRole")->findOneBy(array("role" => $role, "user" => $user));
            if($ur){
                return true;
            }
            return false;
        }
        
        public function getRole($systemName, $roleName){
            $system = $this->em->getRepository("BOSUserBundle:System")->findOneBy(array("name" => $systemName));
            if(!$system){
                throw new \Exception("System not found");
            }
            $cRole = $this->em->getRepository("BOSUserBundle:Role")->findOneBy(array("system" => $system, "name" => $roleName));
            if(!$cRole){
                throw new \Exception("Role not found");
            }
            return $cRole;
        }
        
        public function assignRole($username, Role $role){
            $bos = $this->bos;
            $user = $this->bos->findOneBy(array("username" => $username));
            if(!$user){
                throw new \Exception("User not found");
            }
            $ur = $this->em->getRepository("BOSUserBundle:UserRole")->findOneBy(array("role" => $role, "user" => $user));
            if($ur){
                throw new \Exception("This role is already assigned to the user.");
            }
            $ur = new UserRole();
            $ur->setUser($user);
            $ur->setRole($role);
            $this->em->persist($ur);
            $user->addUserRole($ur);
            $this->em->persist($user);
            $this->em->flush();
        }
        
        public function discardRole($username, Role $role){
            if(!$role){
                throw new \Exception("You must define a role");
            }
            $u = $this->bos->findOneBy(array("username" => $username));
            if(!$u){
                throw new \Exception("Username not found");
            }
            $ur = $this->em->getRepository("BOSUserBundle:UserRole")->findOneBy(array("role" => $role, "user" => $u));
            if(!$ur){
                throw new \Exception("This role is not assigned to this user");
            }
            $this->em->remove($ur);
            $this->em->flush();
        }
        
        private function getRoleByName($roleName, $array){
            $ret = null;
            try{
                if(isset($array[$roleName])){
                    $ret = $array[$roleName];   
                }
            } catch (Exception $ex) {

            }
            return $ret;
        }
         
	public function onKernelController(FilterControllerEvent $event){
		try{
			if($this->isLoggedIn()){
				$user = $this->container->get('bos_user')->getUser();
				$this->container->get('twig')->addGlobal('bos_user', $user);
			}else{
				$this->container->get('twig')->addGlobal('bos_user', null);
			}
			$request = $event->getRequest();
			$method = $request->attributes->get('_controller');
			if(($request->attributes->get('_route'))==$this->container->getParameter('bos_login_name')){
				return;
			}
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
                                
                        }else{
                            $behaviour = $this->container->getParameter('bos_default_behaviour');
                            if($behaviour){
                                    if($behaviour=="redirect"){
                                            if(!$this->isLoggedIn()){
                                                    $routeName = null;
                                                    try{
                                                            $routeName = $this->container->getParameter('bos_login_name');
                                                    }catch(\Exception $e){
                                                            die("BOSUser: 'bos_login_name' is needed in your parameters.yml");
                                                    }
                                                    $url = "";
                                                    try{
                                                            $url = $this->container->get('router')->generate($routeName);
                                                    }catch(\Exception $e){
                                                            die("BOSUser: Error trying to redirect guest to login page. " . $e->getMessage());
                                                    }
                                                    $event->setController(function() use ($url) {
                                                            return new RedirectResponse($url);
                                                    });	
                                            }					
                                    }
                            }
                        }
		}catch(\Exception $e){
			//Profile involved, let it be
		}
	}
	
	public function create($user){
		if(!$this->isValid($user->getUsername())){
			throw new \Exception("The username is mandatory");
		}
		if(!$this->isValid($user->getPassword())){
			throw new \Exception("The password is mandatory");
		}
		$password = $user->getPassword();
		$u = $this->bos->findOneBy(array("username" => $user->getUsername()));
		if($u){
			throw new \Exception("This username is already taken");
		}
		if($this->isValid($user->getEmail())){
			$u = $this->bos->findOneBy(array("email" => $user->getEmail()));
			if($u){
				throw new \Exception("This email is already taken");
			}
		}
		if($this->entityClass==""){
			//$user = new BOSUser();
		}else{
			/*
			$c = new \ReflectionClass($this->entityClass);
			if(!$c){
				die("BOSUser: Class '" . $c->getName() . "' not found. Check your parameters.");
			}
			$user = $c->newInstanceArgs(array());
			*/
		}
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user->setPassword($hash);
		$this->em->persist($user);
		$this->em->flush();
		return $user;
	}
	
	public function updatePassword($username, $password){
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user = $this->bos->findOneBy(array("username" => $username));
		if(!$user){
			throw new \Exception("The username does not exist");
		}
		try{
			$user->setPassword($hash);
			$this->em->persist($user);
			$this->em->flush($user);
		}catch(\Exception $e){
			throw $e;
		}
		return true;
	}
	
	public function usernameExists($username){
		if($this->bos->findOneBy(array("username" => $username))){
			return true;
		}
		return false;
	}
	
	public function emailExists($email){
		if($this->bos->findOneBy(array("email" => $email))){
			return true;
		}
		return false;
	}
	
	public function login($username, $password){
		if($this->isLoggedIn()){
			$this->logout();
		}
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user = $this->bos->findOneBy(array("username" => $username));
		if(!$user){
			throw new \Exception("The username does not exist");
		}
		if(password_verify($password, $user->getPassword())){
			$session = $this->getSession();
			$session->set('bos_user', $user);
			return $user;
		}else{
			throw new \Exception("User credentials are incorrect");
		}
	}

	public function check($username, $password){
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$user = $this->bos->findOneBy(array("username" => $username));
		if(!$user){
			return false;
		}
		if(password_verify($password, $user->getPassword())){
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
			if($this->container->get('session')){
				$session = $this->container->get('session');
			}else{
				$session = new Session();
			}
			if(!$this->container->get('session')->isStarted()){
				$session->start();
			}
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
