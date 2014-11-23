<?php

namespace spec\ReadmeGen\Input;

use PhpSpec\ObjectBehavior;

class ParserSpec extends ObjectBehavior
{
    function it_should_fetch_options()
    {
        $this->setInput('someDummyContent --f=foo --to=bar');
        
        $result = $this->parse();

        $result['from']->shouldReturn('foo');
        $result['f']->shouldReturn('foo');
        $result['to']->shouldReturn('bar');
        $result['t']->shouldReturn('bar');
    }
}
