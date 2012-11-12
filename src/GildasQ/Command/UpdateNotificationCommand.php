<?php

namespace GildasQ\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GildasQ\Github\NotificationFetcher;
use GildasQ\Persistence\PersisterInterface;

class UpdateNotificationCommand extends Command
{
    private $persister;
    private $fetcher;

    public function __construct(PersisterInterface $persister, NotificationFetcher $fetcher)
    {
        $this->persister = $persister;
        $this->fetcher   = $fetcher;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('fetch')
            ->setDescription('Try to fetch new notification from Github')
            ->addArgument('api-token', InputArgument::REQUIRED, 'Your Github API access token')
            ->addArgument('persist-at', InputArgument::OPTIONAL, 'Path to save number of notifications')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiToken      = $input->getArgument('api-token');
        $notifications = $this->fetcher->fetch($apiToken);

        $this->persister->setPath($input->getArgument('persist-at'));

        $this->persister->save(count($notifications));
    }
}
