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
            new Option(null, 'from', Getopt::REQUIRED_ARGUMENT),
            new Option(null, 'to', Getopt::OPTIONAL_ARGUMENT),
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
        
        return $this->handler;
    }
}
