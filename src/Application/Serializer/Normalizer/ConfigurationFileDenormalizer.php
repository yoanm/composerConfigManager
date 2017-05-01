<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\Configuration;
use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

/**
 * Class ConfigurationFileDenormalizer
 */
class ConfigurationFileDenormalizer implements DenormalizerInterface
{
    /** @var ConfigurationDenormalizer */
    private $configurationDenormalizer;

    public function __construct(ConfigurationDenormalizer $configurationDenormalizer)
    {
        $this->configurationDenormalizer = $configurationDenormalizer;
    }

    /**
     * @param array $configuration
     *
     * @return ConfigurationFile
     */
    public function denormalize(array $configuration)
    {
        return new ConfigurationFile(
            $this->configurationDenormalizer->denormalize($configuration),
            array_keys($configuration)
        );
    }
}
