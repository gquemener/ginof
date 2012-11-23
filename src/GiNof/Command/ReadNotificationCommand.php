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
    private $reader;
    private $config;

    /**
     * @param NotificationRead $reader   The notification reader
     */
    public function __construct(array $config = array(), NotificationReader $reader = null)
    {
        $this->reader = $reader ?: new NotificationReader;
        $this->config = $config;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Mark all notifications as read')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiToken = $this->config['parameters']['api_token'];

        if ($this->reader->read($apiToken)) {
            $output->writeln('<info>✔ All notifications were marked as read</info>');
        } else {
            $output->writeln('<comment>✘ Coulnd\'t mark notifications as read</comment>');
        }

    }
}
