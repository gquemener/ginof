<?php

namespace spec\GiNof\Github;

use PHPSpec2\ObjectBehavior;

class NotificationFetcher extends ObjectBehavior
{
    /**
     * @param GiNof\Github\RequestFactory $requestFactory
     * @param GiNof\Github\ResponseFactory $responseFactory
     * @param GiNof\Github\NotificationFactory $notificationFactory
     * @param GiNof\Persistence\PersisterInterface $persister
     * @param Buzz\Client\ClientInterface $client
     * @param Buzz\Message\Request $request
     * @param Buzz\Message\Response $response
     */
    function let($requestFactory, $responseFactory, $notificationFactory, $persister, $client, $request, $response)
    {
        $this->beConstructedWith($client, $requestFactory, $responseFactory, $notificationFactory, $persister);
        $requestFactory->createRequest(ANY_ARGUMENTS)->willReturn($request);
        $responseFactory->createResponse()->willReturn($response);
    }

    /**
     * @param StdClass $notification1
     * @param StdClass $notification2
     */
    function it_should_query_only_if_modification_has_been_detected($notification1, $notification2, $request, $response, $notificationFactory)
    {
        $json = <<<JSON
[
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

        $response->getStatusCode()->willReturn(200);
        $response->getContent()->willReturn($json);
        $notifications = [$notification1, $notification2];
        $notificationFactory->createNotifications(ANY_ARGUMENTS)->willReturn($notifications);

        $this->fetch('1234')->shouldReturn($notifications);
    }

    /**
     * @param StdClass $notification1
     * @param StdClass $notification2
     */
    function it_should_persist_notifications_if_new_notifications_have_been_fetched($notification1, $notification2, $response, $notificationFactory, $persister)
    {
        $json = <<<JSON
[
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
        $response->getContent()->willReturn($json);
        $response->getStatusCode()->willReturn(200);
        $notifications = [$notification1, $notification2];
        $notificationFactory->createNotifications(ANY_ARGUMENTS)->willReturn($notifications);
        $persister->save($notifications)->shouldBeCalled();

        $this->fetch('some token');
    }

    function it_should_not_persist_notifications_if_no_new_notification($response, $persister)
    {
        $response->getStatusCode()->willReturn(304);
        $persister->save(ANY_ARGUMENTS)->shouldNotBeCalled();

        $this->fetch('some token');
    }

    function it_should_return_null_if_no_new_notifications($response, $persister)
    {
        $response->getStatusCode()->willReturn(304);

        $this->fetch('some token')->shouldReturn(null);
    }
}
