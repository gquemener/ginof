<?php

namespace spec\GildasQ\Github;

use PHPSpec2\ObjectBehavior;

class NotificationFetcher extends ObjectBehavior
{
    /**
     * @param Buzz\Client\ClientInterface $client
     * @param GildasQ\Github\RequestFactory $requestFactory
     * @param GildasQ\Github\ResponseFactory $responseFactory
     * @param Buzz\Message\Request $request
     * @param Buzz\Message\Response $response
     */
    function let($client, $requestFactory, $responseFactory, $request, $response)
    {
        $this->beConstructedWith($client, $requestFactory, $responseFactory);
        $requestFactory->createRequest(ANY_ARGUMENTS)->willReturn($request);
        $responseFactory->createResponse()->willReturn($response);
    }

    function it_should_query_only_if_modification_has_been_detected($request, $response)
    {
        $json = <<<JSON
{
    "id": 1,
    "repository": {
      "id": 1296269,
      "owner": {
        "login": "octocat",
        "id": 1,
        "avatar_url": "https://github.com/images/error/octocat_happy.gif",
        "gravatar_id": "somehexcode",
        "url": "https://api.github.com/users/octocat"
      },
      "name": "Hello-World",
      "full_name": "octocat/Hello-World",
      "description": "This your first repo!",
      "private": false,
      "fork": false,
      "url": "https://api.github.com/repos/octocat/Hello-World",
      "html_url": "https://github.com/octocat/Hello-World"
    },
    "subject": {
      "title": "Greetings",
      "url": "https://api.github.com/repos/pengwynn/octokit/issues/123",
      "latest_comment_url": "https://api.github.com/repos/pengwynn/octokit/issues/comments/123"
    },
    "reason": "subscribed",
    "unread": true,
    "updated_at": "2012-09-25T07:54:41-07:00",
    "last_read_at": "2012-09-25T07:54:41-07:00",
    "url": "https://api.github.com/notifications/threads/1"
  }
]
JSON;

        $response->isOk()->willReturn(true);
        $response->getContent()->willReturn($json);

        $this->fetch('1234')->shouldReturn(json_decode($json, true));
    }

    function it_should_not_query_if_no_new_notification($request, $response)
    {
        $response->isOk()->willReturn(false);
        $this->fetch('1234')->shouldReturn(null);
    }
}
