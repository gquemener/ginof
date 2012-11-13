<?php

namespace GildasQ\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GildasQ\Github\NotificationFetcher;
use GildasQ\Persistence\PersisterInterface;
use GildasQ\System\NotifierInterface;

/**
 * Provide command to fetch and display notification
 */
class UpdateNotificationCommand extends Command
{
    private $persister;
    private $fetcher;
    private $notifier;

    /**
     * @param PersisterInterface  $persister The engine to persist notifications
     * @param NotificationFetcher $fetcher   The notification fetcher
     * @param NotifierInterface   $notifier  The system notifier
     */
    public function __construct(PersisterInterface $persister, NotificationFetcher $fetcher, NotifierInterface $notifier)
    {
        $this->persister = $persister;
        $this->fetcher   = $fetcher;
        $this->notifier  = $notifier;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fetch')
            ->setDescription('Try to fetch new notification from Github')
            ->addArgument(
                'api-token',
                InputArgument::REQUIRED,
                'Your Github API access token'
            )
            ->addArgument(
                'persist-at',
                InputArgument::OPTIONAL,
                'Path to save number of notifications'
            )
            ->addArgument(
                'repository',
                InputArgument::OPTIONAL,
                'Repository in which you want to retrieve notifications'
            );
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiToken      = $input->getArgument('api-token');
        $persistAt     = $input->getArgument('persist-at');
        $repository    = $input->getArgument('repository');

        $this->persister->setPath($persistAt);
        $this->fetcher->setPersister($this->persister);

        $notifications = $this->fetcher->fetch($apiToken);

        $repoNotif = array_filter($notifications, function($notification) use ($repository) {
            return $repository === $notification->getRepositoryFullName();
        });

        $output->writeln(sprintf('%d/%d', count($repoNotif), count($notifications)));

        if (count($notifications)) {
            $body = '';
            foreach ($notifications as $notification) {
                $body .= (string) $notification . PHP_EOL;
            }
            $this->notifier->notify(
                'github-notification-fetcher',
                sprintf('Github: You have %d new notifications', count($notifications)),
                $body
            );
        }
    }
}
