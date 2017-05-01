<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * Class ComposerCMContext
 */
class UpdateContext extends ComposerCMContext
{
    public function iExecuteConsoleWithDestAndOption($dest = null, PyStringNode $options = null)
    {
        $commandArguments = $dest ? sprintf('"%s"', $dest) : '';
        $this->iCreateFakeOldFileAt($dest);
        $this->iExecuteComposerCMWith('update', $commandArguments, $options);
    }

    /**
     * @Given /^I execute composercm update with "(?<dest>[^"]+)" and following options:$/
     */
    public function iExecuteConsoleWithNameAndOption($dest, PyStringNode $options)
    {
        $this->iExecuteConsoleWithDestAndOption($dest, $options);
    }

    /**
     * @Given /^I execute composercm update with following options:$/
     */
    public function iExecuteConsoleWitOption(PyStringNode $options)
    {
        $this->iExecuteConsoleWithDestAndOption(null, $options);
    }
}
