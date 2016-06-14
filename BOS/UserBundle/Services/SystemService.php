<?php

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use BOS\UserBundle\Entity\BOSUser;
use BOS\UserBundle\Entity\BOSUserRepository;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\AnnotationReader;

namespace BOS\UserBundle\Services;

/**
 * Description of SystemService
 *
 * @author tuviej
 */
class SystemService {
    
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
           
   }

}
