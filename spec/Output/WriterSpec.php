<?php namespace spec\ReadmeGen\Output;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReadmeGen\Output\Format\FormatInterface;

class WriterSpec extends ObjectBehavior
{
    protected $fileName = 'foobar.md';

    function let(FormatInterface $formatter)
    {
        $this->beConstructedWith($formatter);
    }

    function it_should_write_log_output_to_a_file(FormatInterface $formatter)
    {
        $this->fileName = 'foobar.md';
        $logContent = array(
            'Features:',
            '- foo',
            '- bar',
        );

        $formatter->generate()->willReturn($logContent);
        $formatter->getFileName()->willReturn($this->fileName);

        $this->write();

        if (false === file_exists($this->fileName)) {
            throw new \Exception(sprintf('File %s has not been created.', $this->fileName));
        }

        $content = file_get_contents($this->fileName);

        if (true === empty($content)) {
            throw new \Exception(sprintf('File %s is empty.', $this->fileName));
        }

        if (trim($content) !== join("\n", $logContent)) {
            throw new \Exception('File content differs from expectations.');
        }
    }

    function letgo() {
        @ unlink($this->fileName);
    }
}
