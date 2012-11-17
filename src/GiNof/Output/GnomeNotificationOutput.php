<?php

namespace GiNof\Output;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

/**
 * Output implementation using gnome notify api
 */
class GnomeNotificationOutput implements OutputInterface
{
    private $application;
    private $summary;

    public function __construct(\Dbus $dbus = null)
    {
        $this->dbus = $dbus?: new \Dbus(\Dbus::BUS_SESSION, true);
    }

    public function write($messages, $newline = false, $type = 0)
    {
        return $this->notify($this->getApplication(), $this->getSummary(), $messages);
    }

    public function writeln($messages, $type = 0)
    {
        return $this->write($messages, true, $type);
    }

    public function setVerbosity($level) { }

    public function getVerbosity() { }

    public function setDecorated($decorated) { }

    public function isDecorated() { }

    public function setFormatter(OutputFormatterInterface $formatter) { }

    public function getFormatter() { }

    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    private function notify($application, $summary, $body = '')
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
                array()
            ),
            1000
        );
    }
}
