<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoanm\InitRepositoryWithComposer\Application\WriteConfiguration;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;
use Yoanm\InitRepositoryWithComposer\Infrastructure\Command\Transformer\InputTransformer;

class WriteConfigurationCommand extends Command
{
    const NAME = 'configuration:write';

    /** @var InputTransformer */
    private $inputTransformer;
    /** @var WriteConfiguration */
    private $writeConfiguration;

    public function __construct(
        InputTransformer $inputTransformer,
        WriteConfiguration $writeConfiguration
    ) {
        parent::__construct(self::NAME);

        $this->inputTransformer = $inputTransformer;
        $this->writeConfiguration = $writeConfiguration;
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Will create or update a composer configuration file')
            ->addArgument(
                InputTransformer::ARGUMENT_PACKAGE_NAME,
                InputArgument::REQUIRED,
                'Name for the composer package'
            )
            ->addArgument(
                InputTransformer::ARGUMENT_CONFIGURATION_DEST_FOLDER,
                InputArgument::OPTIONAL,
                'Configuration file destination folder',
                '.'
            )
            ->addOption(
                InputTransformer::OPTION_TYPE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package type. Ex : "library" / "project"',
                Configuration::DEFAULT_TYPE
            )
            ->addOption(
                InputTransformer::OPTION_LICENSE,
                null,
                InputOption::VALUE_REQUIRED,
                'Package license type',
                Configuration::DEFAULT_LICENSE
            )
            ->addOption(
                InputTransformer::OPTION_PACKAGE_VERSION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package version number. Ex : "X.Y.Z"',
                Configuration::DEFAULT_VERSION
            )
            ->addOption(
                InputTransformer::OPTION_DESCRIPTION,
                null,
                InputOption::VALUE_REQUIRED,
                'Package description'
            )
            ->addOption(
                InputTransformer::OPTION_KEYWORD,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package keywords'
            )
            ->addOption(
                InputTransformer::OPTION_AUTHOR,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Package authors. Format "name[#email[#role]]'
            )
            ->addOption(
                InputTransformer::OPTION_PROVIDED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages provided by this one. Ex : "package-name#version"'
            )
            ->addOption(
                InputTransformer::OPTION_SUGGESTED_PACKAGE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages suggested by this one. Ex : "package-name#description"'
            )
            ->addOption(
                InputTransformer::OPTION_SUPPORT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package support urls. Ex : "type#url"'
            )
            ->addOption(
                InputTransformer::OPTION_AUTOLOAD_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-0 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::OPTION_AUTOLOAD_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-4 autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::OPTION_AUTOLOAD_DEV_PSR0,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of packages PSR-0 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::OPTION_AUTOLOAD_DEV_PSR4,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of package PSR-4 dev autoload. Ex : "namespace#path"'
            )
            ->addOption(
                InputTransformer::OPTION_REQUIRE,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::OPTION_REQUIRE_DEV,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of required dev packages. Ex "vendor/package-name#~x.y"'
            )
            ->addOption(
                InputTransformer::OPTION_SCRIPT,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'List of scripts for the package. Ex : "script-name#command"'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $writeRequest = $this->inputTransformer->fromCommandLine($input->getArguments(), $input->getOptions());

        $this->writeConfiguration->run($writeRequest);
    }
}
