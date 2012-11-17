<?php

namespace GiNof\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GiNof\Github\NotificationFetcher;
use GiNof\Github\Router;
use GiNof\Persistence\PersisterInterface;
use GiNof\Persistence\FileSystemPersister;
use GiNof\System\NotifierInterface;
use GiNof\System\GnomeNotifier;
use Symfony\Component\Console\Input\InputOption;

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
     * @param Router              $router    The Github router
     */
    public function __construct(
        PersisterInterface $persister = null,
        NotificationFetcher $fetcher = null,
        NotifierInterface $notifier = null,
        Router $router = null
    )
    {
        $this->persister = $persister ?: new FileSystemPersister;
        $this->fetcher   = $fetcher   ?: new NotificationFetcher;
        $this->notifier  = $notifier  ?: new GnomeNotifier;
        $this->router    = $router    ?: new Router;

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
                'Where to store notifications cache',
                '/tmp/github-notifications'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Wether or not to bypass the cache and force fetching the notifiations'
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiToken      = $input->getArgument('api-token');
        $persistAt     = $input->getArgument('persist-at');

        $this->persister->setPath($persistAt);
        $this->fetcher->setPersister($this->persister);

        $notifications = $this->fetcher->fetch($apiToken, $input->getOption('force'));

        if (null === $notifications) {
            return;
        }

        if (count($notifications)) {
            $body = '';
            foreach ($notifications as $notification) {
                $body .= $notification->getBody() . PHP_EOL;
                $body .= $this->router->generateUrl(
                    $notification->getSubjectUrl(),
                    $notification->getSubjectType()
                ) . PHP_EOL;
            }
            $this->notifier->notify(
                'github-notification-fetcher',
                sprintf('Github: You have %d new notifications', count($notifications)),
                $body
            );
        }
    }
}
