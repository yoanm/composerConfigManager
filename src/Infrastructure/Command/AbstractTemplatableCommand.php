<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Yoanm\ComposerConfigManager\Application\Loader\ConfigurationFileLoaderInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

abstract class AbstractTemplatableCommand extends Command
{
    const OPTION_TEMPLATE = 'template';

    /** @var ConfigurationFileLoaderInterface */
    private $configurationLoader;

    /**
     * @param ConfigurationFileLoaderInterface $configurationFileLoader
     */
    public function __construct(ConfigurationFileLoaderInterface $configurationFileLoader)
    {
        parent::__construct();
        $this->configurationFileLoader = $configurationFileLoader;
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
     * @return ConfigurationFileLoaderInterface
     */
    public function getConfigurationFileLoader()
    {
        return $this->configurationFileLoader;
    }

    /**
     * @param InputInterface $input
     *
     * @return null|Configuration
     */
    protected function loadTemplateConfigurationFile(InputInterface $input)
    {
        $templatePath = $input->getOption(self::OPTION_TEMPLATE);
        $templateConfiguration = null;
        if ($templatePath) {
            if (is_dir($templatePath)) {
                $templateConfiguration = $this->configurationFileLoader->fromPath($templatePath);
            } elseif (is_file($templatePath)) {
                $templateConfiguration = $this->configurationFileLoader->fromString(file_get_contents($templatePath));
            } else {
                throw new \UnexpectedValueException('Template path is nor a file or a path !');
            }
        }

        return $templateConfiguration;
    }
}
