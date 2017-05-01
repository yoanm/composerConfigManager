<?php
namespace Yoanm\ComposerConfigManager\Application\Serializer\Normalizer;

use Yoanm\ComposerConfigManager\Domain\Model\ConfigurationFile;

class ConfigurationFileNormalizer
{
    /** @var ConfigurationNormalizer */
    private $configurationNormalizer;

    public function __construct(ConfigurationNormalizer $configurationNormalizer)
    {
        $this->configurationNormalizer = $configurationNormalizer;
    }

    public function normalize(ConfigurationFile $configurationFile)
    {
        $normalizedConfiguration = $this->configurationNormalizer->normalize($configurationFile->getConfiguration());
        $orderedNormalizedConfiguration = [];
        foreach ($configurationFile->getKeyList() as $key) {
            if (isset($normalizedConfiguration[$key])) {
                $orderedNormalizedConfiguration[$key] = $normalizedConfiguration[$key];
                unset($normalizedConfiguration[$key]);
            }
        }
        //append remaining keys
        return array_merge(
            $orderedNormalizedConfiguration,
            $normalizedConfiguration
        );
    }
}
