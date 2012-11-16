<?php

namespace GiNof\Persistence;

/**
 * Provides a mean to persist and retrieve data
 */
interface PersisterInterface
{
    /**
     * @param mixed $data the data to persist
     *
     * @return mixed
     */
    public function save($data);

    /**
     * @return array|null the data that were previously persisted
     */
    public function retrieve();

    /**
     * @return string|null the last time the data were persisted (format: http://www.faqs.org/rfcs/rfc2822.html)
     */
    public function getLastModified();
}
