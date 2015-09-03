<?php
namespace BOS\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use BOS\UserBundle\Entity\BOSUser;

class PasswordCommand extends ContainerAwareCommand
{
	
	private $container;
	
	protected function configure(){
		$this
		->setName('bos:user:password')
		->setDescription("Changes a user's password")
		->addArgument(
				'username',
				InputArgument::REQUIRED,
				'The username'
		)
		->addArgument(
				'password',
				InputArgument::REQUIRED,
				'New password'
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
		if(!$bos->usernameExists($username)){
			$output->writeln("<error>The username doesn't exists</error>");
			return;
		}
		try{
			$bos->updatePassword($username, $password);
		}catch(\Exception $e){
			$output->writeln("<error>" . $e->getMessage() . "</error>");
			return;
		}
		$output->writeln("<info>User " . $username . " updated successfully</info>");
	}
	
}