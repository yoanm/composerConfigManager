<?php
namespace Yoanm\InitRepositoryWithComposer\Infrastructure\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Yoanm\InitRepositoryWithComposer\Application\Serializer\Encoder\ComposerEncoder as AppComposerEncoder;

class ComposerEncoder implements EncoderInterface
{
    const FORMAT = 'composer';

    /** @var AppComposerEncoder */
    private $appComposerEncoder;

    public function __construct(AppComposerEncoder $appComposerEncoder)
    {
        $this->appComposerEncoder = $appComposerEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = array())
    {
        return $this->appComposerEncoder->encode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }
}
