<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

/**
 * Class ConfigurationDenormalizer
 */
class ConfigurationDenormalizer implements DenormalizerInterface
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

    /**
     * @param array $configuration
     *
     * @return Configuration
     */
    public function denormalize(array $configuration)
    {

        return new Configuration(
            $this->valueOrNull($configuration, ConfigurationNormalizer::KEY_NAME),
            $this->valueOrNull($configuration, ConfigurationNormalizer::KEY_TYPE),
            $this->valueOrNull($configuration, ConfigurationNormalizer::KEY_LICENSE),
            $this->valueOrNull($configuration, ConfigurationNormalizer::KEY_VERSION),
            $this->valueOrNull($configuration, ConfigurationNormalizer::KEY_DESCRIPTION),
            $configuration[ConfigurationNormalizer::KEY_KEYWORDS],
            $this->getNormalizedOrDefault(
                $this->authorListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_AUTHORS,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->packageListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_SUGGEST,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->suggestedPackageListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_SUPPORT,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->supportListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_AUTOLOAD,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->autoloadListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_AUTOLOAD_DEV,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->autoloadListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_REQUIRE,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->packageListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_REQUIRE_DEV,
                []
            ),
            $this->getNormalizedOrDefault(
                $this->scriptListNormalizer,
                $configuration,
                ConfigurationNormalizer::KEY_SCRIPT,
                []
            )
        );
    }

    /**
     * @param DenormalizerInterface $denormalizer
     * @param array                 $configuration
     * @param string                $key
     * @param mixed                 $default
     *
     * @return array
     */
    protected function getNormalizedOrDefault(DenormalizerInterface $denormalizer, array $configuration, $key, $default)
    {
        return isset($configuration[$key]) ? $denormalizer->denormalize($configuration[$key]) : $default;
    }

    /**
     * @param array  $configuration
     * @param string $key
     *
     * @return mixed|null
     */
    protected function valueOrNull(array $configuration, $key)
    {
        return isset($configuration[$key]) ? $configuration[$key] : null;
    }
}
