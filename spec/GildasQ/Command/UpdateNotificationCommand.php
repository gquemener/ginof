<?php

namespace spec\GildasQ\Command;

use PHPSpec2\ObjectBehavior;

class UpdateNotificationCommand extends ObjectBehavior
{
    /**
     * @param GildasQ\Persistence\PersisterInterface $persister
     * @param GildasQ\Github\NotificationFetcher $fetcher
     * @param GildasQ\Github\NotificationFactory $factory
     */
    function let($persister, $fetcher, $factory)
    {
        $this->beConstructedWith($persister, $fetcher, $factory);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }

    /**
     * @param Symfony\Component\Console\Input\InputInterface $input
     * @param Symfony\Component\Console\Output\OutputInterface $output
     * @param StdClass $notif1
     * @param StdClass $notif2
     */
    function it_should_fetch_and_persist_github_notifications($input, $output, $notif1, $notif2, $fetcher, $persister, $factory)
    {
        $input->getArgument('api_token')->shouldBeCalled()->willReturn('1234');
        $notifications = [$notif1, $notif2];
        $fetcher->fetch('1234')->shouldBeCalled()->willReturn($notifications);
        $persiter->save(2)->shouldBeCalled();


        $this->execute($input, $output);
    }
}
