<?php namespace ReadmeGen\Output\Format;

interface FormatInterface {

    public function setLog(array $log);

    public function setIssueTrackerUrlPattern($pattern);

    /**
     * Decorates the output (e.g. adds linkgs to the issue tracker)
     *
     * @return self
     */
    public function decorate();

    /**
     * Returns a write-ready log.
     *
     * @return array
     */
    public function generate();

    /**
     * Returns the output filename.
     *
     * @return string
     */
    public function getFileName();

} 