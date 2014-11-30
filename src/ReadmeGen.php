<?php namespace ReadmeGen;

use ReadmeGen\Config\Loader as ConfigLoader;
use ReadmeGen\Vcs\Parser;
use ReadmeGen\Log\Extractor;
use ReadmeGen\Log\Decorator;
use ReadmeGen\Output\Writer as OutputWriter;

class ReadmeGen
{

    /**
     * Path to the default config file.
     */
    protected $defaultConfigPath = 'readmegen.yml';

    /**
     * Config loader.
     *
     * @var ConfigLoader
     */
    protected $configLoader;

    /**
     * Default config.
     *
     * @var array
     */
    protected $defaultConfig = array();

    /**
     * Final config - default config merged with local config.
     *
     * @var array
     */
    protected $config = array();

    /**
     * Parser instance.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * Message extractor.
     *
     * @var Extractor
     */
    protected $extractor;

    /**
     * Message decorator.
     *
     * @var \ReadmeGen\Log\Decorator
     */
    protected $decorator;

    protected $output;

    public function __construct(ConfigLoader $configLoader, $defaultConfigPath = null)
    {
        $this->defaultConfigPath = realpath(__DIR__.'/../'.$this->defaultConfigPath);

        $configPath = (false === empty($defaultConfigPath) ? $defaultConfigPath : $this->defaultConfigPath);

        $this->configLoader = $configLoader;
        $this->defaultConfig = $this->configLoader->get($configPath);

        /**
         * @todo This should be actually loading a local config file and merge it with the default config
         * @see \ReadmeGen\Config\Loader::get() - this method can merge the configs
         */
        $this->config = $this->defaultConfig;
    }

    /**
     * Returns the config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the parser.
     *
     * @return Parser
     * @throws \InvalidArgumentException When the VCS parser class does not exist.
     */
    public function getParser()
    {
        if (true === empty($this->parser)) {
            $typeParserClassName = sprintf('\ReadmeGen\Vcs\Type\%s', ucfirst($this->config['vcs']));

            if (false === class_exists($typeParserClassName)) {
                throw new \InvalidArgumentException(sprintf('Class "%s" does not exist', $typeParserClassName));
            }

            $this->parser = new Parser(new $typeParserClassName());
        }

        return $this->parser;
    }

    public function setExtractor(Extractor $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }

    public function extractMessages(array $log = null)
    {
        if (true === empty($log)) {
            return array();
        }

        $this->extractor->setMessageGroups($this->config['message_groups']);

        return $this->extractor->setLog($log)
            ->extract();
    }

    /**
     *
     * phpspec failed to properly resolve the aliased version of this interface.
     *
     * @param \ReadmeGen\Log\Decorator $decorator
     * @return $this
     */
    public function setDecorator(Decorator $decorator)
    {
        $this->decorator = $decorator;

        return $this;
    }

    public function getDecoratedMessages(array $log = null)
    {
        if (true === empty($log)) {
            return array();
        }

        return $this->decorator->setLog($log)
            ->setIssueTrackerUrlPattern($this->config['issue_tracker_pattern'])
            ->decorate();
    }

    public function setOutputWriter(OutputWriter $output)
    {
        $this->output = $output;

        return $this;
    }

    public function writeOutput()
    {
        return $this->output->write();
    }
}
