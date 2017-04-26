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
    use Yoanm\InitRepositoryWithComposer\Infrastructure\SfApplication;

    /**
     * Class BaseConsoleContext
     */
    class BaseConsoleContext implements Context, BehatContextSubscriberInterface
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

            $this->application = $container->get('init_repo_with_composer.sf_app');
        }

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
