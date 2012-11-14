<?php

namespace GildasQ\Github;

use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Buzz\Client\ClientInterface;
use GildasQ\Persistence\FileSystemPersister;
use GildasQ\Persistence\PersisterInterface;

class NotificationFetcher
{
    private $client;
    private $requestFactory;
    private $responseFactory;
    private $notificationFactory;
    private $lastModifiedFilePath = '/tmp/github-last-modified-notification';

    public function __construct(
        ClientInterface $client = null,
        RequestFactory $requestFactory = null,
        ResponseFactory $responseFactory = null,
        NotificationFactory $notificationFactory = null,
        PersisterInterface $persister = null
    )
    {
        $this->client              = $client?: new Curl;
        $this->requestFactory      = $requestFactory?: new RequestFactory;
        $this->responseFactory     = $responseFactory?: new ResponseFactory;
        $this->notificationFactory = $notificationFactory?: new NotificationFactory;
        $this->persister           = $persister?: new FileSystemPersister;
    }

    public function setPersister(PersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    public function fetch($apiToken = null)
    {
        if (!$apiToken) {
            throw new \RuntimeException('Please provide a valid api token. See http://developer.github.com/v3/oauth');
        }

        $request  = $this->requestFactory->createRequest($apiToken, $this->getLastModified());
        $response = $this->responseFactory->createResponse();
        $this->client->send($request, $response);

        if ($response->isOk()) {
            if ($date = $response->getHeader('Last-Modified')) {
                $this->setLastModified($date);
            } else {
                $this->setLastModified($response->getHeader('Date'));
            }

            if (null !== $data = json_decode($response->getContent(), true)) {
                $notifications = $this->notificationFactory->createNotifications($data);
                $this->persister->save($notifications);

                return $notifications;
            } else {
                throw new \RuntimeException('Error while parsing json response.');
            }
        }

        return $this->persister->retrieve();
    }

    private function getLastModified()
    {
        if (is_file($this->lastModifiedFilePath)) {
            return file_get_contents($this->lastModifiedFilePath);
        }

        return null;
    }

    private function setLastModified($lastModified)
    {
        return file_put_contents($this->lastModifiedFilePath, $lastModified);
    }
}
