<?php

namespace GiNof\System;

interface NotifierInterface
{
    public function notify($application, $summary, $body = '');
}
