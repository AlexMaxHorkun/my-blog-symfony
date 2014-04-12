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
class CreateRatesCommand extends ContainerAwareCommand{
	protected function configure(){
		$this->setName('amh:myblog:create:rates')
			->setDescription('Create rates for each post by each user');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$generator=$this->getContainer()->get('amh_my_blog.util.entity_generator');
		$rates=$generator->createRates();
		$output->writeln('Rates persisted ('.count($rates).'):');
		foreach($rates as $r){
			$output->writeln($r->getRating().' for '.$r->getPost()->getTitle().' by '.$r->getBy()->getName());
		}
	}
}
?>
