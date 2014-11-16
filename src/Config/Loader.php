<?php namespace ReadmeGen\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * YAML config loader.
 */
class Loader
{

    /**
     * Returns the config as an array.
     * 
     * @param string $path Path to the file.
     * @return array
     * @throws \Symfony\Component\Yaml\Exception\ParseException When a parse error occurs.
     */
    public function get($path)
    {
        return Yaml::parse($this->getFileContent($path));
    }
    
    /**
     * Returns the file's contents.
     * 
     * @param string $path Path to file.
     * @return string
     * @throws \InvalidArgumentException When the file does not exist.
     */
    protected function getFileContent($path)
    {
        if (false === file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exist.', $path));
        }
        
        return file_get_contents($path);
    }

}
