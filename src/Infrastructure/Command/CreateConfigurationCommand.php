<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command;

use \InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\ComposerConfigManager\Application\UpdateConfiguration;
use Yoanm\ComposerConfigManager\Application\UpdateConfigurationRequest;
use Yoanm\ComposerConfigManager\Application\Loader\ConfigurationLoaderInterface;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer\InputTransformer;

class CreateConfigurationCommand extends AbstractTemplatableCommand
{
    const NAME = 'create';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'destination';

    /** @var InputTransformer */
    private $inputTransformer;
    /** @var UpdateConfiguration */
    private $updateConfiguration;

    /**
     * @param InputTransformer             $inputTransformer
     * @param UpdateConfiguration          $updateConfiguration
     * @param ConfigurationLoaderInterface $configurationLoader
     */
    public function __construct(
        InputTransformer $inputTransformer,
        UpdateConfiguration $updateConfiguration,
        ConfigurationLoaderInterface $configurationLoader
    ) {
        parent::__construct($configurationLoader);

        $this->inputTransformer = $inputTransformer;
        $this->updateConfiguration = $updateConfiguration;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Will create a composer configuration file')
            ->addArgument(
                self::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Configuration file destination folder',
                '.'
            )
            ->addArgument(
                InputTransformer::KEY_PACKAGE_NAME,
                InputArgument::OPTIONAL,
                'Name for the composer package. Optionnal if a template containing package-name is given.'
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
        ;
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationList = [];
        $templateConfiguration = $this->loadTemplateConfiguration($input);
        if ($templateConfiguration) {
            $configurationList[] = $templateConfiguration;
        }
        if ($newConfiguration = $this->loadConfiguration($input, $templateConfiguration)) {
            $configurationList[] = $newConfiguration;
        }
        $this->updateConfiguration->run(
            new UpdateConfigurationRequest(
                $configurationList,
                $input->getArgument(self::ARGUMENT_CONFIGURATION_DEST_FOLDER)
            )
        );
    }

    /**
     * @param InputInterface     $input
     * @param Configuration|null $templateConfiguration
     *
     * @return null|Configuration
     */
    protected function loadConfiguration(InputInterface $input, Configuration $templateConfiguration = null)
    {
        $packageName = $input->getArgument(InputTransformer::KEY_PACKAGE_NAME);
        if (null === $packageName) {
            if (null === $templateConfiguration
                || '' === trim($templateConfiguration->getPackageName())
            ) {
                throw new InvalidArgumentException(
                    sprintf(
                        'A package name must be given if no template containing package name is given !',
                        gettype($packageName)
                    )
                );
            }

            return null;
        }

        return $this->inputTransformer->fromCommandLine(
            [
                InputTransformer::KEY_PACKAGE_NAME => $packageName
            ] + $input->getOptions()
        );
    }
}
