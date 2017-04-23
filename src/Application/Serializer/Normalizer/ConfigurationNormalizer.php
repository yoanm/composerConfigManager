<?php
namespace Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer;

use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

class ConfigurationNormalizer
{
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
            'name' => $configuration->getPackageName(),
            'type' =>$configuration->getType(),
            'license' => $configuration->getLicense()
        ];

        if ($configuration->getPackageVersion()) {
            $normalizedConfiguration['version'] = $configuration->getPackageVersion();
        }
        if ($configuration->getDescription()) {
            $normalizedConfiguration['description'] = $configuration->getDescription();
        }
        if (count($configuration->getKeywordList())) {
            $normalizedConfiguration['keywords'] = $configuration->getKeywordList();
        }
        if (count($normalizedAuthorList)) {
            $normalizedConfiguration['authors'] = $normalizedAuthorList;
        }
        if (count($normalizedProvidedPackageList)) {
            $normalizedConfiguration['provide'] = $normalizedProvidedPackageList;
        }
        if (count($normalizedSuggestedPackageList)) {
            $normalizedConfiguration['suggest'] = $normalizedSuggestedPackageList;
        }
        if (count($normalizedSupportList)) {
            $normalizedConfiguration['support'] = $normalizedSupportList;
        }
        if (count($normalizedRequiredPackageList)) {
            $normalizedConfiguration['require'] = $normalizedRequiredPackageList;
        }
        if (count($normalizedRequiredDevPackageList)) {
            $normalizedConfiguration['require-dev'] = $normalizedRequiredDevPackageList;
        }
        if (count($normalizedAutoloadList)) {
            $normalizedConfiguration['autoload'] = $normalizedAutoloadList;
        }
        if (count($normalizedAutoloadDevList)) {
            $normalizedConfiguration['autoload-dev'] = $normalizedAutoloadDevList;
        }
        if (count($normalizedScriptList)) {
            $normalizedConfiguration['script'] = $normalizedScriptList;
        }

        return $normalizedConfiguration;
    }
}