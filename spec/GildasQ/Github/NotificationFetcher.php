<?php

namespace spec\GildasQ\Github;

use PHPSpec2\ObjectBehavior;

class NotificationFetcher extends ObjectBehavior
{
    /**
     * @param GildasQ\Github\RequestFactory $requestFactory
     * @param GildasQ\Github\ResponseFactory $responseFactory
     * @param GildasQ\Github\NotificationFactory $notificationFactory
     * @param GildasQ\Persistence\PersisterInterface $persister
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

        $response->isOk()->willReturn(true);
        $response->getContent()->willReturn($json);
        $notifications = [$notification1, $notification2];
        $notificationFactory->createNotifications(ANY_ARGUMENTS)->willReturn($notifications);

        $this->fetch('1234')->shouldReturn($notifications);
    }

    function it_should_not_query_if_no_new_notification($response)
    {
        $response->isOk()->willReturn(false);
        $this->fetch('1234')->shouldReturn(null);
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
        $response->isOk()->willReturn(true);
        $notifications = [$notification1, $notification2];
        $notificationFactory->createNotifications(ANY_ARGUMENTS)->willReturn($notifications);
        $persister->save($notifications)->shouldBeCalled();

        $this->fetch('some token');
    }

    function it_should_not_persist_notifications_if_no_new_notification($response, $persister)
    {
        $response->isOk()->willReturn(false);
        $persister->save(ANY_ARGUMENTS)->shouldNotBeCalled();

        $this->fetch('some token');
    }

    function it_should_read_cached_notifications_if_no_new_notification($response, $persister)
    {
        $response->isOk()->willReturn(false);
        $persister->retrieve()->shouldBeCalled();

        $this->fetch('some token');
    }
}
