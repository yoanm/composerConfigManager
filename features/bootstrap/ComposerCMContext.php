<?php
namespace Functional\Yoanm\ComposerConfigManager\BehatContext;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;

/**
 * Class ComposerCMContext
 */
class ComposerCMContext implements Context
{
    /** @var CommandRunnerContext */
    private $commandRunnerContext;

    /**
     * @param string            $commandArguments
     * @param PyStringNode|null $options
     */
    public function iExecuteComposerCMWith($commandName, $commandArguments, PyStringNode $options = null)
    {
        $this->commandRunnerContext->runCommand(
            $commandName,
            sprintf(
                '%s %s',
                $commandArguments,
                $options ? $options->getRaw() : ''
            )
        );
    }

    public function iCleanPath($path)
    {
        @unlink(DefaultContext::getFilePath($path));
    }

    public function iCreateFakeOldFileAt($path)
    {
        file_put_contents(
            DefaultContext::getFilePath($path),
            <<<TEMPLATE
{
"name": "default-name",
"type": "default-type",
"license": "default-license",
"version": "default-version",
"description": "default-description",
"keywords": [
    "DEFAULT-KEYWORD1",
    "DEFAULT-KEYWORD2"
],
"authors": [
    {
        "name": "default-name1",
        "email": "default-email1",
        "role": "default-role1"
    },
    {
        "name": "default-name2",
        "email": "default-email2",
        "role": "default-role2"
    }
],
"provide": {
    "package1": "default-provided-package1",
    "package2": "default-provided-package2"
},
"suggest": {
    "package1": "default-suggested-package1",
    "package2": "default-suggested-package2"
},
"support": {
    "type1": "default-support-type1",
    "type2": "default-support-type2"
},
"autoload": {
    "psr-0": {
        "DefaultNamespace\\\\DefaultSubNamespace": "default-psr0-path1",
        "DefaultNamespace\\\\DefaultSubNamespace2": "default-psr0-path2"
    },
    "psr-4": {
        "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-psr4-path1",
        "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-psr4-path2"
    }
},
"autoload-dev": {
    "psr-0": {
        "DefaultNamespace\\\\DefaultSubNamespace": "default-psr0-path1",
        "DefaultNamespace\\\\DefaultSubNamespace2": "default-psr0-path2"
    },
    "psr-4": {
        "\\\\DefaultNamespace\\\\DefaultSubNamespace\\\\": "default-psr4-path1",
        "\\\\DefaultNamespace\\\\DefaultSubNamespace2\\\\": "default-psr4-path2"
    }
},
"require": {
    "requirement1": "default-required-package1"
},
"require-dev": {
    "requirement1": "default-required-dev-package1"
},
"scripts": {
    "default-script-1": [
        "default-script1-command_1",
        "default-script1-command_2"
    ],
    "default-script-2": [
        "default-script2-command_1",
        "default-script2-command_2"
    ]
}
}

TEMPLATE
        );
    }

    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function init(BeforeScenarioScope $scope)
    {
        $this->commandRunnerContext = $scope->getEnvironment()->getContext(CommandRunnerContext::class);
    }
}
