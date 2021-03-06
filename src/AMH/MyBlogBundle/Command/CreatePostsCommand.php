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
class CreatePostsCommand extends ContainerAwareCommand{
	protected function configure(){
		$this->setName('amh:myblog:create:posts')
			->setDescription('Create posts')
			->addArgument('count',InputArgument::REQUIRED,'Count of posts you want to create');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$generator=$this->getContainer()->get('amh_my_blog.util.entity_generator');
		$posts=$generator->createPosts($input->getArgument('count'));
		$output->writeln('Posts persisted ('.count($posts).'):');
		foreach($posts as $p){
			$output->writeln($p->getTitle().' #'.$p->getId());
		}
	}
}
?>
