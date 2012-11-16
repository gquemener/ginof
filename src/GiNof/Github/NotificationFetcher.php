<?php

namespace GiNof\Github;

use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Buzz\Client\ClientInterface;
use GiNof\Persistence\FileSystemPersister;
use GiNof\Persistence\PersisterInterface;

class NotificationFetcher
{
    private $client;
    private $requestFactory;
    private $responseFactory;
    private $notificationFactory;

    public function __construct(
        ClientInterface $client = null,
        RequestFactory $requestFactory = null,
        ResponseFactory $responseFactory = null,
        NotificationFactory $notificationFactory = null,
        PersisterInterface $persister = null
    )
    {
        $this->client              = $client ?: new Curl;
        $this->requestFactory      = $requestFactory ?: new RequestFactory;
        $this->responseFactory     = $responseFactory ?: new ResponseFactory;
        $this->notificationFactory = $notificationFactory ?: new NotificationFactory;
        $this->persister           = $persister ?: new FileSystemPersister;
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

        $request  = $this->requestFactory->createRequest($apiToken, $this->persister->getLastModified());
        $response = $this->responseFactory->createResponse();
        $this->client->send($request, $response);

        if ($this->isNotModified($response)) {
            return $this->persister->retrieve();
        }

        if (null !== $data = json_decode($response->getContent(), true)) {
            $notifications = $this->notificationFactory->createNotifications($data);
            $this->persister->save($notifications);

            return $notifications;
        } else {
            throw new \RuntimeException('Error while parsing json response.');
        }
    }

    private function isNotModified(Response $response)
    {
        return 304 === $response->getStatusCode();
    }
}
