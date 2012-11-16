<?php

namespace spec\GiNof\Github;

use PHPSpec2\ObjectBehavior;

class ResponseFactory extends ObjectBehavior
{
    function it_should_create_a_response()
    {
        $this->createResponse()->shouldBeAnInstanceOf('Buzz\Message\Response');
    }
}
