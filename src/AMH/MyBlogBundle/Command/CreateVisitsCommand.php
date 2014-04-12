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
class CreateVisitsCommand extends ContainerAwareCommand{
	protected function configure(){
		$this->setName('amh:myblog:create:visits')
			->setDescription('Add visits')
			->addArgument('count',InputArgument::OPTIONAL,'Coutn of visits to add to each post');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$generator=$this->getContainer()->get('amh_my_blog.util.entity_generator');
		$generator->addVisits($input->getArgument('count'));
		$output->writeln('Visits added');
	}
}
?>
