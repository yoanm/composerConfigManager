<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Yoanm\ComposerConfigManager\Application\Loader\ConfigurationLoaderInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

abstract class AbstractTemplatableCommand extends Command
{
    const OPTION_TEMPLATE = 'template';

    /** @var ConfigurationLoaderInterface */
    private $configurationLoader;

    /**
     * @param ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(ConfigurationLoaderInterface $configurationLoader)
    {
        parent::__construct();
        $this->configurationLoader = $configurationLoader;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addOption(
                self::OPTION_TEMPLATE,
                null,
                InputOption::VALUE_REQUIRED,
                'Path of the json template file. Will be used as default values.'
            )
        ;
    }

    /**
     * @return ConfigurationLoaderInterface
     */
    public function getConfigurationLoader()
    {
        return $this->configurationLoader;
    }

    /**
     * @param InputInterface $input
     *
     * @return null|Configuration
     */
    protected function loadTemplateConfiguration(InputInterface $input)
    {
        $templatePath = $input->getOption(self::OPTION_TEMPLATE);
        $templateConfiguration = null;
        if ($templatePath) {
            if (is_dir($templatePath)) {
                $templateConfiguration = $this->configurationLoader->fromPath($templatePath);
            } elseif (is_file($templatePath)) {
                $templateConfiguration = $this->configurationLoader->fromString(file_get_contents($templatePath));
            } else {
                throw new \UnexpectedValueException('Template path is nor a file or a path !');
            }
        }

        return $templateConfiguration;
    }
}
