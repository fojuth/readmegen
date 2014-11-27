<?php namespace ReadmeGen;

use ReadmeGen\Input\Parser;
use ReadmeGen\Config\Loader as ConfigLoader;
use ReadmeGen\Log\Extractor;

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

        $log = $this->generator->getParser()
            ->setArguments($options)
            ->setShellRunner(new Shell)
            ->parse();

        $logGrouped = $this->generator->setExtractor(new Extractor())
            ->extractMessages($log);

        var_dump($logGrouped);
        die;
    }

} 