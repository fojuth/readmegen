<?php namespace ReadmeGen;

use \ReadmeGen\Config\Loader as ConfigLoader;

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
    
    public function __construct(\ReadmeGen\Config\Loader $configLoader, $defaultConfigPath = null)
    {
        $configPath = (false === empty($defaultConfigPath) ? $defaultConfigPath : self::DEFAULT_CONFIG_PATH);
        
        $this->configLoader = $configLoader;
        $this->defaultConfig = $this->configLoader->get($configPath);
    }

    /**
     * Runs the whole process resulting in generating the readme file.
     */
    public function run()
    {
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
}
