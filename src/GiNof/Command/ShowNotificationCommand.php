<?php

namespace GiNof\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GiNof\Github\Router;
use GiNof\Persistence\PersisterInterface;
use GiNof\Persistence\FileSystemPersister;

/**
 * Provide command to fetch and display notification
 */
class ShowNotificationCommand extends Command
{
    private $persister;
    private $notifier;

    /**
     * @param PersisterInterface  $persister The engine to persist notifications
     * @param Router              $router    The Github router
     */
    public function __construct(
        PersisterInterface $persister = null,
        Router $router = null
    )
    {
        $this->persister = $persister ?: new FileSystemPersister;
        $this->router    = $router    ?: new Router;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('show')
            ->setDescription('Show cached notifications')
            ->addArgument(
                'persist-at',
                InputArgument::OPTIONAL,
                'Where to read notifications cache from',
                '/tmp/github-notifications'
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $persistAt = $input->getArgument('persist-at');

        $this->persister->setPath($persistAt);
        $notifications = $this->persister->retrieve();

        $output->writeln(sprintf(
            '## <info>%d notifications on %s</info>',
            count($notifications),
            $this->persister->getLastModified()
        ));

        foreach ($notifications as $notification) {
            $output->writeln(sprintf(
                '<comment>%s</comment> %s',
                $notification->getBody(),
                $this->router->generateUrl(
                    $notification->getSubjectUrl(),
                    $notification->getSubjectType()
                )
            ));
        }
    }
}
