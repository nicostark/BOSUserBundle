<?php
namespace BOS\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use BOS\UserBundle\Entity\BOSUser;

class CreateCommand extends ContainerAwareCommand
{
	
	private $container;
	
	protected function configure(){
		$this
		->setName('bos:user:create')
		->setDescription("Creates a user")
		->addArgument(
				'username',
				InputArgument::REQUIRED,
				'The username'
		)
		->addArgument(
				'password',
				InputArgument::REQUIRED,
				'The password'
		);
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$username = $input->getArgument("username");
		$password = $input->getArgument("password");
			$this->container = $this->getContainer();
		$bos = $this->container->get('bos_user');
		if(!$bos){
			$output->writeln("<error>Couldn't find the bos_user service</error>");
			return;
		}
		if($bos->usernameExists($username)){
			$output->writeln("<error>The username already exists</error>");
			return;
		}
		$user = null;
		try{
			if($bos->entityClass==""){
				$user = new BOSUser();
			}else{				
				$c = new \ReflectionClass($bos->entityClass);
				if(!$c){
				die("BOSUser: Class '" . $c->getName() . "' not found. Check your parameters.");
				}
				$user = $c->newInstanceArgs(array());
			}
			$user->setUsername($username);
			$user->setPassword($password);
			$em = $this->container->get('doctrine')->getEntityManager();
			$em->persist($user);
			$em->flush();
		}catch(\Exception $e){
			$output->writeln("<error>" . $e->getMessage() . "</error>");
			return;
		}
		$output->writeln("<info>User " . $username . " created successfully</info>");
	}
	
}