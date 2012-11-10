<?php

namespace GildasQ\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GildasQ\Github\NotificationFetcher;
use GildasQ\Github\NotificationPersister;

class UpdateNotificationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('fetch')
            ->setDescription('Try to fetch new notification from Github')
            ->addArgument('api_token', InputArgument::REQUIRED, 'Your Github API access token')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiToken = $input->getArgument('api_token');
        $fetcher = new NotificationFetcher;
        $notifications = $fetcher->fetch($apiToken);

        // Write notifications to a file
        $persister = new NotificationPersister;
        $persister->persist($notifications);

        // notify-send
    }
}
