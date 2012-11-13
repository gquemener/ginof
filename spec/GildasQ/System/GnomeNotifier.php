<?php

namespace spec\GildasQ\System;

use PHPSpec2\ObjectBehavior;

class GnomeNotifier extends ObjectBehavior
{
    /**
     * @param Dbus $gnome
     */
    public function let($gnome)
    {
        $this->beConstructedWith($gnome);
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('GildasQ\System\NotifierInterface');
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
            'Hey, can your hear me?',
            ANY_ARGUMENT,
            ANY_ARGUMENT,
            ANY_ARGUMENT
        )->shouldBeCalled();

        $this->notify('GnomeNotification', 'Testing notification', 'Hey, can your hear me?');
    }
}
