<?php

namespace clagiordano\weblibs\configmanager;

use Exception;
use RuntimeException;

/**
 * Class YamlConfigManager
 * @package clagiordano\weblibs\configmanager
 */
class YamlConfigManager extends AbstractConfigManager
{
    /**
     * Load config data from file and store it into internal property
     *
     * @param null|string $configFilePath
     *
     * @return IConfigurable
     */
    public function loadConfig($configFilePath = null)
    {
        if (!is_null($configFilePath)) {
            $this->configFilePath = $configFilePath;

            if (file_exists($configFilePath)) {
                $this->configData = yaml_parse_file($configFilePath);
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
     * @return IConfigurable
     * @throws RuntimeException
     */
    public function saveConfigFile($configFilePath = null, $autoReloadConfig = false)
    {
        if (is_null($configFilePath)) {
            $configFilePath = $this->configFilePath;
        }

        try {
            yaml_emit_file($configFilePath, $this->configData);
        } catch (Exception $exc) {
            throw new RuntimeException(
                __METHOD__ . ": Failed to write config file to path '{$configFilePath}'"
            );
        }

        if ($autoReloadConfig) {
            $this->loadConfig($configFilePath);
        }

        return $this;
    }
}