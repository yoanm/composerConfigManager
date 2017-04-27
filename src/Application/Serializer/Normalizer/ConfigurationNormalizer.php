<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

class ConfigurationNormalizer
{
    const KEY_NAME = 'name';
    const KEY_TYPE = 'type';
    const KEY_LICENSE = 'license';
    const KEY_VERSION = 'version';
    const KEY_DESCRIPTION = 'description';
    const KEY_KEYWORDS = 'keywords';
    const KEY_AUTHORS = 'authors';
    const KEY_PROVIDE = 'provide';
    const KEY_SUGGEST = 'suggest';
    const KEY_SUPPORT = 'support';
    const KEY_REQUIRE = 'require';
    const KEY_REQUIRE_DEV = 'require-dev';
    const KEY_AUTOLOAD = 'autoload';
    const KEY_AUTOLOAD_DEV = 'autoload-dev';
    const KEY_SCRIPT = 'script';

    /** @var AuthorListNormalizer */
    private $authorListNormalizer;
    /** @var PackageListNormalizer */
    private $packageListNormalizer;
    /** @var SuggestedPackageListNormalizer */
    private $suggestedPackageListNormalizer;
    /** @var SupportListNormalizer */
    private $supportListNormalizer;
    /** @var AutoloadListNormalizer */
    private $autoloadListNormalizer;
    /** @var ScriptListNormalizer */
    private $scriptListNormalizer;

    public function __construct(
        AuthorListNormalizer $authorListNormalizer,
        PackageListNormalizer $packageListNormalizer,
        SuggestedPackageListNormalizer $suggestedPackageListNormalizer,
        SupportListNormalizer $supportListNormalizer,
        AutoloadListNormalizer $autoloadListNormalizer,
        ScriptListNormalizer $scriptListNormalizer
    ) {
        $this->authorListNormalizer = $authorListNormalizer;
        $this->packageListNormalizer = $packageListNormalizer;
        $this->suggestedPackageListNormalizer = $suggestedPackageListNormalizer;
        $this->supportListNormalizer = $supportListNormalizer;
        $this->autoloadListNormalizer = $autoloadListNormalizer;
        $this->scriptListNormalizer = $scriptListNormalizer;
    }

    public function normalize(Configuration $configuration)
    {
        $normalizedConfiguration = [
            self::KEY_NAME => $configuration->getPackageName(),
            self::KEY_TYPE =>$configuration->getType(),
            self::KEY_LICENSE => $configuration->getLicense()
        ];

        // package version
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getPackageVersion(),
            self::KEY_VERSION
        );
        // description
        $normalizedConfiguration = $this->appendIfDefined(
            $normalizedConfiguration,
            $configuration->getDescription(),
            self::KEY_DESCRIPTION
        );
        // keywords
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $configuration->getKeywordList(),
            self::KEY_KEYWORDS
        );
        // authors
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->authorListNormalizer->normalize($configuration->getAuthorList()),
            self::KEY_AUTHORS
        );
        // provide
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getProvidedPackageList()),
            self::KEY_PROVIDE
        );
        // suggest
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->suggestedPackageListNormalizer->normalize($configuration->getSuggestedPackageList()),
            self::KEY_SUGGEST
        );
        // support
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->supportListNormalizer->normalize($configuration->getSupportList()),
            self::KEY_SUPPORT
        );
        // require
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getRequiredPackageList()),
            self::KEY_REQUIRE
        );
        // require-dev
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->packageListNormalizer->normalize($configuration->getRequiredDevPackageList()),
            self::KEY_REQUIRE_DEV
        );
        // autoload
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->autoloadListNormalizer->normalize($configuration->getAutoloadList()),
            self::KEY_AUTOLOAD
        );
        // autoload-dev
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->autoloadListNormalizer->normalize($configuration->getAutoloadDevList()),
            self::KEY_AUTOLOAD_DEV
        );
        // script
        $normalizedConfiguration = $this->appendIfNotEmpty(
            $normalizedConfiguration,
            $this->scriptListNormalizer->normalize($configuration->getScriptList()),
            self::KEY_SCRIPT
        );

        return $normalizedConfiguration;
    }

    /**
     * @param array  $normalizedConfiguration
     * @param array  $list
     * @param string $key
     *
     * @return array
     */
    protected function appendIfNotEmpty(array $normalizedConfiguration, array $list, $key)
    {
        if (count($list)) {
            $normalizedConfiguration[$key] = $list;
        }

        return $normalizedConfiguration;
    }

    /**
     * @param array  $normalizedConfiguration
     * @param string $value
     * @param string $key
     *
     * @return array
     */
    protected function appendIfDefined(array $normalizedConfiguration, $value, $key)
    {
        if ($value) {
            $normalizedConfiguration[$key] = $value;
        }

        return $normalizedConfiguration;
    }
}
