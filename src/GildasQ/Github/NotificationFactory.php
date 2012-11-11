<?php

namespace GildasQ\Github;

class NotificationFactory
{
    public function createNotifications(array $data = [])
    {
        $notifications = [];
        foreach ($data as $value) {
            $notification = $this->createNotification($value);
            $notifications[] = $notification;
        }

    }

    private function createNotification(array $data = [])
    {
        $notification = new Notification;
        $notification
            ->setRepositoryFullName($data['repository']['full_name'])
            ->setSubjectTitle($data['subject']['title'])
            ->setSubjectUrl($data['subject']['url'])
        ;

        return $notification;
    }
}
