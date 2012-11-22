<?php

namespace GiNof\Github;

use Buzz\Message\Request;
use Buzz\Message\RequestInterface;

class RequestFactory
{
    public function createRequest($apiToken, $force = false, $write = false, $lastModified = null)
    {
        $request = new Request(RequestInterface::METHOD_GET, '/notifications', 'https://api.github.com');
        if ($write) {
            $request->setMethod(RequestInterface::METHOD_PUT);
            $request->setContent(json_encode(['read' => true]));
        }


        $request->addHeader(sprintf('Authorization: token %s', $apiToken));

        if ($lastModified && !$force) {
            $request->addHeader(sprintf('If-Modified-Since: %s', $lastModified));
        }

        return $request;
    }
}
