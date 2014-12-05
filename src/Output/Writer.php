<?php namespace ReadmeGen\Output;

use ReadmeGen\Output\Format\FormatInterface;

class Writer
{
    /**
     * @var FormatInterface
     */
    protected $formatter;
    protected $break;

    public function __construct(FormatInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function write()
    {
        $this->makeFile($this->formatter->getFileName());
        $fileContent = file_get_contents($this->formatter->getFileName());
        $log = join("\n", (array) $this->formatter->generate())."\n";

        // Include the breakpoint
        if (false === empty($this->break) && strstr($fileContent, $this->break)) {
            $splitFileContent = explode($this->break, $fileContent);

            file_put_contents($this->formatter->getFileName(), $splitFileContent[0].$this->break."\n".$log.$splitFileContent[1]);

            return true;
        }

        file_put_contents($this->formatter->getFileName(), $log.$fileContent);

        return true;
    }

    protected function makeFile($fileName){
        if (file_exists($fileName)) {
            return;
        }

        touch($fileName);
    }

    public function setBreak($break = null)
    {
        if (false === empty($break)) {
            $this->break = $break;
        }

        return $this;
    }
}
