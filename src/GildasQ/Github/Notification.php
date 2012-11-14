<?php

namespace GildasQ\Github;

class Notification
{
    private $repositoryFullName;
    private $subjectTitle;
    private $subjectUrl;
    private $subjectType;

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

    public function getSubjectType()
    {
        return $this->subjectType;
    }

    public function setSubjectType($subjectType)
    {
        $this->subjectType = $subjectType;
    }

    public function getBody()
    {
        return sprintf(
            '[%s] %s',
            $this->getRepositoryFullName(),
            $this->getSubjectTitle()
        );
    }

    public function __toString()
    {
        return $this->getBody();
    }
}
