<?php

namespace spec\ReadmeGen;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use \ReadmeGen\Config\Loader as ConfigLoader;

class ReadmeGenSpec extends ObjectBehavior
{

    protected $dummyConfigFile = 'dummy_config.yaml';
    protected $dummyConfig = "vcs: git\nfoo: bar";
    protected $dummyConfigArray = array(
        'vcs' => 'git',
        'foo' => 'bar',
    );

    function let()
    {
        file_put_contents($this->dummyConfigFile, $this->dummyConfig);
        
        $this->beConstructedWith(new ConfigLoader, $this->dummyConfigFile);
    }

    function letgo()
    {
        unlink($this->dummyConfigFile);
    }
    
    function it_should_load_default_config()
    {
        $this->run();
        
        $this->getConfig()->shouldBe($this->dummyConfigArray);
    }
    
}
