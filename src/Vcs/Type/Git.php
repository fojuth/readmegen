<?php namespace ReadmeGen\Vcs\Type;

class Git extends AbstractType
{
    const MSG_SEPARATOR = '{{MSG_SEPARATOR}}';
    
    /**
     * Parses the log.
     * 
     * @return array
     */
    public function parse()
    {
        return array_filter(array_map('trim', explode(self::MSG_SEPARATOR, $this->getLog())));
    }
    
    protected function getCommand()
    {
        return 'git log --pretty=format:"%s{{MSG_SEPARATOR}}%b"';
    }
    
    protected function getLog()
    {
        return $this->runCommand($this->getCommand());
    }

}
