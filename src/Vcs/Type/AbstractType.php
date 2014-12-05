<?php namespace ReadmeGen\Vcs\Type;

use ReadmeGen\Shell;

abstract class AbstractType implements TypeInterface
{
    const MSG_SEPARATOR = '{{MSG_SEPARATOR}}';
    
    /**
     * Shell script runner.
     *
     * @var Shell
     */
    protected $shell;

    /**
     * Input arguments.
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * Input arguments.
     *
     * @var array
     */
    protected $options = array();

    /**
     * Shell command executing class setter.
     * 
     * @param Shell $shell
     */
    public function setShellRunner(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * Runs the shell command and returns the result.
     *
     * @param $command
     * @return string
     */
    protected function runCommand($command)
    {
        return $this->shell->run($command);
    }

    public function setOptions(array $options = null)
    {
        $this->options = $options;
    }

    public function setArguments(array $arguments = null)
    {
        $this->arguments = $arguments;
    }

    public function hasOption($option)
    {
        return in_array($option, $this->options);
    }
    
    public function getOptions()
    {
        return $this->options;
    }

    public function hasArgument($argument)
    {
        return isset($this->arguments[$argument]);
    }

    public function getArgument($argument)
    {
        return $this->arguments[$argument];
    }
    
    public function getArguments()
    {
        return $this->arguments;
    }
    
}
