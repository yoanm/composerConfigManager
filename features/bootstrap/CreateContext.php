<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;

/**
 * Class ComposerCMContext
 */
class CreateContext extends ComposerCMContext
{
    const DEFAULT_NAME = 'name';

    /**
     * @Given /^I execute composercm create with "(?<name>[^"]+)", "(?<dest>[^"]+)" and following options:$/
     */
    public function iExecuteConsoleWithNameDestAndOption($name = null, $dest = null, PyStringNode $options = null)
    {
        $commandArguments = sprintf(
            '"%s" "%s"',
            DefaultContext::getBasePath($dest),
            $name ? $name : self::DEFAULT_NAME
        );
        $this->iCleanPath($dest);
        $this->iExecuteComposerCMWith('create', $commandArguments, $options);
    }

    /**
     * @Given /^I execute composercm create with "(?<name>[^"]+)" and "(?<dest>[^"]+)"$/
     */
    public function iExecuteConsoleWithNameAndDest($name, $dest)
    {
        $this->iExecuteConsoleWithNameDestAndOption($name, $dest, null);
    }
    /**
     * @Given /^I execute composercm create with "(?<name>[^"]+)" and following options:$/
     */
    public function iExecuteConsoleWithNameAndOption($name, PyStringNode $options)
    {
        $this->iExecuteConsoleWithNameDestAndOption($name, null, $options);
    }

    /**
     * @Given /^I execute composercm create with "(?<name>[^"]+)"$/
     */
    public function iExecuteConsoleWithName($name)
    {
        $this->iExecuteConsoleWithNameDestAndOption($name);
    }

    /**
     * @Given /^I execute composercm create with following options:$/
     */
    public function iExecuteConsoleWitOption(PyStringNode $options)
    {
        $this->iExecuteConsoleWithNameDestAndOption(null, null, $options);
    }
}
