<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigNormalizer;
use Yoanm\ComposerConfigManager\Application\Serializer\Normalizer\ConfigurationDenormalizer as AppConfigDenormalizer;
use Yoanm\ComposerConfigManager\Domain\Model\Configuration;

class ConfigurationNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @var AppConfigNormalizer */
    private $appConfigurationNormalizer;
    /** @var AppConfigDenormalizer */
    private $appConfigurationDenormalizer;

    /**
     * @param AppConfigNormalizer $appConfigurationNormalizer
     * @param AppConfigDenormalizer $appConfigurationDenormalizer
     */
    public function __construct(
        AppConfigNormalizer $appConfigurationNormalizer,
        AppConfigDenormalizer $appConfigurationDenormalizer
    ) {
        $this->appConfigurationNormalizer = $appConfigurationNormalizer;
        $this->appConfigurationDenormalizer = $appConfigurationDenormalizer;
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

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->appConfigurationDenormalizer->denormalize($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return Configuration::class == $type;
    }
}
