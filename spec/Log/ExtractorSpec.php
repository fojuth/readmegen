<?php

namespace spec\ReadmeGen\Log;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtractorSpec extends ObjectBehavior
{

    function it_extracts_messages_from_log()
    {
        $log = array(
            'foo',
            'hashcommitx' => 'feature: bar baz',
            'nope',
            'hashcommity' => 'feature: dummy feature',
            'hashcommitz' => 'feat: lol',
            'also nope',
            'hashcommitw' => 'fix: some bugfix',
        );

        $messageGroups = array(
            'Features' => array('feature', 'feat'),
            'Bugfixes' => array('bugfix', 'fix'),
            'Docs' => array('docs'),
        );

        $result = array(
            'Features' => array(
                'hashcommitx' => 'bar baz',
                'hashcommity' => 'dummy feature',
                'hashcommitz' => 'lol',
            ),
            'Bugfixes' => array(
                'hashcommitw' => 'some bugfix',
            ),
        );

        $this->setLog($log);
        $this->setMessageGroups($messageGroups);

        $this->extract()->shouldReturn($result);
    }

}
