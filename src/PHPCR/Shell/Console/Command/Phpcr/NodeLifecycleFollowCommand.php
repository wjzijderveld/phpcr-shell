<?php

namespace PHPCR\Shell\Console\Command\Phpcr;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use PHPCR\RepositoryInterface;

class NodeLifecycleFollowCommand extends PhpcrShellCommand
{
    protected function configure()
    {
        $this->setName('node:lifecycle:follow');
        $this->setDescription('Causes the lifecycle state of this node to undergo the specified transition.');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path of node');
        $this->addArgument('transition', InputArgument::REQUIRED, 'A state transition');
        $this->setHelp(<<<HERE
Causes the lifecycle state of the current node to undergo the specified
transition.

This command may change the value of the jcr:currentLifecycleState
property, in most cases it is expected that the implementation will
change the value to that of the passed transition parameter, though this
is an implementation-specific issue. If the jcr:currentLifecycleState
property is changed the change is persisted immediately, there is no
need to call save.
HERE
        );

        $this->requiresDescriptor(RepositoryInterface::OPTION_LIFECYCLE_SUPPORTED, true);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->getHelper('phpcr')->getSession();
        $path = $session->getAbsPath($input->getArgument('path'));
        $currentNode = $session->getNode($path);
        $transition = $input->getArgument('transition');
        $currentNode->followLifecycleTransition($transition);
    }
}
