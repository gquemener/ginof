<?php

namespace spec\GildasQ\Command;

use PHPSpec2\ObjectBehavior;

class UpdateNotificationCommand extends ObjectBehavior
{
    /**
     * @param GildasQ\Persistence\PersisterInterface           $persister
     * @param GildasQ\Github\NotificationFetcher               $fetcher
     * @param GildasQ\System\NotifierInterface                 $notifier
     * @param Symfony\Component\Console\Input\InputInterface   $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @param GildasQ\Github\Notification                      $notification1
     * @param GildasQ\Github\Notification                      $notification2
     * @param GildasQ\Github\Notification                      $notification3
     */
    function let($persister, $fetcher, $notifier, $input, $output, $notification1, $notification2, $notification3)
    {
        $this->beConstructedWith($persister, $fetcher, $notifier);

        $input->getArgument('api-token')->willReturn('1234');
        $input->getArgument('persist-at')->willReturn('some file');
        $input->getArgument('repository')->willReturn('some repo');

        $notification1->getRepositoryFullName()->willReturn('some repo');
        $notification2->getRepositoryFullName()->willReturn('some other repo');
        $notification3->getRepositoryFullName()->willReturn('some repo');

        $notification1->getBody()->willReturn('subject');
        $notification2->getBody()->willReturn('subject');
        $notification3->getBody()->willReturn('subject');

        $fetcher->fetch(ANY_ARGUMENT)->willReturn([$notification1, $notification2, $notification3]);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    function it_should_display_number_of_notifications_in_the_given_repository_among_all_notifications($input, $output)
    {
        $output->writeln('2/3')->shouldBeCalled();

        $this->execute($input, $output);
    }

    function it_should_use_system_notifier_to_display_result_of_fetching($input, $output, $notifier)
    {
        $notifier->notify(
            'github-notification-fetcher',
            'Github: You have 3 new notifications',
            'subject' . PHP_EOL . 'subject' . PHP_EOL . 'subject' . PHP_EOL
        )->shouldBeCalled();

        $this->execute($input, $output);
    }
}
