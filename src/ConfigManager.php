<?php

namespace clagiordano\weblibs\configmanager;

/**
 * Class ConfigManager, class for easily read and access to php config array file.
 * @package clagiordano\weblibs\configmanager
 */
class ConfigManager
{
    private $configData = null;
    private $configFilePath = null;

    /**
     * Create config object, optionally automatic load config
     * from argument $configFilePath
     *
     * @param string $configFilePath
     *
     * @return ConfigManager
     */
    public function __construct($configFilePath = null)
    {
        return $this->loadConfig($configFilePath);
    }

    /**
     * Load config data from file and store it into internal property
     *
     * @param null|string $configFilePath
     *
     * @return ConfigManager
     */
    public function loadConfig($configFilePath = null)
    {
        if (!is_null($configFilePath)) {
            $this->configFilePath = $configFilePath;

            if (file_exists($configFilePath)) {
                $this->configData = require $configFilePath;
            }
        }

        return $this;
    }

    /**
     * Prepare and write config file on disk
     *
     * @param null|string $configFilePath
     * @param bool $autoReloadConfig
     *
     * @return ConfigManager
     * @throws \Exception
     */
    public function saveConfigFile($configFilePath = null, $autoReloadConfig = false)
    {
        if (is_null($configFilePath)) {
            $configFilePath = $this->configFilePath;
        }

        $configFileContent = "<?php\n\n";
        $configFileContent .= "return ";
        $configFileContent .= var_export($this->configData, true);
        $configFileContent .= ";\n\n";

        if ((is_writable($configFilePath) && is_writable(dirname($configFilePath)))) {
            file_put_contents($configFilePath, $configFileContent);
        } else {
            throw new \Exception(__METHOD__ . ": File path '$configFilePath' is not writeable!");
        }

        if ($autoReloadConfig) {
            $this->loadConfig($configFilePath);
        }

        return $this;
    }

    /**
     * Get value pointer from config for get/set value
     *
     * @param string $configPath
     *
     * @return mixed
     */
    private function & getValuePointer($configPath)
    {
        $configData =& $this->configData;
        $parts = explode('.', $configPath);
        $length = count($parts);

        for ($i = 0; $i < $length; $i++) {
            if (!isset($configData[ $parts[ $i ] ])) {
                $configData[ $parts[ $i ] ] = ($i === $length) ? [] : null;
            }
            $configData = &$configData[ $parts[ $i ] ];
        }

        return $configData;
    }

    /**
     * Get value from config data throught keyValue path
     *
     * @param string $configPath
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function getValue($configPath, $defaultValue = null)
    {
        $stored = $this->getValuePointer($configPath);

        return is_null($stored) ? $defaultValue : $stored;
    }

    /**
     * Check if exist required config for keyValue
     *
     * @param string $keyValue
     *
     * @return mixed
     */
    public function existValue($keyValue)
    {
        return !is_null($this->getValue($keyValue));
    }

    /**
     * Set value in config path
     *
     * @param string $configPath
     * @param mixed $newValue
     *
     * @return ConfigManager
     */
    public function setValue($configPath, $newValue)
    {
        $configData = &$this->getValuePointer($configPath);
        $configData = $newValue;

        return $this;
    }
}
