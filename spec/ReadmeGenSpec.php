<?php

namespace spec\ReadmeGen {
    
    use PhpSpec\ObjectBehavior;
    use \ReadmeGen\Config\Loader as ConfigLoader;

    class ReadmeGenSpec extends ObjectBehavior
    {

        protected $dummyConfigFile = 'dummy_config.yaml';
        protected $dummyConfig = "vcs: dummyvcs\nfoo: bar\nmessage_groups:\n  Features:\n    - feat\n    - feature\n  Bugfixes:\n    - fix\n    - bugfix";
        protected $dummyConfigArray = array(
            'vcs' => 'dummyvcs',
            'foo' => 'bar',
            'message_groups' => array(
                'Features' => array(
                    'feat', 'feature'
                ),
                'Bugfixes' => array(
                    'fix', 'bugfix'
                ),
            ),
        );
        protected $badConfigFile = 'bad_config.yaml';
        protected $badConfig = "vcs: nope\nfoo: bar";

        function let()
        {
            file_put_contents($this->dummyConfigFile, $this->dummyConfig);
            file_put_contents($this->badConfigFile, $this->badConfig);

            $this->beConstructedWith(new ConfigLoader, $this->dummyConfigFile);
        }

        function letgo()
        {
            unlink($this->dummyConfigFile);
            unlink($this->badConfigFile);
        }

        function it_should_load_default_config()
        {
            $this->getConfig()->shouldBe($this->dummyConfigArray);
        }

        function it_loads_the_correct_vcs_parser()
        {
            $config = $this->getConfig();

            $config['vcs']->shouldBe('dummyvcs');

            $this->getParser()->shouldHaveType('\ReadmeGen\Vcs\Parser');
            $this->getParser()->getVcsParser()->shouldHaveType('\ReadmeGen\Vcs\Type\Dummyvcs');
        }

        function it_throws_exception_when_trying_to_load_nonexisting_vcs_parser()
        {
            $this->beConstructedWith(new ConfigLoader, $this->badConfigFile);

            $this->shouldThrow('\InvalidArgumentException')->during('getParser');
        }

    }
    
}

/**
 * Dummy VCS type class used by ReadmeGen during tests.
 */
namespace ReadmeGen\Vcs\Type {
    
    class Dummyvcs extends \ReadmeGen\Vcs\Type\AbstractType {
        
        public function parse()
        {
            return array();
        }
        
    }
    
}
