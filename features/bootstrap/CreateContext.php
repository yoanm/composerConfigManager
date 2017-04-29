<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

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
        $dest = $dest ? $dest : DefaultContext::DEFAULT_DESTINATION;
        $commandArguments = sprintf(
            'create "%s" "%s"',
            $name ? $name : self::DEFAULT_NAME,
            $dest
        );
        $this->iCleanPath($dest);
        $this->iExecuteComposerCMWith($commandArguments, $options);
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
