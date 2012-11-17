<?php

namespace GiNof\Github;

class NotificationFactory
{
    public function createNotifications(array $data = [])
    {
        $notifications = [];
        foreach ($data as $value) {
            $notifications[] = $this->createNotification($value);
        }

        return $notifications;
    }

    private function createNotification(array $data = [])
    {
        $notification = new Notification;
        $notification
            ->setRepositoryFullName($data['repository']['full_name'])
            ->setSubjectTitle($data['subject']['title'])
            ->setSubjectUrl($data['subject']['url'])
            ->setSubjectType($data['subject']['type'])
        ;

        return $notification;
    }
}
