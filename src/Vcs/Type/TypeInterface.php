<?php namespace ReadmeGen\Vcs\Type;

use ReadmeGen\Shell;

interface TypeInterface
{
    
    /**
     * Parses the log.
     * 
     * @return array
     */
    public function parse();
    
    /**
     * Shell command executing class setter.
     * 
     * @param Shell $shell
     */
    public function setShellRunner(Shell $shell);

    public function setOptions(array $options = null);

    public function setArguments(array $arguments = null);

    public function hasOption($option);
    
    public function hasArgument($argument);
    
    public function getArgument($argument);
    
}
