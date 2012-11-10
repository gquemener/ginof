<?php

namespace spec\GildasQ\Command;

use PHPSpec2\ObjectBehavior;

class UpdateNotificationCommand extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Symfony\Component\Console\Command\Command');
    }
}
