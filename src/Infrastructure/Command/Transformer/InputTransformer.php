<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Command\Transformer;

use Yoanm\ComposerConfigManager\Domain\Model\Author;
use Yoanm\ComposerConfigManager\Domain\Model\Autoload;
use Yoanm\ComposerConfigManager\Domain\Model\AutoloadEntry;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;
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
    const KEY_PROVIDED_PACKAGE = 'provide';
    const KEY_SUGGESTED_PACKAGE = 'suggest';
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
     * @return ConfigurationFile|null
     */
    public function fromCommandLine($inputList)
    {
        return $this->createConfigurationFile($inputList);
    }

    /**
     * @param array $inputList
     *
     * @return ConfigurationFile|null
     */
    protected function createConfigurationFile(array $inputList)
    {
        $defaultKeyList = [
            self::KEY_PACKAGE_NAME,
            self::KEY_DESCRIPTION,
            self::KEY_PACKAGE_VERSION,
            self::KEY_TYPE,
            self::KEY_KEYWORD,
            self::KEY_LICENSE,
            self::KEY_AUTHOR,
            self::KEY_SUPPORT,
            self::KEY_REQUIRE,
            self::KEY_REQUIRE_DEV,
            self::KEY_PROVIDED_PACKAGE,
            self::KEY_SUGGESTED_PACKAGE,
            self::KEY_AUTOLOAD_PSR0,
            self::KEY_AUTOLOAD_PSR4,
            self::KEY_AUTOLOAD_DEV_PSR0,
            self::KEY_AUTOLOAD_DEV_PSR4,
            self::KEY_SCRIPT,
        ];
        $defaultNormalizedFileKeyList = [
            'name' => [self::KEY_PACKAGE_NAME],
            'description' => [self::KEY_DESCRIPTION],
            'version' => [self::KEY_PACKAGE_VERSION],
            'type' => [self::KEY_TYPE],
            'keywords' => [self::KEY_KEYWORD],
            'license' => [self::KEY_LICENSE],
            'authors' => [self::KEY_AUTHOR],
            'support' => [self::KEY_SUPPORT],
            'require' => [self::KEY_REQUIRE],
            'require-dev' => [self::KEY_REQUIRE_DEV],
            'provide' => [self::KEY_PROVIDED_PACKAGE],
            'suggest' => [self::KEY_SUGGESTED_PACKAGE],
            'autoload' => [self::KEY_AUTOLOAD_PSR0, self::KEY_AUTOLOAD_PSR4],
            'autoload-dev' => [self::KEY_AUTOLOAD_DEV_PSR0, self::KEY_AUTOLOAD_DEV_PSR4],
            'scripts' => [self::KEY_SCRIPT],
        ];
        if (0 === count(array_intersect($defaultKeyList, array_keys($inputList)))) {
            return null;
        }
        $fileKeyList = [];
        foreach ($defaultNormalizedFileKeyList as $fileKey => $inputKeyList) {
            foreach ($inputKeyList as $inputKey) {
                if (isset($inputList[$inputKey])) {
                    $fileKeyList[] = $fileKey;
                    break;
                }
            }
        }

        return new ConfigurationFile(
            new Configuration(
                $this->getValue($inputList, self::KEY_PACKAGE_NAME, null),
                $this->getValue($inputList, self::KEY_TYPE, null),
                $this->getValue($inputList, self::KEY_LICENSE, null),
                $this->getValue($inputList, self::KEY_PACKAGE_VERSION, null),
                $this->getValue($inputList, self::KEY_DESCRIPTION, null),
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
            ),
            $fileKeyList
        );
    }

    /**
     * @param array  $inputList
     * @param string $key
     * @param string $defaultValue
     *
     * @return string
     */
    protected function getValue(array $inputList, $key, $defaultValue)
    {
        return isset($inputList[$key]) ? $inputList[$key] : $defaultValue;
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
        foreach ($this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_PSR0) as $namespace => $path) {
            $list[] = new Autoload(Autoload::TYPE_PSR0, $path, $namespace);
        }
        // PSR-4
        foreach ($this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_PSR4) as $namespace => $path) {
            $list[] = new Autoload(Autoload::TYPE_PSR4, $path, $namespace);
        }


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
        // PSR0
        foreach ($this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_DEV_PSR0) as $namespace => $path) {
            $list[] = new Autoload(Autoload::TYPE_PSR0, $path, $namespace);
        }
        // PSR-4
        foreach ($this->extractAutoloadList($inputList, self::KEY_AUTOLOAD_DEV_PSR4) as $namespace => $path) {
            $list[] = new Autoload(Autoload::TYPE_PSR4, $path, $namespace);
        }

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
     * @return array
     */
    protected function extractAutoloadList(array $inputList, $optionKey)
    {
        $list = [];
        if (isset($inputList[$optionKey]) && is_array($inputList[$optionKey])) {
            foreach ($inputList[$optionKey] as $rawValue) {
                list ($namespace, $path) = $this->extractDataFromValue($rawValue);
                $list[$namespace] = $path;
            }
        }

        return $list;
    }
}
