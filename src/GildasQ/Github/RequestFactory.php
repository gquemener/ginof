<?php

namespace GildasQ\Github;

use Buzz\Message\Request;

class RequestFactory
{
    public function createRequest($apiToken, $lastModified = null)
    {
        $request = new Request('GET', '/notifications', 'https://api.github.com');
        $request->addHeader(sprintf('Authorization: token %s', $apiToken));

        if ($lastModified) {
            $request->addHeader(sprintf('If-Modified-Since: %s', $lastModified));
        }

        return $request;
    }
}
