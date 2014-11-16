<?php namespace ReadmeGen\Vcs\Type;

interface TypeInterface
{
    
    /**
     * Parses the log.
     * 
     * @return array
     */
    public function parse();
    
}
