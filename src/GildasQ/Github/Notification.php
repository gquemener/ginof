<?php

namespace GildasQ\Github;

class Notification
{
    private $repositoryFullName;
    private $subjectTitle;
    private $subjectUrl;

    public function getRepositoryFullName()
    {
        return $this->repositoryFullName;
    }

    public function setRepositoryFullName($repositoryFullName)
    {
        $this->repositoryFullName = $repositoryFullName;

        return $this;
    }

    public function getSubjectTitle()
    {
        return $this->subjectTitle;
    }

    public function setSubjectTitle($subjectTitle)
    {
        $this->subjectTitle = $subjectTitle;

        return $this;
    }

    public function getSubjectUrl()
    {
        return $this->subjectUrl;
    }

    public function setSubjectUrl($subjectUrl)
    {
        $this->subjectUrl = $subjectUrl;

        return $this;
    }

    public function __toString()
    {
        return sprintf(
            '[%s] %s',
            $this->getRepositoryFullName(),
            $this->getSubjectTitle()
        );
    }
}
