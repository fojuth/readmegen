<?php

namespace spec\ReadmeGen\Output\Format;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MdSpec extends ObjectBehavior
{
    protected $issueTrackerUrl = 'http://some.issue.tracker.com/show/';
    protected $issueTrackerPattern = 'http://some.issue.tracker.com/show/\1';

    function it_should_add_links_to_the_issue_tracker()
    {
        $log = array(
            'Features' => array(
                'bar #123 baz',
                'dummy feature',
            ),
            'Bugfixes' => array(
                'some bugfix (#890)',
            ),
        );

        $result = array(
            'Features' => array(
                "bar [#123]({$this->issueTrackerUrl}123) baz",
                'dummy feature',
            ),
            'Bugfixes' => array(
                "some bugfix ([#890]({$this->issueTrackerUrl}890))",
            ),
        );

        $this->setLog($log);
        $this->setIssueTrackerUrlPattern($this->issueTrackerPattern);
        $this->decorate()->shouldReturn($result);
    }
}
