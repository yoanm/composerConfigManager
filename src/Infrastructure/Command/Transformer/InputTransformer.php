<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

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

    const KEY_PACKAGE_NAME = 'package-name';
    const KEY_TYPE = 'type';
    const KEY_LICENSE = 'license';
    const KEY_PACKAGE_VERSION = 'package-version';
    const KEY_DESCRIPTION = 'description';
    const KEY_KEYWORD = 'keyword';
    const KEY_AUTHOR = 'author';
    const KEY_PROVIDED_PACKAGE = 'provided-package';
    const KEY_SUGGESTED_PACKAGE = 'suggested-package';
    const KEY_SUPPORT = 'support';
    const KEY_AUTOLOAD_PSR0 = 'autoload-psr0';
    const KEY_AUTOLOAD_PSR4 = 'autoload-psr4';
    const KEY_AUTOLOAD_DEV_PSR0 = 'autoload-dev-psr0';
    const KEY_AUTOLOAD_DEV_PSR4 = 'autoload-dev-psr4';
    const KEY_REQUIRE = 'require';
    const KEY_REQUIRE_DEV = 'require-dev';
    const KEY_SCRIPT = 'script';

    /**
     * @param $inputList
     *
     * @return Configuration
     */
    public function fromCommandLine($inputList)
    {
        return $this->createConfiguration($inputList);
    }

    /**
     * @param array $inputList
     *
     * @return Configuration
     */
    protected function createConfiguration(array $inputList)
    {
        return new Configuration(
            $inputList[self::KEY_PACKAGE_NAME],
            isset($inputList[self::KEY_TYPE])
                ? $inputList[self::KEY_TYPE]
                : Configuration::DEFAULT_TYPE,
            isset($inputList[self::KEY_LICENSE])
                ? $inputList[self::KEY_LICENSE]
                : Configuration::DEFAULT_LICENSE,
            isset($inputList[self::KEY_PACKAGE_VERSION])
                ? $inputList[self::KEY_PACKAGE_VERSION]
                : Configuration::DEFAULT_VERSION,
            isset($inputList[self::KEY_DESCRIPTION])
                ? $inputList[self::KEY_DESCRIPTION]
                : null,
            $this->extractKeywords($inputList),
            $this->extractAuthors($inputList),
            $this->extractProvidedPackages($inputList),
            $this->extractSuggestedPackages($inputList),
            $this->extractSupports($inputList),
            $this->extractAutoloads($inputList),
            $this->extractAutoloadsDev($inputList),
            $this->extractRequiredPackages($inputList),
            $this->extractRequiredDevPackages($inputList),
            $this->extractScripts($inputList)
        );
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractKeywords(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_KEYWORD]) && is_array($inputList[self::KEY_KEYWORD])) {
            foreach ($inputList[self::KEY_KEYWORD] as $keyword) {
                $list[] = $keyword;
            }
        }

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractAuthors(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_AUTHOR]) && is_array($inputList[self::KEY_AUTHOR])) {
            foreach ($inputList[self::KEY_AUTHOR] as $key => $author) {
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
     * @param array $inputList
     *
     * @return array
     */
    protected function extractProvidedPackages(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_PROVIDED_PACKAGE]) && is_array($inputList[self::KEY_PROVIDED_PACKAGE])) {
            foreach ($inputList[self::KEY_PROVIDED_PACKAGE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractSuggestedPackages(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_SUGGESTED_PACKAGE])
            && is_array($inputList[self::KEY_SUGGESTED_PACKAGE])
        ) {
            foreach ($inputList[self::KEY_SUGGESTED_PACKAGE] as $rawValue) {
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
     * @param array $inputList
     *
     * @return array
     */
    protected function extractSupports(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_SUPPORT]) && is_array($inputList[self::KEY_SUPPORT])) {
            foreach ($inputList[self::KEY_SUPPORT] as $rawValue) {
                $data = $this->extractDataFromValue($rawValue);
                $list[] = new Support(array_shift($data), implode(self::SEPARATOR, $data));
            }
        }

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractAutoloads(array $inputList)
    {
        $list = [];
        // PSR0
        $list[] = new Autoload(
            Autoload::TYPE_PSR0,
            $this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_PSR0)
        );
        // PSR-4
        $list[] = new Autoload(
            Autoload::TYPE_PSR4,
            $this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_PSR4)
        );

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractAutoloadsDev(array $inputList)
    {
        $list = [];
        $list[] = new Autoload(
            Autoload::TYPE_PSR0,
            $this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_DEV_PSR0)
        );
        // PSR-4
        $list[] = new Autoload(
            Autoload::TYPE_PSR4,
            $this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_DEV_PSR4)
        );

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractRequiredPackages(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_REQUIRE]) && is_array($inputList[self::KEY_REQUIRE])) {
            foreach ($inputList[self::KEY_REQUIRE] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }
    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractRequiredDevPackages(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_REQUIRE_DEV]) && is_array($inputList[self::KEY_REQUIRE_DEV])) {
            foreach ($inputList[self::KEY_REQUIRE_DEV] as $rawValue) {
                list ($name, $versionConstraint) = $this->extractDataFromValue($rawValue);
                $list[] = new Package($name, $versionConstraint);
            }
        }

        return $list;
    }

    /**
     * @param array $inputList
     *
     * @return array
     */
    protected function extractScripts(array $inputList)
    {
        $list = [];
        if (isset($inputList[self::KEY_SCRIPT]) && is_array($inputList[self::KEY_SCRIPT])) {
            foreach ($inputList[self::KEY_SCRIPT] as $rawValue) {
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
     * @param array  $inputList
     * @param string $optionKey
     *
     * @return AutoloadEntry[]
     */
    protected function extractAutoloadList(array $inputList, $optionKey)
    {
        /** @var AutoloadEntry[] $list */
        $list = [];
        if (isset($inputList[$optionKey]) && is_array($inputList[$optionKey])) {
            foreach ($inputList[$optionKey] as $rawValue) {
                list ($namespace, $path) = $this->extractDataFromValue($rawValue);
                $list[] = new AutoloadEntry($namespace, $path);
            }
        }

        return $list;
    }
}
