<?php

namespace spec\ReadmeGen\Log;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DecoratorSpec extends ObjectBehavior
{
    protected $issueTrackerUrl = 'http://some.issue.tracker.com/show/';

    function it_adds_links_to_issues()
    {
        $log = array(
            'Features' => array(
                'bar #123 baz',
                'dummy feature',
                'lol #404',
            ),
            'Bugfixes' => array(
                'some bugfix #890',
            ),
        );

        $this->setLog($log);
        $this->setIssueLinkPattern($this->issueTrackerUrl.'\1');

        $this->decorate()->shouldReturn();
    }
}
