<?php
namespace Yoanm\ComposerConfigManager\Infrastructure\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Yoanm\ComposerConfigManager\Application\Serializer\Encoder\ComposerEncoder as AppComposerEncoder;

class ComposerEncoder implements EncoderInterface, DecoderInterface
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
        return $this->isSupportedFormat($format);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        $this->appComposerEncoder->decode($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return $this->isSupportedFormat($format);
    }

    /**
     * {@inheritdoc}
     */
    public function isSupportedFormat($format)
    {
        return self::FORMAT === $format;
    }
}
