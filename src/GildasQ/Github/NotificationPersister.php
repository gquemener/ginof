<?php

namespace GildasQ\Github;

class NotificationPersister
{
    public function persist($notifications = null)
    {
        $notifications = is_array($notifications)?: array();
        file_put_contents('/tmp/github-notifications', count($notifications));
    }
}
