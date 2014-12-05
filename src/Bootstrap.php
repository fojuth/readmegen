<?php namespace ReadmeGen;

use ReadmeGen\Input\Parser;
use ReadmeGen\Config\Loader as ConfigLoader;
use ReadmeGen\Log\Extractor;
use ReadmeGen\Log\Decorator;
use ReadmeGen\Output\Writer;

class Bootstrap
{
    protected $generator;

    public function __construct(array $input)
    {
        $inputParser = new Parser();
        $inputParser->setInput(join(' ', $input));

        try {
            $input = $inputParser->parse();
        } catch (\BadMethodCallException $e) {
            die($e->getMessage());
        }

        $this->run($input->getOptions());
    }

    public function run(array $options)
    {
        $this->generator = new ReadmeGen(new ConfigLoader());

        $logGrouped = $this->generator->setExtractor(new Extractor())
            ->extractMessages($this->getLog($options));

        $config = $this->generator->getConfig();

        $formatterClassName = '\ReadmeGen\Output\Format\\' . $config['format'];

        $formatter = new $formatterClassName;

        $formatter->setRelease($options['release'])
            ->setDate(new \DateTime());

        $this->generator->setDecorator(new Decorator($formatter))
            ->getDecoratedMessages($logGrouped);

        $writer = new Writer($formatter);

        if (true === isset($options['break'])) {
            $writer->setBreak($options['break']);
        }

        $this->generator->setOutputWriter($writer)
            ->writeOutput();
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function getLog(array $options)
    {
        return $this->generator->getParser()
            ->setArguments($options)
            ->setShellRunner(new Shell)
            ->parse();
    }

}
