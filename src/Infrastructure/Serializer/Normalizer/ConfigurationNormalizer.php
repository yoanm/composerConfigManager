<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigNormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class ConfigurationNormalizer implements NormalizerInterface
{
    /** @var AppConfigNormalizer */
    private $appConfigurationNormalizer;

    /**
     * @param AppConfigNormalizer $appConfigurationNormalizer
     */
    public function __construct(AppConfigNormalizer $appConfigurationNormalizer)
    {
        $this->appConfigurationNormalizer = $appConfigurationNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->appConfigurationNormalizer->normalize($object);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Configuration;
    }
}
