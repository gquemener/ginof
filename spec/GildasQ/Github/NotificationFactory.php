<?php

namespace spec\GildasQ\Github;

use PHPSpec2\ObjectBehavior;

class NotificationFactory extends ObjectBehavior
{
    function it_should_create_notifications_from_an_array()
    {
        $notifications = json_decode(file_get_contents('./notifications.json'), true);

        $notifications = $this->createNotifications($notifications);
    }
}
