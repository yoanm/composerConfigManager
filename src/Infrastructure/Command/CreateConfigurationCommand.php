<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;
use Yoanm\ComposerConfigManager\Application\CreateConfiguration;
use Yoanm\ComposerConfigManager\Application\CreateConfigurationRequest;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer\InputTransformer;
use Yoanm\ComposerConfigManager\Infrastructure\Loader\ConfigurationLoader;

class CreateConfigurationCommand extends Command
{
    const NAME = 'create';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'destination';
    const OPTION_TEMPLATE = 'template';

    /** @var InputTransformer */
    private $inputTransformer;
    /** @var CreateConfiguration */
    private $createConfiguration;
    /** @var ConfigurationLoader */
    private $configurationLoader;

    /**
     * @param InputTransformer    $inputTransformer
     * @param CreateConfiguration $createConfiguration
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(
        InputTransformer $inputTransformer,
        CreateConfiguration $createConfiguration,
        ConfigurationLoader $configurationLoader
    ) {
        parent::__construct(self::NAME);

        $this->inputTransformer = $inputTransformer;
        $this->createConfiguration = $createConfiguration;
        $this->configurationLoader = $configurationLoader;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Will create a composer configuration file')
            ->addArgument(
                InputTransformer::KEY_PACKAGE_NAME,
                InputArgument::REQUIRED,
                'Name for the composer package'
            )
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Configuration file destination folder',
                '.'
            )
            ->addOption(
                InputTransformer::KEY_TYPE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package type. Ex : "library" / "project"'
            )
            ->addOption(
                InputTransformer::KEY_LICENSE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package license type'
            )
            ->addOption(
                InputTransformer::KEY_PACKAGE_VERSION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package version number. Ex : "X.Y.Z"'
            )
            ->addOption(
                InputTransformer::KEY_DESCRIPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package description'
            )
            ->addOption(
                InputTransformer::KEY_KEYWORD,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package keywords'
            )
            ->addOption(
                InputTransformer::KEY_AUTHOR,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package authors. Format "name[#email[#role]]'
            )
            ->addOption(
                InputTransformer::KEY_PROVIDED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages provided by this one. Ex : "package-name#version"'
            )
            ->addOption(
                InputTransformer::KEY_SUGGESTED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages suggested by this one. Ex : "package-name#description"'
            )
            ->addOption(
                InputTransformer::KEY_SUPPORT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package support urls. Ex : "type#url"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-0 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-4 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-0 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_AUTOLOAD_DEV_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of PSR-4 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::KEY_REQUIRE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::KEY_REQUIRE_DEV,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required dev packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::KEY_SCRIPT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of scripts for the package. Ex : "script-name#command"'
            )
            ->addOption(
                self::OPTION_TEMPLATE,
                null,
                InputOption::VALUE_REQUIRED,
                'Path of the json template file. Will be used as default values.'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createConfiguration->run(
            new CreateConfigurationRequest(
                $this->loadConfiguration($input),
                $input->getArgument(self::ARGUMENT_CONFIGURATION_DEST_FOLDER),
                $this->loadTemplateConfiguration($input->getOption(self::OPTION_TEMPLATE))
            )
        );
    }

    /**
     * @param string $templatePath
     *
     * @return null|Configuration
     */
    protected function loadTemplateConfiguration($templatePath)
    {
        $templateConfiguration = null;
        if ($templatePath) {
            if (is_dir($templatePath)) {
                $templateConfiguration = $this->configurationLoader->fromPath($templatePath);
            } elseif (is_file($templatePath)) {
                $templateConfiguration = $this->configurationLoader->fromFilePath($templatePath);
            } else {
                throw new \UnexpectedValueException('Template path is nor a file or a path !');
            }
        }

        return $templateConfiguration;
    }

    /**
     * @param InputInterface $input
     *
     * @return Configuration
     */
    protected function loadConfiguration(InputInterface $input)
    {
        return $this->inputTransformer->fromCommandLine(
            [
                InputTransformer::KEY_PACKAGE_NAME => $input->getArgument(InputTransformer::KEY_PACKAGE_NAME)
            ] + $input->getOptions()
        );
    }
}
