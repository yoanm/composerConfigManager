<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

/**
 * Class ConfigurationDenormalizer
 */
class ConfigurationDenormalizer
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
            isset($configuration[ConfigurationNormalizer::KEY_AUTHORS])
                ? $this->authorListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_AUTHORS])
                : []
            ,
            isset($configuration[ConfigurationNormalizer::KEY_PROVIDE])
                ? $this->packageListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_PROVIDE])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_SUGGEST])
                ? $this->suggestedPackageListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_SUGGEST])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_SUPPORT])
                ? $this->supportListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_SUPPORT])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_AUTOLOAD])
                ? $this->autoloadListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_AUTOLOAD])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_AUTOLOAD_DEV])
                ? $this->autoloadListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_AUTOLOAD_DEV])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_REQUIRE])
                ? $this->packageListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_REQUIRE])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_REQUIRE_DEV])
                ? $this->packageListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_REQUIRE_DEV])
                : [],
            isset($configuration[ConfigurationNormalizer::KEY_SCRIPT])
                ? $this->scriptListNormalizer->denormalize($configuration[ConfigurationNormalizer::KEY_SCRIPT])
                : []
        );
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
