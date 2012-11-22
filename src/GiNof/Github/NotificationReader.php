<?php

namespace GiNof\Github;

use Buzz\Client\Curl;
use Buzz\Message\Response;
use Buzz\Client\ClientInterface;

class NotificationReader
{
    private $requestFactory;

    public function __construct(
        ClientInterface $client = null,
        RequestFactory $requestFactory = null,
        ResponseFactory $responseFactory = null
    )
    {
        $this->client          = $client ?: new Curl;
        $this->requestFactory  = $requestFactory ?: new RequestFactory;
        $this->responseFactory = $responseFactory ?: new ResponseFactory;
    }

    public function read($apiToken)
    {
        $request  = $this->requestFactory->createRequest($apiToken, false, true);
        $response = $this->responseFactory->createResponse();
        $this->client->send($request, $response);

        return $this->isResetContent($response);
    }

    private function isResetContent(Response $response)
    {
        return 205 === $response->getStatusCode();
    }
}
