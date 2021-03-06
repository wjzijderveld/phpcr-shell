<?php

namespace PHPCR\Shell\Console\Command\Phpcr;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class WorkspaceListCommand extends Command
{
    protected function configure()
    {
        $this->setName('workspace:list');
        $this->setDescription('Lists workspaces in the current repository');
        $this->addArgument('srcWorkspace', InputArgument::OPTIONAL, 'If specified, clone from this workspace');
        $this->setHelp(<<<HERE
Lists the workspaces accessible to the current user.

The current workspace is indicated by an asterix (*).

Lists the names of all workspaces in this
repository that are accessible to this user, given the Credentials that
were used to get the Session to which this Workspace is tied.
In order to access one of the listed workspaces, the user performs
another <info>session:login</info>, specifying the name of the desired
workspace, and receives a new Session object.
HERE
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $session = $this->getHelper('phpcr')->getSession();

        $workspace = $session->getWorkspace();
        $availableWorkspaces = $workspace->getAccessibleWorkspaceNames();

        $table = $this->getHelper('table')->create();
        $table->setHeaders(array('Name'));
        foreach ($availableWorkspaces as $availableWorkspace) {
            if ($availableWorkspace == $workspace->getName()) {
                $availableWorkspace .= ' *';
            }
            $table->addRow(array($availableWorkspace));
        }

        $table->render($output);
    }
}
