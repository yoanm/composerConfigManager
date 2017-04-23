<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Yoanm\InitRepositoryWithComposer\Domain\Model\Configuration;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Normalizer\ConfigurationNormalizer as AppConfigurationNormalizer;

class ConfigurationNormalizer implements NormalizerInterface
{
    /** @var AppConfigurationNormalizer */
    private $appConfigurationNormalizer;

    /**
     * @param AppConfigurationNormalizer $appConfigurationNormalizer
     */
    public function __construct(AppConfigurationNormalizer $appConfigurationNormalizer)
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