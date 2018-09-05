<?php namespace ReadmeGen\Vcs\Type;

class Git extends AbstractType
{
    /**
     * Parses the log.
     *
     * @return array
     */
    public function parse()
    {
        $hash = function($entry){
            $hash = explode(Git::HASH_SEPARATOR, $entry);
            return preg_replace('/\n/', '', $hash[0]);
        };
        $msgs = function($entry){
            $commit = explode(Git::HASH_SEPARATOR, $entry);
            return (count(explode(Git::HASH_SEPARATOR, $entry)) >=2 ? $commit[1] : null);
        };

        return array_filter(array_combine(array_map($hash, explode(Git::MSG_SEPARATOR, $this->getLog())), array_map($msgs, explode(Git::MSG_SEPARATOR, $this->getLog()))));
    }

    /**
     * Returns the base VCS log command.
     *
     * @return string
     */
    protected function getBaseCommand()
    {
        // return 'git log --pretty=format:"%s{{MSG_SEPARATOR}}%b"';
        return 'git log --pretty=format:"%H{{HASH_SEPARATOR}}%s{{MSG_SEPARATOR}}%b"';
    }

    /**
     * Returns the compiled VCS log command.
     *
     * @return string
     */
    public function getCommand()
    {
        $options = $this->getOptions();
        $arguments = $this->getArguments();

        $to = null;
        $from = $arguments['from'];

        if (true === isset($arguments['to'])) {
            $to = $arguments['to'];
        }

        array_walk($options, function (&$option) {
            $option = '--' . $option;
        });

        array_walk($arguments, function (&$value, $argument) {
            $value = '--' . $argument . '=' . $value;
        });

        return trim(sprintf('%s %s %s', $this->getBaseCommand(), $this->getRange($from, $to), join(' ', $options)));
    }

    protected function getLog()
    {
        return $this->runCommand($this->getCommand());
    }

    protected function getRange($from, $to = null)
    {
        $range = $from . '..';

        return $range . (($to) ?: 'HEAD');
    }


    public function getToDate()
    {
        $arguments = $this->getArguments();

        $to = (true === isset($arguments['to'])) ? $arguments['to'] : 'HEAD';

        $fullDate = $this->runCommand(sprintf('git log -1 -s --format=%%ci %s', $to));
        $date = explode(' ', $fullDate);

        return $date[0];
    }
}
