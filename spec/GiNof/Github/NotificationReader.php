<?php

namespace spec\GiNof\Github;

use PHPSpec2\ObjectBehavior;

class NotificationReader extends ObjectBehavior
{
    /**
     * @param GiNof\Github\RequestFactory $requestFactory
     * @param GiNof\Github\ResponseFactory $responseFactory
     * @param Buzz\Client\ClientInterface $client
     * @param Buzz\Message\Request $request
     * @param Buzz\Message\Response $response
     */
    function let($requestFactory, $responseFactory, $client, $request, $response)
    {
        $this->beConstructedWith($client, $requestFactory, $responseFactory);
        $responseFactory->createResponse()->willReturn($response);
    }

    function it_should_read_all_notifications($requestFactory, $request, $response, $client)
    {
        $requestFactory->createRequest('my api token', false, true)->shouldBeCalled()->willReturn($request);
        $client->send($request, $response)->shouldBeCalled();

        $this->read('my api token');
    }

    function it_should_return_true_if_notifications_have_been_marked_as_read($requestFactory, $request, $response, $client)
    {
        $requestFactory->createRequest(ANY_ARGUMENTS)->willReturn($request);
        $response->getStatusCode()->willReturn(205);
        $client->send($request, $response)->shouldBeCalled();

        $this->read('my api token')->shouldReturn(true);
    }

    function it_should_return_false_otherwise($requestFactory, $request, $response, $client)
    {
        $requestFactory->createRequest(ANY_ARGUMENTS)->willReturn($request);
        $response->getStatusCode()->willReturn(500);
        $client->send($request, $response)->shouldBeCalled();

        $this->read('my api token')->shouldReturn(false);
    }
}
