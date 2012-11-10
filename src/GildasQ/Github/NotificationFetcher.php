<?php

namespace GildasQ\Github;

use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Buzz\Client\ClientInterface;

class NotificationFetcher
{
    private $client;
    private $requestFactory;
    private $responseFactory;

    public function __construct(ClientInterface $client = null, RequestFactory $requestFactory = null, ResponseFactory $responseFactory = null)
    {
        $this->client          = $client?: new Curl;
        $this->requestFactory  = $requestFactory?: new RequestFactory;
        $this->responseFactory = $responseFactory?: new ResponseFactory;
    }

    public function fetch($apiToken = null)
    {
        if (!$apiToken) {
            throw new \RuntimeException('Please provide a valid api token. See http://developer.github.com/v3/activity/notifications/');
        }

        $request  = $this->requestFactory->createRequest($apiToken, $this->getLastModified());
        $response = $this->responseFactory->createResponse();
        $this->client->send($request, $response);

        if ($response->isOk()) {
            $this->setLastModified($response->getHeader('Date'));

            return json_decode($response->getContent(), true);
        }

        return;
    }

    private function getLastModified()
    {
        if (is_file('/tmp/github-notification')) {
            return file_get_contents('/tmp/github-notification');
        }

        return null;
    }

    private function setLastModified($lastModified)
    {
        return file_put_contents('/tmp/github-notification', $lastModified);
    }
}
