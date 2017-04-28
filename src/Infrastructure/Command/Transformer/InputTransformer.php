<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Application\WriteConfigurationRequest;
use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\AutoloadEntry;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\Package;
use Yoanm\ComposerConfigManager\Domain\Model\Script;
use Yoanm\ComposerConfigManager\Domain\Model\SuggestedPackage;
use Yoanm\ComposerConfigManager\Domain\Model\Support;

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
        $configuration = $this->createConfiguration($argumentList, $optionList);

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
    protected function createConfiguration(array $argumentList, array $optionList)
    {
        return new Configuration(
            $argumentList[self::ARGUMENT_PACKAGE_NAME],
            isset($optionList[self::OPTION_TYPE])
                ? $optionList[self::OPTION_TYPE]
                : Configuration::DEFAULT_TYPE,
            isset($optionList[self::OPTION_LICENSE])
                ? $optionList[self::OPTION_LICENSE]
                : Configuration::DEFAULT_LICENSE,
            isset($optionList[self::OPTION_PACKAGE_VERSION])
                ? $optionList[self::OPTION_PACKAGE_VERSION]
                : Configuration::DEFAULT_VERSION,
            isset($optionList[self::OPTION_DESCRIPTION])
                ? $optionList[self::OPTION_DESCRIPTION]
                : null,
            $this->extractKeywords($optionList),
            $this->extractAuthors($optionList),
            $this->extractProvidedPackages($optionList),
            $this->extractSuggestedPackages($optionList),
            $this->extractSupports($optionList),
            $this->extractAutoloads($optionList),
            $this->extractAutoloadsDev($optionList),
            $this->extractRequiredPackages($optionList),
            $this->extractRequiredDevPackages($optionList),
            $this->extractScripts($optionList)
        );
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractKeywords(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_KEYWORD]) && is_array($optionList[self::OPTION_KEYWORD])) {
            foreach ($optionList[self::OPTION_KEYWORD] as $keyword) {
                $list[] = $keyword;
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractAuthors(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_AUTHOR]) && is_array($optionList[self::OPTION_AUTHOR])) {
            foreach ($optionList[self::OPTION_AUTHOR] as $key => $author) {
                $data = $this->extractDataFromValue($author);
                $name = array_shift($data);
                $email = array_shift($data);
                $role = array_shift($data);

                $list[] = new Author($name, $email, $role);
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractProvidedPackages(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_PROVIDED_PACKAGE]) && is_array($optionList[self::OPTION_PROVIDED_PACKAGE])) {
            foreach ($optionList[self::OPTION_PROVIDED_PACKAGE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractSuggestedPackages(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_SUGGESTED_PACKAGE])
            && is_array($optionList[self::OPTION_SUGGESTED_PACKAGE])
        ) {
            foreach ($optionList[self::OPTION_SUGGESTED_PACKAGE] as $rawValue) {
                $data = $this->extractDataFromValue($rawValue);
                $list[] = new SuggestedPackage(
                    array_shift($data),
                    implode(self::SEPARATOR, $data)
                );
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractSupports(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_SUPPORT]) && is_array($optionList[self::OPTION_SUPPORT])) {
            foreach ($optionList[self::OPTION_SUPPORT] as $rawValue) {
                $data = $this->extractDataFromValue($rawValue);
                $list[] = new Support(array_shift($data), implode(self::SEPARATOR, $data));
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractAutoloads(array $optionList)
    {
        $list = [];
        // PSR0
        $list[] = new Autoload(
            Autoload::TYPE_PSR0,
            $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_PSR0)
        );
        // PSR-4
        $list[] = new Autoload(
            Autoload::TYPE_PSR4,
            $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_PSR4)
        );

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractAutoloadsDev(array $optionList)
    {
        $list = [];
        $list[] = new Autoload(
            Autoload::TYPE_PSR0,
            $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_DEV_PSR0)
        );
        // PSR-4
        $list[] = new Autoload(
            Autoload::TYPE_PSR4,
            $this->extractAutoloadList($optionList, self::OPTION_AUTOLOAD_DEV_PSR4)
        );

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractRequiredPackages(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_REQUIRE]) && is_array($optionList[self::OPTION_REQUIRE])) {
            foreach ($optionList[self::OPTION_REQUIRE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }
    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractRequiredDevPackages(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_REQUIRE_DEV]) && is_array($optionList[self::OPTION_REQUIRE_DEV])) {
            foreach ($optionList[self::OPTION_REQUIRE_DEV] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }

    /**
     * @param array $optionList
     *
     * @return array
     */
    protected function extractScripts(array $optionList)
    {
        $list = [];
        if (isset($optionList[self::OPTION_SCRIPT]) && is_array($optionList[self::OPTION_SCRIPT])) {
            foreach ($optionList[self::OPTION_SCRIPT] as $rawValue) {
                list ($name, $command) = $this->extractDataFromValue($rawValue);
                $list[] = new Script($name, $command);
            }
        }

        return $list;
    }

    /**
     * @param string $value
     *
     * @return array
     */
    protected function extractDataFromValue($value)
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
                list ($namespace, $path) = $this->extractDataFromValue($rawValue);
                $list[] = new AutoloadEntry($namespace, $path);
            }
        }

        return $list;
    }
}
