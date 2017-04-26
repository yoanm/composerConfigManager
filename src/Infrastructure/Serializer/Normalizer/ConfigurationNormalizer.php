<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigNormalizer;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;

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
