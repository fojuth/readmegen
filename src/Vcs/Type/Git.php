<?php namespace ReadmeGen\Vcs\Type;

use ReadmeGen\Shell;
use ReadmeGen\Vcs\Type\TypeInterface;

class Git implements TypeInterface
{
    const MSG_SEPARATOR = '{{MSG_SEPARATOR}}';
    
    /**
     * Shell script runner.
     *
     * @var Shell
     */
    protected $shell;
    
    /**
     * Parses the log.
     * 
     * @return array
     */
    public function parse()
    {
        $log = $this->runCommand($this->getCommand());
        $logArray = explode(self::MSG_SEPARATOR, $log);
        
        return array_filter(array_map('trim', $logArray));
    }

    /**
     * Shell command executing class setter.
     * 
     * @param Shell $shell
     */
    public function setShellRunner(Shell $shell)
    {
        $this->shell = $shell;
    }
    
    protected function runCommand($command)
    {
        return $this->shell->run($command);
    }
    
    protected function getCommand()
    {
        return 'git log --pretty=format:"%s{{MSG_SEPARATOR}}%b"';
    }
    
}
