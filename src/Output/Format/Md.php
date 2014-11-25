<?php namespace ReadmeGen\Output\Format;

use ReadmeGen\Output\Format\FormatInterface;

class Md implements FormatInterface
{
    /**
     * @var array
     */
    protected $log;

    protected $pattern;

    protected $fileName = 'README.md';

    public function setLog(array $log = null)
    {
        $this->log = $log;

        return $this;
    }

    public function setIssueTrackerUrlPattern($pattern){
        $this->pattern = $pattern;

        return $this;
    }

    public function decorate()
    {
        foreach ($this->log as &$entries) {
            array_walk($entries, array($this, 'injectLinks'));
        }

        return $this->log;
    }

    protected function injectLinks(&$entry){
        $entry = preg_replace('/#(\d+)/', "[#\\1]({$this->pattern})", $entry);
    }

    public function generate(){
        return 'foo';
    }

    public function getFileName(){
        return $this->fileName;
    }

}
