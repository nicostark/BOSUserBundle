<?php

namespace BOS\UserBundle\Controller;

use BOS\UserBundle\Annotations\BOSUserFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @BOSUserFilter(loginRequired=false)
     * @Route("/bos/publica", name="main_bos")
     * @Template()
     */
    public function indexAction()
    {
		die("Esta página está disponible sin estar logueado");
    }
    
    /**
     * @BOSUserFilter(loginRequired=true)
     * @Route("/bos/privada", name="main_private")
     * @Template()
     */
    public function privateAction()
    {
    	die("Esta página es privada, solo se puede acceder estando logueado.");
    }
    
    /**
     * @Route("/bos/home", name="main_login_page")
     * @Template()
     */
    public function doLoginAction()
    {
    	$s = $this->get('bos_user');
    	if($s){
    		echo("Servicio activo. ");
    	}
    	die("Usted intentó acceder a una página sin estar logueado! Ha sido redireccionado aquí");
    }
    
    /**
     * @Route("/bos/login", name="bos_login")
     * @Template()
     */
    public function loginAction()
    {
    	$s = $this->get('bos_user');
    	try{
    		$s->login("nbullorini", "nbullorini");
    	}catch(\Exception $e){
    		echo("Error: " . $e->getMessage() . "<br />");
    	}
    	die("Página de LOGIN, éxito al loguear. Sesión iniciada.");
    }
    
    /**
     * @Route("/bos/logout", name="bos_logout")
     * @Template()
     */
    public function logoutAction()
    {
    	$s = $this->get('bos_user');
    	try{
    		$s->logout();
    	}catch(\Exception $e){
    		echo("Error: " . $e->getMessage() . "<br />");
    	}
    	die("Página de LOGOUT. Deslogueado.");
    }
    
}