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
    const SEPARATOR = '#';

    /** Arguments */
    const ARGUMENT_PACKAGE_NAME = 'package-name';
    const ARGUMENT_CONFIGURATION_DEST_FOLDER = 'destination';

    /** Options */
    const OPTION_TYPE = 'type';
    const OPTION_LICENSE = 'license';
    const OPTION_PACKAGE_VERSION = 'package-version';
    const OPTION_DESCRIPTION = 'description';
    const OPTION_KEYWORD = 'keyword';
    const OPTION_AUTHOR = 'author';
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
     * @param array $optionList
     *
     * @return WriteConfigurationRequest
     */
    public function fromCommandLine(array $argumentList, array $optionList)
    {
        $configuration = $this->extractConfiguration($argumentList, $optionList);

        $this->extractKeywords($configuration, $optionList);

        $this->extractAuthors($configuration, $optionList);

        $this->extractProvidedPackages($configuration, $optionList);

        $this->extractSuggestedPackages($configuration, $optionList);

        $this->extractSupports($configuration, $optionList);

        $this->extractAutoloads($configuration, $optionList);

        $this->extractRequiredPackages($configuration, $optionList);

        $this->extractScripts($configuration, $optionList);

        return new WriteConfigurationRequest(
            $configuration,
            $argumentList[self::ARGUMENT_CONFIGURATION_DEST_FOLDER]
        );
    }

    /**
     * @param array $argumentList
     * @param array $optionList
     *
     * @return Configuration
     */
    protected function extractConfiguration(array $argumentList, array $optionList)
    {
        return new Configuration(
            $argumentList[self::ARGUMENT_PACKAGE_NAME],
            isset($optionList[self::OPTION_TYPE]) ? $optionList[self::OPTION_TYPE] : null,
            isset($optionList[self::OPTION_DESCRIPTION]) ? $optionList[self::OPTION_DESCRIPTION] : null,
            isset($optionList[self::OPTION_LICENSE]) ? $optionList[self::OPTION_LICENSE] : null,
            isset($optionList[self::OPTION_PACKAGE_VERSION]) ? $optionList[self::OPTION_PACKAGE_VERSION] : null
        );
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractKeywords(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_KEYWORD]) && is_array($optionList[self::OPTION_KEYWORD])) {
            foreach ($optionList[self::OPTION_KEYWORD] as $keyword) {
                $configuration->addKeyword($keyword);
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractAuthors(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_AUTHOR]) && is_array($optionList[self::OPTION_AUTHOR])) {
            foreach ($optionList[self::OPTION_AUTHOR] as $key => $author) {
                $data = $this->extractKeyValue($author);
                $name = array_shift($data);
                $email = array_shift($data);
                $role = array_shift($data);

                $configuration->addAuthor(
                    new Author($name, $email, $role)
                );
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractProvidedPackages(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_PROVIDED_PACKAGE]) && is_array($optionList[self::OPTION_PROVIDED_PACKAGE])) {
            foreach ($optionList[self::OPTION_PROVIDED_PACKAGE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
                $configuration->addProvidedPackage(new Package($name, $versionConstraint));
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractSuggestedPackages(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_SUGGESTED_PACKAGE])
            && is_array($optionList[self::OPTION_SUGGESTED_PACKAGE])
        ) {
            foreach ($optionList[self::OPTION_SUGGESTED_PACKAGE] as $rawValue) {
                list ($name, $description) = $this->extractKeyValue($rawValue);
                $configuration->addSuggestedPackage(new SuggestedPackage($name, $description));
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractSupports(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_SUPPORT]) && is_array($optionList[self::OPTION_SUPPORT])) {
            foreach ($optionList[self::OPTION_SUPPORT] as $rawValue) {
                list ($type, $url) = $this->extractKeyValue($rawValue);
                $configuration->addSupport(new Support($type, $url));
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractAutoloads(Configuration $configuration, array $optionList)
    {
        // PSR0
        $configuration->addAutoload(
            new Autoload(
                Autoload::TYPE_PSR0,
                $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_PSR0)
            )
        );
        $configuration->addAutoloadDev(
            new Autoload(
                Autoload::TYPE_PSR0,
                $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_DEV_PSR0)
            )
        );
        // PSR-4
        $configuration->addAutoload(
            new Autoload(
                Autoload::TYPE_PSR4,
                $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_PSR4)
            )
        );
        $configuration->addAutoloadDev(
            new Autoload(
                Autoload::TYPE_PSR4,
                $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_DEV_PSR4)
            )
        );
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractRequiredPackages(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_REQUIRE]) && is_array($optionList[self::OPTION_REQUIRE])) {
            foreach ($optionList[self::OPTION_REQUIRE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
                $configuration->addRequiredPackage(new Package($name, $versionConstraint));
            }
        }
        if (isset($optionList[self::OPTION_REQUIRE_DEV]) && is_array($optionList[self::OPTION_REQUIRE_DEV])) {
            foreach ($optionList[self::OPTION_REQUIRE_DEV] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractKeyValue($rawValue);
                $configuration->addRequiredDevPackage(new Package($name, $versionConstraint));
            }
        }
    }

    /**
     * @param Configuration $configuration
     * @param array         $optionList
     */
    protected function extractScripts(Configuration $configuration, array $optionList)
    {
        if (isset($optionList[self::OPTION_SCRIPT]) && is_array($optionList[self::OPTION_SCRIPT])) {
            foreach ($optionList[self::OPTION_SCRIPT] as $rawValue) {
                list ($name, $command) = $this->extractKeyValue($rawValue);
                $configuration->addScript(
                    new Script($name, $command)
                );
            }
        }
    }

    /**
     * @param string $value
     *
     * @return array
     */
    protected function extractKeyValue($value)
    {
        return explode(self::SEPARATOR, $value);
    }

    /**
     * @param array  $optionList
     * @param string $optionKey
     *
     * @return AutoloadEntry[]
     */
    protected function extractAutoloadList(array $optionList, $optionKey)
    {
        /** @var AutoloadEntry[] $list */
        $list = [];
        if (isset($optionList[$optionKey]) && is_array($optionList[$optionKey])) {
            foreach ($optionList[$optionKey] as $rawValue) {
                list ($namespace, $path) = $this->extractKeyValue($rawValue);
                $list[] = new AutoloadEntry($namespace, $path);
            }
        }

        return $list;
    }
}
