<?php

namespace BOS\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use BOS\UserBundle\Entity\Role;
use BOS\UserBundle\Entity\Permission;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BOSUserExtension extends Extension
{
    
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
		if(array_key_exists("bos_login_name", $config)){
			$container->setParameter("bos_login_name", $config["bos_login_name"]);
		}else{
			$container->setParameter("bos_login_name", null);
		}
		if(array_key_exists("bos_user_entity", $config)){
			$container->setParameter("bos_user_entity", $config["bos_user_entity"]);
		}else{
			$container->setParameter("bos_user_entity", null);
		}
		if(array_key_exists("bos_default_behaviour", $config)){
			$container->setParameter("bos_default_behaviour", $config["bos_default_behaviour"]);
		}else{
			$container->setParameter("bos_default_behaviour", null);
		}
                if(array_key_exists("bos_system", $config)){
			$container->setParameter("bos_system", $config["bos_system"]);
		}else{
			$container->setParameter("bos_system", null);
		}
                if(array_key_exists("roles", $config)){
                    	$container->setParameter("roles", $config["roles"]);
                        
                }else{
			$container->setParameter("roles", null);
		}
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
