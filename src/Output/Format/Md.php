<?php namespace ReadmeGen\Output\Format;

use ReadmeGen\Vcs\Type\AbstractType as VCS;

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

    public function setIssueTrackerUrlPattern($pattern)
    {
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

    protected function injectLinks(&$entry)
    {
        $entry = preg_replace('/#(\d+)/', "[#\\1]({$this->pattern})", $entry);
    }

    public function generate()
    {
        $log = array();

        foreach ($this->log as $header => &$entries) {
            $log[] = sprintf("\n#### %s", $header);

            foreach ($entries as &$line) {
                $message = explode(VCS::MSG_SEPARATOR, $line);

                $log[] = sprintf("* %s", trim($message[0]));

                if (true === isset($message[1])) {
                    $log[] = sprintf("\n  %s", trim($message[1]));
                }
            }
        }

        $log[] = "\n---";

        return $log;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

}
