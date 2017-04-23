<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Command\Transformer;

use Yoanm\InitRepositoryWithComposer\Application\WriteConfigurationRequest;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Author;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Autoload;
use Yoanm\InitRepositoryWithComposer\Domain\Model\AutoloadEntry;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Package;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Script;
use Yoanm\InitRepositoryWithComposer\Domain\Model\SuggestedPackage;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Support;

class InputTransformer
{
    /** Arguments */
    const ARGUMENT_PACKAGE_NAME = 'package-name';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'destination';

    /** Options */
    const OPTION_TYPE = 'type';
    const OPTION_LICENSE = 'license';
    const OPTION_PACKAGE_VERSION = 'package-version';
    const OPTION_DESCRIPTION = 'description';
    const OPTION_KEYWORD = 'keyword';
    const OPTION_AUTHOR_NAME = 'author-name';
    const OPTION_AUTHOR_EMAIL = 'author-email';
    const OPTION_AUTHOR_ROLE = 'author-role';
    const OPTION_PROVIDED_PACKAGE = 'provided-package';
    const OPTION_SUGGESTED_PACKAGE = 'suggested-package';
    const OPTION_SUPPORT = 'support';
    const OPTION_AUTOLOAD_PSR0 = 'autoload-psr0';
    const OPTION_AUTOLOAD_PSR4 = 'autoload-psr4';
    const OPTION_AUTOLOAD_DEV_PSR0 = 'autoload-dev-psr0';
    const OPTION_AUTOLOAD_DEV_PSR4 = 'autoload-dev-psr4';
    const OPTION_REQUIRE = 'require';
    const OPTION_REQUIRE_DEV = 'require-dev';
    const OPTION_SCRIPT = 'script';

    /**
     * @param array $argumentList
     * @param array $optionsList
     *
     * @return WriteConfigurationRequest
     */
    public function fromCommandLine(array $argumentList, array $optionsList)
    {
        $configuration = $this->extractConfiguration($argumentList, $optionsList);

        $this->extractKeywords($configuration, $optionsList);

        $this->extractAuthors($configuration, $optionsList);

        $this->extractProvidedPackages($configuration, $optionsList);

        $this->extractSuggestedPackages($configuration, $optionsList);

        $this->extractSupports($configuration, $optionsList);

        $this->extractAutoloads($configuration, $optionsList);

        $this->extractRequiredPackages($configuration, $optionsList);

        $this->extractScripts($configuration, $optionsList);

        return new WriteConfigurationRequest(
            $configuration,
            $argumentList[self::ARGUMENT_CONFIGURATION_DEST_FOLDER]
        );
    }

    /**
     * @param array $argumentList
     * @param array $optionsList
     *
     * @return Configuration
     */
    protected function extractConfiguration(array $argumentList, array $optionsList)
    {
        return new Configuration(
            $argumentList[self::ARGUMENT_PACKAGE_NAME],
            $optionsList[self::OPTION_TYPE],
            $optionsList[self::OPTION_DESCRIPTION],
            $optionsList[self::OPTION_LICENSE],
            $optionsList[self::OPTION_PACKAGE_VERSION]
        );
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractKeywords(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_KEYWORD] as $keyword) {
            $configuration->addKeyword($keyword);
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractAuthors(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_AUTHOR_NAME] as $key => $authorName) {
            $configuration->addAuthor(
                new Author(
                    $authorName,
                    $optionsList[self::OPTION_AUTHOR_EMAIL][$key],
                    $optionsList[self::OPTION_AUTHOR_ROLE][$key]
                )
            );
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractProvidedPackages(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_PROVIDED_PACKAGE] as $rawValue) {
            list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
            $configuration->addProvidedPackage(new Package($name, $versionConstraint));
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractSuggestedPackages(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_SUGGESTED_PACKAGE] as $rawValue) {
            list ($name, $description) = $this->extractKeyValue($rawValue);
            $configuration->addSuggestedPackage(new SuggestedPackage($name, $description));
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractSupports(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_SUPPORT] as $rawValue) {
            list ($type, $url) = $this->extractKeyValue($rawValue);
            $configuration->addSupport(new Support($type, $url));
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractAutoloads(Configuration $configuration, array $optionsList)
    {
        // PSR-0
        $configuration->addAutoload(
            new Autoload(
                Autoload::TYPE_PSR0,
                $this->extractAutoloadList($optionsList, self::OPTION_AUTOLOAD_PSR0)
            )
        );
        $configuration->addAutoloadDev(
            new Autoload(
                Autoload::TYPE_PSR0,
                $this->extractAutoloadList($optionsList, self::OPTION_AUTOLOAD_DEV_PSR0)
            )
        );
        // PSR-4
        $configuration->addAutoload(
            new Autoload(
                Autoload::TYPE_PSR4,
                $this->extractAutoloadList($optionsList, self::OPTION_AUTOLOAD_PSR4)
            )
        );
        $configuration->addAutoloadDev(
            new Autoload(
                Autoload::TYPE_PSR4,
                $this->extractAutoloadList($optionsList, self::OPTION_AUTOLOAD_DEV_PSR4)
            )
        );

    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractRequiredPackages(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_REQUIRE] as $rawValue) {
            list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
            $configuration->addRequiredPackage(new Package($name, $versionConstraint));
        }
        foreach ($optionsList[self::OPTION_REQUIRE_DEV] as $rawValue) {
            list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
            $configuration->addRequiredDevPackage(new Package($name, $versionConstraint));
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionsList
     */
    protected function extractScripts(Configuration $configuration, array $optionsList)
    {
        foreach ($optionsList[self::OPTION_SCRIPT] as $rawValue) {
            list ($name, $command) = $this->extractKeyValue($rawValue);
            $configuration->addScript(
                new Script($name, $command)
            );
        }
    }

    /**
     * @param string $value
     *
     * @return array
     */
    protected function extractKeyValue($value)
    {
        $result = [];
        preg_match_all('/(.+)#([^#]+)/', $value, $result);

        return [
            array_shift($result[1]),
            array_shift($result[2]),
        ];
    }

    /**
     * @param array  $optionsList
     * @param string $optionKey
     *
     * @return AutoloadEntry[]
     */
    protected function extractAutoloadList(array $optionsList, $optionKey)
    {
        /** @var AutoloadEntry[] $list */
        $list = [];
        foreach ($optionsList[$optionKey] as $rawValue) {
            list ($namespace, $path) = $this->extractKeyValue($rawValue);
            $list[] = new AutoloadEntry($namespace, $path);
        }

        return $list;
    }
}