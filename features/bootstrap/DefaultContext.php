<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationFileWriter;
use Yoanm\ComposerConfigManager\Infrastructure\Writer\ConfigurationWriter;

/**
 * Class DefaultContext
 */
class DefaultContext implements Context
{
    const DEFAULT_DESTINATION = './build/behat';

    /** @var CommandRunnerContext */
    private $commandRunnerContext;

    public static function getFilePath($path = null)
    {
        return sprintf(
            '%s/%s',
            self::getBasePath($path),
            ConfigurationFileWriter::FILENAME
        );
    }

    public static function getBasePath($path = null)
    {
        return trim(
            sprintf(
                '%s/%s',
                trim(self::DEFAULT_DESTINATION, '/'),
                trim($path, '/')
            ),
            '/'
        );
    }

    /**
     * @BeforeScenario
     */
    public function initDirectories()
    {
        $this->deleteDirectory(self::getBasePath());
        $this->iHaveAFolder('/');
    }

    /**
     * @Given /^I have no(?: configuration)? file(?: at "(?<filepath>[^"]+)")?$/
     */
    public function iHaveNoFileAt($filepath = null)
    {
        @unlink(self::getFilePath($filepath));
    }

    /**
     * @Given /^I have the folder "(?<path>[^"]+)"$/
     */
    public function iHaveAFolder($path)
    {
        @mkdir(self::getBasePath($path), 0777, true);
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should be:$/
     */
    public function configurationFileShouldBe($path = null, PyStringNode $inputs = null)
    {
        $this->configFileShouldBe($this->decodeJson($inputs->getRaw()), $path);
    }

    /**
     * @Then /^configuration file key order (?:at "(?<path>[^"]+)" )?should be:$/
     */
    public function configurationFileKeyOrderShouldBe($path = null, PyStringNode $inputs = null)
    {
        $this->configFileKeyOrderShouldBe($this->decodeJson($inputs->getRaw()), $path);
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should contains:$/
     */
    public function configurationFileShouldContains($path = null, PyStringNode $inputs = null)
    {
        $this->configFileShouldContains($this->decodeJson($inputs->getRaw()), $path);
    }

    /**
     * @Then /^I should have a configuration file at "(?<path>[^"]+)"$/
     */
    public function iShouldHaveAConfigurationAt($path)
    {
        if (!file_exists(self::getFilepath($path))) {
            throw new \Exception(
                sprintf(
                    "No configuration file was not created at %s",
                    $path
                )
            );
        }
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function init(BeforeScenarioScope $scope)
    {
        $this->commandRunnerContext = $scope->getEnvironment()->getContext(CommandRunnerContext::class);
    }

    protected function configFileShouldBe(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent(self::getFilepath($path));
        try {
            Assert::assertSame($expected, $currentConfiguration);
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            throw new \Exception(
                sprintf(
                    "Configuration file content not expected !\n Expected: %s\nActual: %s",
                    json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    json_encode($currentConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                )
            );
        }
    }

    protected function configFileKeyOrderShouldBe(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent(self::getFilepath($path));
        $actual = array_keys($currentConfiguration);
        try {
            Assert::assertSame($expected, $actual);
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            throw new \Exception(
                sprintf(
                    "Configuration file keys not expected !\n Expected: %s\nActual: %s",
                    json_encode($expected),
                    json_encode($actual)
                )
            );
        }
    }

    protected function configFileShouldContains(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent(self::getFilepath($path));
        $resultList = [];
        if (is_array($currentConfiguration)) {
            foreach ($expected as $key => $value) {
                if (array_key_exists($key, $currentConfiguration)) {
                    $resultList[$key] = $currentConfiguration[$key];
                }
            }
        }
        try {
            Assert::assertSame($expected, $resultList);
        } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
            throw new \Exception(
                sprintf(
                    "Configuration file content do not contains expected data !\n Expected: %s\nActual: %s",
                    json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                    json_encode($currentConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                )
            );
        }
    }

    /**
     * @param $configFilePath
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function getConfigurationFileContent($configFilePath)
    {
        return $this->decodeJson(file_get_contents($configFilePath));
    }

    /**
     * @param string $encoded
     *
     * @return array
     *
     * @throws Exception
     */
    protected function decodeJson($encoded)
    {
        /** @var array $currentConfiguration */
        $decoded = json_decode($encoded, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(sprintf(
                'Invalid JSON (%s). Content : %s',
                json_last_error_msg(),
                $encoded
            ));
        }

        return $decoded;
    }

    private function deleteDirectory($dir)
    {
        if ($handle = @opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ('.' != $file && '..' != $file) {
                    $path = implode(DIRECTORY_SEPARATOR, [$dir, $file]);
                    if (is_dir($path)) {
                        if (!@rmdir($path)) {
                            // Probably not empty => remove files inside
                            $this->deleteDirectory($path.DIRECTORY_SEPARATOR);
                        }
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($handle);
            if ('.' !== $dir) {
                rmdir($dir);
            }
        }
    }
}
