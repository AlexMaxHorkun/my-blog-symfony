<?php
namespace AMH\MyBlogBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ABSwitchCommand
 * @package AMH\MyBlogBundle\Command
 * @author Alexander Horkun mindkilleralexs@gmail.com
 */
class ABSwitchCommand extends ContainerAwareCommand{
    protected function configure(){
        $this->setName('test:abswitch')
            ->setDescription('Switch A with with B')
            ->addArgument('a',InputArgument::REQUIRED,'A')
            ->addArgument("b", InputArgument::REQUIRED, 'B');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $a=$input->getArgument('a');
        $b=$input->getArgument('b');
        $output->writeln("A = $a, B = $b");
        //switching
        $b=[$a,$b];
        $a=$b[1];
        $b=$b[0];
        $output->writeln("new A = $a, new B = $b");
    }
} 