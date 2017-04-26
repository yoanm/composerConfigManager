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
        $normalizedAuthorList = $this->authorListNormalizer->normalize(
            $configuration->getAuthorList()
        );
        $normalizedProvidedPackageList = $this->packageListNormalizer->normalize(
            $configuration->getProvidedPackageList()
        );
        $normalizedSuggestedPackageList = $this->suggestedPackageListNormalizer->normalize(
            $configuration->getSuggestedPackageList()
        );
        $normalizedSupportList = $this->supportListNormalizer->normalize(
            $configuration->getSupportList()
        );
        $normalizedRequiredPackageList = $this->packageListNormalizer->normalize(
            $configuration->getRequiredPackageList()
        );
        $normalizedRequiredDevPackageList = $this->packageListNormalizer->normalize(
            $configuration->getRequiredDevPackageList()
        );
        $normalizedAutoloadList = $this->autoloadListNormalizer->normalize(
            $configuration->getAutoloadList()
        );
        $normalizedAutoloadDevList = $this->autoloadListNormalizer->normalize(
            $configuration->getAutoloadDevList()
        );
        $normalizedScriptList = $this->scriptListNormalizer->normalize(
            $configuration->getScriptList()
        );

        $normalizedConfiguration = [
            self::KEY_NAME => $configuration->getPackageName(),
            self::KEY_TYPE =>$configuration->getType(),
            self::KEY_LICENSE => $configuration->getLicense()
        ];

        if ($configuration->getPackageVersion()) {
            $normalizedConfiguration[self::KEY_VERSION] = $configuration->getPackageVersion();
        }
        if ($configuration->getDescription()) {
            $normalizedConfiguration[self::KEY_DESCRIPTION] = $configuration->getDescription();
        }
        if (count($configuration->getKeywordList())) {
            $normalizedConfiguration[self::KEY_KEYWORDS] = $configuration->getKeywordList();
        }
        if (count($normalizedAuthorList)) {
            $normalizedConfiguration[self::KEY_AUTHORS] = $normalizedAuthorList;
        }
        if (count($normalizedProvidedPackageList)) {
            $normalizedConfiguration[self::KEY_PROVIDE] = $normalizedProvidedPackageList;
        }
        if (count($normalizedSuggestedPackageList)) {
            $normalizedConfiguration[self::KEY_SUGGEST] = $normalizedSuggestedPackageList;
        }
        if (count($normalizedSupportList)) {
            $normalizedConfiguration[self::KEY_SUPPORT] = $normalizedSupportList;
        }
        if (count($normalizedRequiredPackageList)) {
            $normalizedConfiguration[self::KEY_REQUIRE] = $normalizedRequiredPackageList;
        }
        if (count($normalizedRequiredDevPackageList)) {
            $normalizedConfiguration[self::KEY_REQUIRE_DEV] = $normalizedRequiredDevPackageList;
        }
        if (count($normalizedAutoloadList)) {
            $normalizedConfiguration[self::KEY_AUTOLOAD] = $normalizedAutoloadList;
        }
        if (count($normalizedAutoloadDevList)) {
            $normalizedConfiguration[self::KEY_AUTOLOAD_DEV] = $normalizedAutoloadDevList;
        }
        if (count($normalizedScriptList)) {
            $normalizedConfiguration[self::KEY_SCRIPT] = $normalizedScriptList;
        }

        return $normalizedConfiguration;
    }
}
