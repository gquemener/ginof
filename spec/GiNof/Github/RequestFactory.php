<?php

namespace spec\GiNof\Github;

use PHPSpec2\ObjectBehavior;

class RequestFactory extends ObjectBehavior
{
    function it_should_create_a_GET_request()
    {
        $request = $this->createRequest('1234');

        $request->getMethod()->shouldBe('GET');
    }

    function it_should_create_a_PUT_request()
    {
        $request = $this->createRequest('1234', false, true);

        $request->getMethod()->shouldBe('PUT');
    }

    function it_should_create_a_request_to_github_notification_api()
    {
        $request = $this->createRequest('1234');

        $request->getHost()->shouldBe('https://api.github.com');
        $request->getResource()->shouldBe('/notifications');
    }

    function it_should_create_a_request_for_the_specified_api_token()
    {
        $this->createRequest('1234')->shouldBeAnInstanceOf('Buzz\Message\Request');
    }

    function it_should_create_a_get_notifications_request()
    {
        $this->createRequest('1234')->getHeader('Authorization')->shouldReturn('token 1234');
    }

    function it_should_create_a_last_modified_request()
    {
        $request = $this->createRequest('1234', false, false, 'Thu, 25 Oct 2012 15:16:27 GMT');
        $request->getHeader('Authorization')->shouldReturn('token 1234');
        $request->getHeader('If-Modified-Since')->shouldReturn('Thu, 25 Oct 2012 15:16:27 GMT');
    }

    function it_should_not_use_the_if_modified_since_header_if_we_force_not_to()
    {
        $request = $this->createRequest('1234', true, false, 'Thu, 25 Oct 2012 15:16:27 GMT');
        $request->getHeader('Authorization')->shouldReturn('token 1234');
        $request->getHeader('If-Modified-Since')->shouldReturn(null);
    }
}
