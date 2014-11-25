<?php namespace ReadmeGen\Output;

use ReadmeGen\Output\Format\FormatInterface;

class Writer
{
    /**
     * @var FormatInterface
     */
    protected $formatter;

    public function __construct(FormatInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function write()
    {
        $this->makeFile($this->formatter->getFileName());

        $content = '';

        foreach ((array) $this->formatter->generate() as $line) {
            $content .= "{$line}\n";
        }

        file_put_contents($this->formatter->getFileName(), $content, FILE_APPEND);
    }

    protected function makeFile($fileName){
        if (file_exists($fileName)) {
            return;
        }

        touch($fileName);
    }
}
