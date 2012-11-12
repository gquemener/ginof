<?php

namespace spec\GildasQ\Persistence;

use PHPSpec2\ObjectBehavior;

class FileSystemPersister extends ObjectBehavior
{
    /**
     * @param stdClass $data
     */
    function it_should_write_to_file_system($data)
    {
        $this->setPath('some path');

        $this->save($data);
    }

    /**
     * @param stdClass $data
     */
    function it_should_not_write_if_no_path_is_defined($data)
    {
        $this->shouldThrow('RuntimeException')->duringSave($data);
    }

    function it_should_read_from_file_system()
    {
        $this->setPath('some path');

        $this->retrieve();
    }

    function it_should_not_read_if_path_does_not_exist()
    {
        $this->retrieve()->shouldReturn([]);
    }
}
