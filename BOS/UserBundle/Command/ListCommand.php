<?php
namespace BOS\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use BOS\UserBundle\Entity\BOSUser;

class ListCommand extends ContainerAwareCommand
{
	
	private $container;
	
	protected function configure(){
		$this
		->setName('bos:user:list')
		->setDescription("Lists all the users");
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$this->container = $this->getContainer();
		$bos = $this->container->get('bos_user');
		$output->writeln("");
		if(!$bos){
			$output->writeln("<error>Couldn't find the bos_user service</error>");
			return;
		}
		$users = $bos->bos->findBy(array(),array("username" => "ASC"));
		if(!$users){
			$output->writeln("<error>Couldn't find the repository</error>");
			return;
		}
		if($bos->entityClass==""){
			$output->writeln("<bg=yellow>List of BOSUsers</>");
		}else{
			$output->writeln("<info>List of " . $bos->entityClass . "</info>");
		}
		foreach($users as $user){
			$output->writeln($user->getUsername());
		}
		$output->writeln("");
	}
}