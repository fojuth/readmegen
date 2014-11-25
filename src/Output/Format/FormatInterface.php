<?php namespace ReadmeGen\Output\Format;

interface FormatInterface {

    public function setLog(array $log);

    public function setIssueTrackerUrlPattern($pattern);

    public function decorate();

} 