<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * Class TemplateContext
 */
class TemplateContext extends ComposerCMContext
{
    /**
     * @Given /^I create a template file at "(?<path>[^"]+)" with:$/
     */
    public function iExecuteConsoleWithNameDestAndOption($path, PyStringNode $content)
    {
        file_put_contents($path, $content->getRaw());
    }
}
