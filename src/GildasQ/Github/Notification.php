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
        $this->subjectUrl = str_replace(
            'https://api.github.com/repos',
            'https://www.github.com',
            $subjectUrl
        );

        return $this;
    }

    public function getBody()
    {
        return sprintf(
            '[%s] %s' . PHP_EOL .' %s',
            $this->getRepositoryFullName(),
            $this->getSubjectTitle(),
            $this->getSubjectUrl()
        );
    }

    public function __toString()
    {
        return $this->getBody();
    }
}
