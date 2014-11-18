<?php

namespace spec\ReadmeGen\Vcs\Type;

use PhpSpec\ObjectBehavior;
use ReadmeGen\Shell;

class GitSpec extends ObjectBehavior
{
    
    function it_should_parse_a_git_log(Shell $shell)
    {
        $log = "Foo bar.{{MSG_SEPARATOR}}\nDummy message.{{MSG_SEPARATOR}}";
        
        $shell->run('git log --pretty=format:"%s{{MSG_SEPARATOR}}%b"')->willReturn($log);
        
        $this->setShellRunner($shell);
        
        $this->parse()->shouldReturn(array(
            'Foo bar.',
            'Dummy message.',
        ));
    }
    
}
