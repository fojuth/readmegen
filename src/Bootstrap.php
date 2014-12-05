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
        // Set up the input parser
        $inputParser = new Parser();
        $inputParser->setInput(join(' ', $input));

        // Parse the input
        try {
            $input = $inputParser->parse();
        } catch (\BadMethodCallException $e) {
            die($e->getMessage());
        }

        // Run the whole process
        $this->run($input->getOptions());
    }

    /**
     * Generates the output file.
     *
     * @param array $options
     */
    public function run(array $options)
    {
        $this->generator = new ReadmeGen(new ConfigLoader());

        // Extract useful log entries
        $logGrouped = $this->generator->setExtractor(new Extractor())
            ->extractMessages($this->getLog($options));

        $config = $this->generator->getConfig();

        $formatterClassName = '\ReadmeGen\Output\Format\\' . $config['format'];

        // Create the output formatter
        $formatter = new $formatterClassName;

        $formatter->setRelease($options['release'])
            ->setDate(new \DateTime());

        // Pass decorated log entries to the generator
        $this->generator->setDecorator(new Decorator($formatter))
            ->getDecoratedMessages($logGrouped);

        $writer = new Writer($formatter);

        // If present, respect the breakpoint in the existing output file
        if (true === isset($options['break'])) {
            $writer->setBreak($options['break']);
        }

        // Write the output
        $this->generator->setOutputWriter($writer)
            ->writeOutput();
    }

    /**
     * Returns the parsed log.
     *
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
