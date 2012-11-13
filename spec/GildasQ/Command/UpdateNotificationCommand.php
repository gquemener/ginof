<?php

namespace spec\GildasQ\Command;

use PHPSpec2\ObjectBehavior;

class UpdateNotificationCommand extends ObjectBehavior
{
    /**
     * @param GildasQ\Persistence\PersisterInterface $persister
     * @param GildasQ\Github\NotificationFetcher $fetcher
     * @param GildasQ\System\NotifierInterface $notifier
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @param StdClass $notification1
     * @param StdClass $notification2
     * @param StdClass $notification3
     */
    function let($persister, $fetcher, $notifier, $input, $output, $notification1, $notification2, $notification3, $fetcher)
    {
        $this->beConstructedWith($persister, $fetcher, $notifier);

        $input->getArgument('api-token')->willReturn('1234');
        $input->getArgument('persist-at')->willReturn('some file');
        $input->getArgument('repository')->willReturn('some repo');

        $notification1->getRepositoryFullName()->willReturn('some repo');
        $notification2->getRepositoryFullName()->willReturn('some other repo');
        $notification3->getRepositoryFullName()->willReturn('some repo');

        $fetcher->fetch(ANY_ARGUMENT)->willReturn([$notification1, $notification2, $notification3]);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    /**
     */
    function it_should_display_number_of_notifications_in_the_given_repository_among_all_notifications($input, $output)
    {
        // $output->wrintln("0/3")->shouldBeCalled();

        $this->execute($input, $output);
    }

    function it_should_use_system_notifier_to_displayer_result_of_fetching($input, $output, $notifier)
    {
        $notifier->notify(
            'github-notification-fetcher',
            'Github: You have 3 new notifications'
        )->shouldBeCalled();

        $this->execute($input, $output);
    }
}
