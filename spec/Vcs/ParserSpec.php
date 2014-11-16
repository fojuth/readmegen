<?php

namespace spec\ReadmeGen\Vcs;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReadmeGen\Vcs\Type\TypeInterface;

class ParserSpec extends ObjectBehavior
{
    function let(TypeInterface $vcs)
    {
        $this->beConstructedWith($vcs);
    }
    
    function it_should_parse_the_vcs_log_into_an_array(TypeInterface $vcs)
    {
        $returnData = array(
            'foo' => 'bar',
            'baz' => 42,
        );
        
        $vcs->parse()->willReturn($returnData);
        
        $this->parse()->shouldBe($returnData);
    }
}
