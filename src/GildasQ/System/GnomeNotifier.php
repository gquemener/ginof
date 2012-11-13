<?php

namespace GildasQ\System;

class GnomeNotifier implements NotifierInterface
{
    public function __construct(\Dbus $dbus = null)
    {
        $this->dbus = $dbus?: new \Dbus(\Dbus::BUS_SESSION, true);
    }

    public function notify($application, $summary, $body = '')
    {
        $notificator = $this->dbus->createProxy(
            "org.freedesktop.Notifications",
            "/org/freedesktop/Notifications",
            "org.freedesktop.Notifications"
        );

        $notificator->Notify(
            $application,
            new \DBusUInt32(1),
            'gnome-terminal',
            $summary,
            $body,
            new \DBusArray(\DBus::STRING, array()),
            new \DBusDict(
                \DBus::VARIANT,
                array(
                )
            ),
            5000
        );
    }
}
