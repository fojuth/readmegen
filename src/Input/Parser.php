<?php namespace ReadmeGen\Input;

use Ulrichsg\Getopt\Getopt;
use Ulrichsg\Getopt\Option;

class Parser
{
    /**
     * @var Getopt
     */
    protected $handler;
    protected $input;
    
    public function __construct()
    {
        $this->handler = new Getopt(array(
            new Option('r', 'release', Getopt::REQUIRED_ARGUMENT),
            new Option('f', 'from', Getopt::REQUIRED_ARGUMENT),
            new Option('t', 'to', Getopt::OPTIONAL_ARGUMENT),
            new Option('b', 'break', Getopt::OPTIONAL_ARGUMENT),
        ));
    }

    public function setInput($input)
    {
        $inputArray = explode(' ', $input);
        
        array_shift($inputArray);
        
        $this->input = join(' ', $inputArray);
    }

    public function parse()
    {
        $this->handler->parse($this->input);

        $output = $this->handler->getOptions();

        if (false === isset($output['from'])) {
            throw new \BadMethodCallException('The --from argument is required.');
        }

        if (false === isset($output['release'])) {
            throw new \BadMethodCallException('The --release argument is required.');
        }
        
        return $this->handler;
    }
}
