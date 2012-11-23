<?php

namespace spec\GiNof\Github;

use PHPSpec2\ObjectBehavior;

class Router extends ObjectBehavior
{
    function it_should_generate_url_for_pull_request()
    {
        $this
            ->generateUrl('https://api.github.com/repos/user/test-repo/pulls/20','PullRequest')
            ->shouldReturn('https://www.github.com/user/test-repo/pull/20')
        ;
    }

    function it_should_generate_url_for_issue()
    {
        $this
            ->generateUrl('https://api.github.com/repos/user/test-repo/issues/20','Issue')
            ->shouldReturn('https://www.github.com/user/test-repo/issues/20')
        ;
    }

    function it_should_generate_url_for_commit()
    {
        $this
            ->generateUrl('https://api.github.com/repos/symfony/symfony/commits/b3c3e89462d058afc35c59cdd71a4b6c4b6cdbcc','Commit')
            ->shouldReturn('https://www.github.com/symfony/symfony/commit/b3c3e89462d058afc35c59cdd71a4b6c4b6cdbcc')
        ;
    }

    function its_generateUrl_should_throw_exception_if_subject_url_is_invalid()
    {
        $this->shouldThrow('RuntimeException')->duringGenerateUrl('http://www.google.com', 'PullRequest');
    }

    function its_generateUrl_should_throw_exception_if_subject_type_is_invalid()
    {
        $this->shouldThrow('RuntimeException')->duringGenerateUrl('https://api.github.com/repos/user/test-repo/pulls/20', 'some fancy subject type');
    }
}
