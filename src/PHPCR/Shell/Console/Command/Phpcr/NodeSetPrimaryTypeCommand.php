<?php

namespace PHPCR\Shell\Console\Command\Phpcr;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class NodeSetPrimaryTypeCommand extends Command
{
    protected function configure()
    {
        $this->setName('node:set-primary-type');
        $this->setDescription('Set the primary type of the current node');
        $this->addArgument('path', InputArgument::REQUIRED, 'Path of node');
        $this->addArgument('nodeTypeName', null, InputArgument::REQUIRED, null, 'New primary node type name');
        $this->setHelp(<<<HERE
Changes the primary node type of this node to nodeTypeName.

Also immediately changes this node's jcr:primaryType property
appropriately. Semantically, the new node type may take effect
immediately or on dispatch but must take effect on persist. Whichever
behavior is adopted it must be the same as the behavior adopted for
addMixin() (see below) and the behavior that occurs when a node is first
created.
HERE
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->getHelper('phpcr')->getSession();
        $path = $session->getAbsPath($input->getArgument('path'));
        $nodeTypeName = $input->getArgument('nodeTypeName');

        $currentNode = $session->getNode($path);
        $currentNode->setPrimaryType($nodeTypeName);
    }
}
