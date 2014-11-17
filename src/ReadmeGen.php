<?php namespace ReadmeGen;

use ReadmeGen\Config\Loader as ConfigLoader;
use ReadmeGen\Vcs\Parser as Parser;

class ReadmeGen
{
    
    /**
     * Path to the default config file.
     */
    const DEFAULT_CONFIG_PATH = 'readmegen.yml';
    
    /**
     * Config loader.
     *
     * @var ConfigLoader
     */
    protected $configLoader;
    
    /**
     * Default config.
     *
     * @var array
     */
    protected $defaultConfig = array();
    
    /**
     * Final config - default config merged with local config.
     *
     * @var array
     */
    protected $config = array();
    
    /**
     * Parser instance.
     *
     * @var Parser
     */
    protected $parser;
    
    public function __construct(\ReadmeGen\Config\Loader $configLoader, $defaultConfigPath = null)
    {
        $configPath = (false === empty($defaultConfigPath) ? $defaultConfigPath : self::DEFAULT_CONFIG_PATH);
        
        $this->configLoader = $configLoader;
        $this->defaultConfig = $this->configLoader->get($configPath);
        
        /**
         * @todo This should be actually loading a local config file and merge it with the default config
         * @see \ReadmeGen\Config\Loader::get() - this method can merge the configs
         */
        $this->config = $this->defaultConfig;
    }

    /**
     * Returns the config.
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns the parser.
     * 
     * @return Parser
     * @throws \InvalidArgumentException When the VCS parser class does not exist.
     */
    public function getParser()
    {
        if (true === empty($this->parser)) {
            $typeParserClassName = sprintf('\ReadmeGen\Vcs\Type\%s', ucfirst($this->config['vcs']));
            
            if (false === class_exists($typeParserClassName)) {
                throw new \InvalidArgumentException(sprintf('Class "%s" does not exist', $typeParserClassName));
            }
            
            $this->parser = new Parser(new $typeParserClassName());
        }
        
        return $this->parser;
    }
}
