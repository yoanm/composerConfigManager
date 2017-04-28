<?php
namespace {

    use Behat\Gherkin\Node\PyStringNode;
    use PHPUnit\Framework\Assert;

    /**
     * Class ComposerCMContext
     */
    class ComposerCMContext extends BaseConsoleContext
    {
        const DEFAULT_DESTINATION = './build';
        const DEFAULT_NAME = 'name';

        /** @var string|null */
        private $fileWasCreatedAt = null;
        /** @var array|null */
        private $currentConfiguration = null;

        /**
         * @Given /^I execute composercm with "(?<name>[^"]+)", "(?<dest>[^"]+)" and following options:$/
         */
        public function iExecuteConsoleWithNameDestAndOption($name = null, $dest = null, PyStringNode $options = null)
        {
            $this->currentConfiguration = null;
            $this->fileWasCreatedAt = null;
            $dest = $dest ? $dest : self::DEFAULT_DESTINATION;
            $this->runCommand(sprintf(
                '"%s" "%s" %s',
                $name ? $name : self::DEFAULT_NAME,
                $dest,
                $options ? $options->getRaw() : ''
            ));
            $this->currentConfiguration = $this->getConfigurationFileContent($dest);
            $expectedFilePath = sprintf('%s/composer.json', $dest);
            $this->fileWasCreatedAt = file_exists($expectedFilePath) ? $expectedFilePath : null;
            @unlink($expectedFilePath);
        }

        /**
         * @Given /^I execute composercm with "(?<name>[^"]+)" and "(?<dest>[^"]+)"$/
         */
        public function iExecuteConsoleWithNameAndDest($name, $dest)
        {
            $this->iExecuteConsoleWithNameDestAndOption($name, $dest, null);
        }
        /**
         * @Given /^I execute composercm with "(?<name>[^"]+)" and following options:$/
         */
        public function iExecuteConsoleWithNameAndOption($name, PyStringNode $options)
        {
            $this->iExecuteConsoleWithNameDestAndOption($name, null, $options);
        }

        /**
         * @Given /^I execute composercm with "(?<name>[^"]+)"$/
         */
        public function iExecuteConsoleWithName($name)
        {
            $this->iExecuteConsoleWithNameDestAndOption($name);
        }

        /**
         * @Given /^I execute composercm with following options:$/
         */
        public function iExecuteConsoleWitOption(PyStringNode $options)
        {
            $this->iExecuteConsoleWithNameDestAndOption(null, null, $options);
        }

        /**
         * @Given /^I have the folder "(?<path>[^"]+)"$/
         */
        public function iHaveAFolder($path)
        {
            @unlink(sprintf('%s/composer.json', $path));
            @mkdir($path, 0777, true);
        }

        /**
         * @Then /^configuration file should be:$/
         */
        public function configurationFileShouldBe(PyStringNode $inputs)
        {
            $this->configFileShouldBe($this->decodeJson($inputs->getRaw()));
        }

        /**
         * @Then /^configuration file should contains:$/
         */
        public function configurationFileShouldContains(PyStringNode $inputs)
        {
            $this->configFileShouldContains($this->decodeJson($inputs->getRaw()));
        }

        /**
         * @Then /^I should have a configuration file at "(?<path>[^"]+)"$/
         */
        public function iShouldHaveAConfigurationAt($path)
        {
            if (sprintf('%s/composer.json', $path) != $this->fileWasCreatedAt) {
                throw new \Exception(
                    sprintf(
                        "No configuration file was not created at %s",
                        $path
                    )
                );
            }
        }

        protected function configFileShouldBe(array $expected)
        {
            try {
                Assert::assertSame($expected, $this->currentConfiguration);
            } catch (\PHPUnit_Framework_ExpectationFailedException $exception) {
                throw new \Exception(
                    sprintf(
                        "Configuration file content not expected !\n Expected: %s\nActual: %s",
                        json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                        json_encode($this->currentConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                    )
                );
            }
        }

        protected function configFileShouldContains(array $expected)
        {
            $resultList = [];
            if (is_array($this->currentConfiguration)) {
                foreach ($expected as $key => $value) {
                    if (array_key_exists($key, $this->currentConfiguration)) {
                        $resultList[$key] = $this->currentConfiguration[$key];
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
                        json_encode($this->currentConfiguration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
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
            return $this->decodeJson(file_get_contents(sprintf('%s/composer.json', $configFilePath)));
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
    }
}
