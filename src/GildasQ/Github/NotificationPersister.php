<?php

namespace GildasQ\Github;

class NotificationPersister
{
    public function persist(array $notifications = array())
    {
        file_put_contents('/tmp/github-notifications', count($notifications));
    }
}
