<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * Class DefaultContext
 */
class DefaultContext implements Context
{
    const DEFAULT_DESTINATION = './build';

    /**
     * @Given /^I have the folder "(?<path>[^"]+)"$/
     */
    public function iHaveAFolder($path)
    {
        @mkdir($path, 0777, true);
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should be:$/
     */
    public function configurationFileShouldBe($path = self::DEFAULT_DESTINATION, PyStringNode $inputs = null)
    {
        $this->configFileShouldBe($this->decodeJson($inputs->getRaw()), $path);
    }

    /**
     * @Then /^configuration file (?:at "(?<path>[^"]+)" )?should contains:$/
     */
    public function configurationFileShouldContains($path = self::DEFAULT_DESTINATION, PyStringNode $inputs = null)
    {
        $this->configFileShouldContains($this->decodeJson($inputs->getRaw()), $path);
    }

    /**
     * @Then /^I should have a configuration file at "(?<path>[^"]+)"$/
     */
    public function iShouldHaveAConfigurationAt($path)
    {
        if (!file_exists($this->getFilepath($path))) {
            throw new \Exception(
                sprintf(
                    "No configuration file was not created at %s",
                    $path
                )
            );
        }
    }

    protected function configFileShouldBe(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent($this->getFilepath($path));
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

    protected function configFileShouldContains(array $expected, $path)
    {
        $currentConfiguration = $this->getConfigurationFileContent($this->getFilepath($path));
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

    protected function getFilePath($path)
    {
        return sprintf('%s/composer.json', $path);
    }
}
