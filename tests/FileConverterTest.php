<?php

namespace clagiordano\weblibs\configmanager\tests;

use clagiordano\weblibs\configmanager\ArrayConfigManager;
use clagiordano\weblibs\configmanager\FileConverter;
use clagiordano\weblibs\configmanager\YamlConfigManager;
use PHPUnit\Framework\TestCase;

/**
 * Class FileConverterTest
 * @package clagiordano\weblibs\configmanager\tests
 */
class FileConverterTest extends TestCase
{
    /**
     * @return array
     */
    public function configDataProvider()
    {
        return [
            [
              __DIR__ . '/../testsdata/sample_config_data.php',
              '\clagiordano\weblibs\configmanager\ArrayConfigManager',
              __DIR__ . '/../testsdata/sample_config_data.php.converted.yml',
              '\clagiordano\weblibs\configmanager\YamlConfigManager',
            ],
            [
                __DIR__ . '/../testsdata/sample_config_data.php',
                '\clagiordano\weblibs\configmanager\ArrayConfigManager',
                __DIR__ . '/../testsdata/sample_config_data.php.converted.json',
                '\clagiordano\weblibs\configmanager\JsonConfigManager',
            ],
            [
                __DIR__ . '/../testsdata/sample_config_data.yml',
                '\clagiordano\weblibs\configmanager\YamlConfigManager',
                __DIR__ . '/../testsdata/sample_config_data.yml.converted.json',
                '\clagiordano\weblibs\configmanager\JsonConfigManager',
            ],
            [
                __DIR__ . '/../testsdata/sample_config_data.yml',
                '\clagiordano\weblibs\configmanager\YamlConfigManager',
                __DIR__ . '/../testsdata/sample_config_data.yml.converted.php',
                '\clagiordano\weblibs\configmanager\ArrayConfigManager',
            ],
            [
                __DIR__ . '/../testsdata/sample_config_data.json',
                '\clagiordano\weblibs\configmanager\JsonConfigManager',
                __DIR__ . '/../testsdata/sample_config_data.json.converted.yml',
                '\clagiordano\weblibs\configmanager\YamlConfigManager',
            ],
            [
                __DIR__ . '/../testsdata/sample_config_data.json',
                '\clagiordano\weblibs\configmanager\JsonConfigManager',
                __DIR__ . '/../testsdata/sample_config_data.json.converted.php',
                '\clagiordano\weblibs\configmanager\ArrayConfigManager',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider configDataProvider
     * @param mixed $sourceConfig
     * @param mixed $sourceInstance
     * @param mixed $targetConfig
     * @param mixed $targetInstance
     */
    public function canConvertOneFormatToAnother($sourceConfig, $sourceInstance, $targetConfig, $targetInstance)
    {
        $source = new $sourceInstance($sourceConfig);
        self::assertInstanceOf($sourceInstance, $source);

        $target = new $targetInstance($targetConfig);
        self::assertInstanceOf($targetInstance, $target);

        $converter = new FileConverter($source, $target);
        $converter->convert();

        self::assertFileExists($targetConfig);
    }

    /**
     * @test
     */
    public function canSuccessConversionOnInvalidSource()
    {

        $source = new ArrayConfigManager();
        $target = new YamlConfigManager(__DIR__ . '/../testsdata/sample_config_data.empty.converted.yml');

        $converter = new FileConverter($source, $target);
        $converter->convert();
    }

    /**
     * @test
     */
    public function canFailConversionOnInvalidTarget()
    {
        self::setExpectedException('\RuntimeException');

        $source = new ArrayConfigManager(__DIR__ . '/../testsdata/sample_config_data.php');
        $target = new YamlConfigManager();

        $converter = new FileConverter($source, $target);
        $converter->convert();
    }
}
