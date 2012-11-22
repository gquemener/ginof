<?php

namespace GiNof\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use GiNof\Github\NotificationReader;

/**
 * Provide command to mark notifications as read
 */
class ReadNotificationCommand extends Command
{
    private $fetcher;

    /**
     * @param NotificationRead $fetcher   The notification reader
     */
    public function __construct(NotificationReader $fetcher = null)
    {
        $this->reader   = $fetcher   ?: new NotificationReader;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Mark all notifications as read')
            ->addArgument(
                'api-token',
                InputArgument::REQUIRED,
                'Your Github API access token'
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->reader->read($input->getArgument('api-token'))) {
            $output->writeln('<info>✔ All notifications were marked as read</info>');
        } else {
            $output->writeln('<comment>✘ Coulnd\'t mark notifications as read</comment>');
        }

    }
}
