<?php
namespace {

    use Behat\Behat\Context\Context;
    use Behat\Behat\EventDispatcher\Event\ExampleTested;
    use Behat\Behat\EventDispatcher\Event\GherkinNodeTested;
    use Behat\Behat\EventDispatcher\Event\ScenarioTested;
    use Behat\Gherkin\Node\PyStringNode;
    use Symfony\Component\Config\FileLocator;
    use Symfony\Component\Console\Input\ArrayInput;
    use Symfony\Component\Console\Input\StringInput;
    use Symfony\Component\Console\Output\ConsoleOutput;
    use Symfony\Component\DependencyInjection\ContainerBuilder;
    use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
    use Yoanm\BehatUtilsExtension\Context\BehatContextSubscriberInterface;
    use Yoanm\ComposerConfigManager\Infrastructure\SfApplication;

    /**
     * Class ComposerCMContext
     */
    class ComposerCMContext implements Context, BehatContextSubscriberInterface
    {
        /** @var SfApplication */
        private $application;
        /** @var ConsoleOutput */
        private $output;
        /** @var ArrayInput */
        private $intput;
        /** @var null|\Exception */
        private $lastException;

        /**
         * @param string            $commandArguments
         * @param PyStringNode|null $options
         */
        public function iExecuteComposerCMWith($commandArguments, PyStringNode $options = null)
        {
            $this->runCommand(sprintf(
                '%s %s',
                $commandArguments,
                $options ? $options->getRaw() : ''
            ));
        }

        public function iCleanPath($path)
        {
            @unlink(sprintf('%s/composer.json', $path));
        }

        public function iCreateFakeOldFileAt($path)
        {
            var_dump("create $path/composer.json");
            file_put_contents(
                sprintf('%s/composer.json', $path),
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
         * @return SfApplication
         */
        public function getApplication()
        {
            return $this->application;
        }

        /**
         * @param GherkinNodeTested $event
         */
        public function reset(GherkinNodeTested $event)
        {
            require_once(__DIR__ . '/../../vendor/autoload.php');

            $container = new ContainerBuilder();
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../src/Infrastructure/config'));

            $loader->load('application.xml');
            $loader->load('infra.xml');

            $this->application = $container->get('composer_config_manager.sf_app');
        }

        /**
         * {@inheritdoc}
         */
        public static function getSubscribedEvents()
        {
            return [
                ScenarioTested::BEFORE => ['reset'],
                ExampleTested::BEFORE => ['reset'],
            ];
        }

        /**
         * @param array|string $inputs
         */
        protected function runCommand($inputs)
        {
            $this->intput = is_array($inputs) ? new ArrayInput($inputs) : new StringInput($inputs);
            $this->output = new ConsoleOutput();
            $this->lastException = null;
            try {
                $this->application->setAutoExit(false);
                $this->application->run($this->intput, $this->output);
            } catch (\Exception $exception) {
                $this->lastException = $exception;
            }
        }
    }
}
