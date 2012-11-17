<?php

namespace spec\GiNof\Output;

use PHPSpec2\ObjectBehavior;

class GnomeNotificationOutput extends ObjectBehavior
{
    /**
     * @param Dbus $gnome
     */
    public function let($gnome)
    {
        $this->beConstructedWith($gnome);
        $this->setApplication('GnomeNotification');
        $this->setSummary('Testing notification');
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Console\Output\OutputInterface');
    }

    /**
     * @param org\freedesktop\Notifications $proxy
     */
    public function it_should_notify_gnome($gnome, $proxy)
    {
        $gnome->createProxy(
            "org.freedesktop.Notifications",
            "/org/freedesktop/Notifications",
            "org.freedesktop.Notifications"
        )->shouldBeCalled()->willReturn($proxy);

        $proxy->Notify(
            'GnomeNotification',
            ANY_ARGUMENT,
            ANY_ARGUMENT,
            'Testing notification',
            'Hey, can you hear me?',
            ANY_ARGUMENT,
            ANY_ARGUMENT,
            ANY_ARGUMENT
        )->shouldBeCalled();

        $this->writeln('Hey, can you hear me?');
    }
}
