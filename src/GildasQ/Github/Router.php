<?php

namespace GildasQ\Github;

/**
 * Github api response only provide api url
 * This class allows to convert them into frontend url
 */
class Router
{
    public function generateUrl($subjectUrl, $subjectType)
    {
        switch ($subjectType) {
            case 'PullRequest':
                return $this->createPullRequestUrl($subjectUrl, $subjectType);

            case 'Issue':
                return $this->createIssueUrl($subjectUrl, $subjectType);

            default:
                throw new \RuntimeException(sprintf('Cannot generate github url from subject url "%s" and type "%s"', $subjectUrl, $subjectType));
        }
    }

    private function createPullRequestUrl($subjectUrl, $subjectType)
    {
        $matches = $this->getMatches('#https?://api.github.com/repos/(.*)/(.*)/pulls/(\d+)#', $subjectUrl);

        return sprintf('https://www.github.com/%s/%s/pull/%d', $matches[1], $matches[2], $matches[3]);
    }

    private function createIssueUrl($subjectUrl, $subjectType)
    {
        $matches = $this->getMatches('#https?://api.github.com/repos/(.*)/(.*)/issues/(\d+)#', $subjectUrl);

        return sprintf('https://www.github.com/%s/%s/issues/%d', $matches[1], $matches[2], $matches[3]);
    }

    private function getMatches($urlPattern, $subjectUrl)
    {
        if (!preg_match($urlPattern, $subjectUrl, $matches)) {
            throw new \RuntimeException(sprintf('Cannot parse url "%s" using pattern "%s"', $subjectUrl, $urlPattern));
        }

        return $matches;
    }
}
