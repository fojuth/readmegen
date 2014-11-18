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
    
}
