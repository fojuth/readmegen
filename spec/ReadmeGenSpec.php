<?php

namespace spec\ReadmeGen {

    use PhpSpec\ObjectBehavior;
    use \ReadmeGen\Config\Loader as ConfigLoader;
    use \ReadmeGen\Shell;
    use \ReadmeGen\Vcs\Type\Git;
    use \ReadmeGen\Log\Extractor;
    use \ReadmeGen\Output\Format\Md;

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
        protected $gitConfigFile = 'git_config.yaml';
        protected $gitConfig = "vcs: git\nmessage_groups:\n  Features:\n    - feat\n    - feature\n  Bugfixes:\n    - fix\n    - bugfix\nformat: md\nissue_tracker_pattern: http://issue.tracker.com/\\1";

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

        function it_runs_the_whole_process(Shell $shell)
        {
            file_put_contents($this->gitConfigFile, $this->gitConfig);

            $shell->run(sprintf('git log --pretty=format:"%%s%s%%b" 1.2.3..4.0.0', Git::MSG_SEPARATOR))->willReturn($this->getLogAsString());

            $this->beConstructedWith(new ConfigLoader, $this->gitConfigFile);

            $this->getParser()->getVcsParser()->shouldHaveType('\ReadmeGen\Vcs\Type\Git');
            $this->getParser()->setArguments(array(
                'from' => '1.2.3',
                'to' => '4.0.0',
            ));
            $this->getParser()->setShellRunner($shell);

            $log = $this->getParser()->parse();

            $this->setExtractor(new Extractor());
            $logGrouped = $this->extractMessages($log)->shouldReturn(array(
                'Features' => array(
                    'bar baz #123',
                    'dummy feature',
                    'lol',
                ),
                'Bugfixes' => array(
                    'some bugfix',
                )
            ));

            $this->setDecorator(new Md());
            $this->getDecoratedMessages($logGrouped)->shouldReturn(array(
                'Features' => array(
                    'bar baz [#123](http://issue.tracker.com/123)',
                    'dummy feature',
                    'lol',
                ),
                'Bugfixes' => array(
                    'some bugfix',
                )
            ));
        }

        protected function getLogAsString()
        {
            $log = array(
                'foo',
                'feature: bar baz #123',
                'nope',
                'feature: dummy feature',
                'feat: lol',
                'also nope',
                'fix: some bugfix',
            );

            return join(Git::MSG_SEPARATOR."\n", $log).Git::MSG_SEPARATOR."\n";
        }

    }

}

/**
 * Dummy VCS type class used by ReadmeGen during tests.
 */
namespace ReadmeGen\Vcs\Type {

    class Dummyvcs extends \ReadmeGen\Vcs\Type\AbstractType
    {

        public function parse()
        {
            return array();
        }

    }

}
