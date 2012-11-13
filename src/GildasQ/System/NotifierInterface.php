<?php

namespace GildasQ\System;

interface NotifierInterface
{
    public function notify($application, $summary, $body = '');
}
