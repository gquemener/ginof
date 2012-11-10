<?php

namespace GildasQ\Github;

use Buzz\Message\Response;

class ResponseFactory
{
    public function createResponse()
    {
        return new Response();
    }
}
