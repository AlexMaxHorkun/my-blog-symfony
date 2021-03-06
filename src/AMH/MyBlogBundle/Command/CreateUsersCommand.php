<?php
namespace AMH\MyBlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
/**
@author Alexander Horkun mindkilleralexs@gmail.com
*/
class CreateUsersCommand extends ContainerAwareCommand{
	protected function configure(){
		$this->setName('amh:myblog:create:users')
			->setDescription('Create users')
			->addArgument('count',InputArgument::REQUIRED,'Count of users you want to create');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$generator=$this->getContainer()->get('amh_my_blog.util.entity_generator');
		$encoder=NULL;
		try{
			$encoder=$this->getContainer()->get('security.encoder_factory')->getEncoder('AMH\MyBlogBundle\User\User');
		}
		catch(\Exception $e){ /* nothing to do, no encoder for user class */ }
		$users=$generator->createUsers($input->getArgument('count'),$encoder);
		$output->writeln('users persisted ('.count($users).'):');
		foreach($users as $u){
			$output->writeln($u->getEmail().' "'.$u->getName().'" #'.$u->getId());
		}
	}
}
?>
