<?php namespace ReadmeGen\Log;

use ReadmeGen\Output\Format\FormatInterface;

class Decorator
{
    /**
     * @var FormatInterface
     */
    protected $formatter;

    public function __construct(FormatInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function setLog(array $log)
    {
        $this->formatter->setLog($log);

        return $this;
    }

    public function setIssueTrackerUrlPattern($pattern)
    {
        $this->formatter->setIssueTrackerUrlPattern($pattern);

        return $this;
    }

    public function decorate()
    {
        return $this->formatter->decorate();
    }
}
